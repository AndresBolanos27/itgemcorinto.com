<?php
// Incluir dependencias
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
include_once __DIR__ . '/../config/database.php';

// Verificar sesión y permisos
verificar_sesion();

if ($_SESSION['usuario_rol'] !== 'admin') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout';
          </script>";
    exit();
}

// Procesar el formulario de guardar notas si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['grupo_id'])) {
            throw new Exception("El parámetro 'grupo_id' es requerido.");
        }

        $grupo_seleccionado = (int)$_POST['grupo_id'];
        $anio_academico = date('Y');

        if (empty($_POST['notas']) || !is_array($_POST['notas'])) {
            throw new Exception("Los datos de notas no son válidos o están vacíos.");
        }

        $conn->begin_transaction();

        $stmt = $conn->prepare("
            INSERT INTO notas_estudiantes (
                estudiante_id, 
                materia_id, 
                grupo_id, 
                nota, 
                observacion, 
                año_académico
            ) VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                nota = VALUES(nota),
                observacion = VALUES(observacion),
                año_académico = VALUES(año_académico)
        ");

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "iiidss",
            $estudiante_id,
            $materia_id,
            $grupo_seleccionado,
            $nota,
            $observacion,
            $anio_academico
        );

        foreach ($_POST['notas'] as $estudiante_id => $materias) {
            foreach ($materias as $materia_id => $data) {
                $nota = isset($data['nota']) ? (float)$data['nota'] : null;
                if ($nota === null || $nota < 1 || $nota > 5) {
                    throw new Exception("La nota para el estudiante $estudiante_id, materia $materia_id no es válida. Debe estar entre 1 y 5.");
                }

                $observacion = isset($data['observacion']) ? trim($data['observacion']) : '';
                if (strlen($observacion) > 255) {
                    throw new Exception("La observación para el estudiante $estudiante_id, materia $materia_id es demasiado larga.");
                }

                $estudiante_id = (int)$estudiante_id;
                $materia_id = (int)$materia_id;

                if (!$stmt->execute()) {
                    throw new Exception("Error al guardar la nota para el estudiante $estudiante_id, materia $materia_id: " . $stmt->error);
                }
            }
        }

        $conn->commit();

        $location = match ($_SESSION['usuario_rol']) {
            'docente' => "notas_docentes?grupo_id=$grupo_seleccionado",
            'admin' => "calificaciones?grupo_id=$grupo_seleccionado",
            default => 'dashboard'
        };

        header("Location: $location");
        exit;

    } catch (Exception $e) {
        if ($conn->in_transaction) {
            $conn->rollback();
        }

        error_log($e->getMessage());
        $_SESSION['error'] = "Error al procesar la solicitud: " . $e->getMessage();
        
        $location = match ($_SESSION['usuario_rol']) {
            'docente' => "notas_docentes?grupo_id=$grupo_seleccionado",
            'admin' => "calificaciones?grupo_id=$grupo_seleccionado",
            default => 'dashboard'
        };

        header("Location: $location");
        exit;
    }
}

$grupos_sql = "
    SELECT grupos.id, grupos.nombre_grupo, ciclos_escolares.fecha_fin 
    FROM grupos 
    JOIN ciclos_escolares ON grupos.ciclo_id = ciclos_escolares.id 
    WHERE ciclos_escolares.fecha_inicio <= CURDATE() 
      AND ciclos_escolares.fecha_fin >= CURDATE()
    ORDER BY grupos.nombre_grupo";
$grupos_result = $conn->query($grupos_sql);

if (!$grupos_result) {
    die("Error al obtener los grupos: " . $conn->error);
}

$grupos_validos = [];
$ciclo_fecha_fin = null;

while ($grupo = $grupos_result->fetch_assoc()) {
    $grupos_validos[$grupo['id']] = $grupo['nombre_grupo'];
    if ($ciclo_fecha_fin === null) {
        $ciclo_fecha_fin = $grupo['fecha_fin'];
    }
}

$dias_restantes = null;
if ($ciclo_fecha_fin) {
    $fecha_fin = new DateTime($ciclo_fecha_fin);
    $fecha_actual = new DateTime();
    $intervalo = $fecha_actual->diff($fecha_fin);
    $dias_restantes = $intervalo->days;
}

$grupo_seleccionado = isset($_GET['grupo_id']) ? (int)$_GET['grupo_id'] : 0;

if ($grupo_seleccionado && !array_key_exists($grupo_seleccionado, $grupos_validos)) {
    die("Grupo no válido seleccionado.");
}

$estudiantes_info = [];
$materias = [];
$notas_por_estudiante = [];

if ($grupo_seleccionado) {
    $estudiantes_sql = "
        SELECT 
            estudiantes.id AS estudiante_id,
            estudiantes.nombre AS estudiante_nombre,
            estudiantes.apellido AS estudiante_apellido,
            notas_estudiantes.materia_id,
            notas_estudiantes.nota,
            notas_estudiantes.observacion
        FROM estudiantes
        JOIN estudiante_grupo ON estudiantes.id = estudiante_grupo.estudiante_id
        LEFT JOIN notas_estudiantes ON estudiantes.id = notas_estudiantes.estudiante_id 
        AND notas_estudiantes.grupo_id = estudiante_grupo.grupo_id
        WHERE estudiante_grupo.grupo_id = ?
        ORDER BY estudiantes.nombre, notas_estudiantes.materia_id";

    $stmt_estudiantes = $conn->prepare($estudiantes_sql);
    if (!$stmt_estudiantes) {
        die("Error al preparar la consulta de estudiantes: " . $conn->error);
    }
    $stmt_estudiantes->bind_param("i", $grupo_seleccionado);
    $stmt_estudiantes->execute();
    $estudiantes_result = $stmt_estudiantes->get_result();

    $materias_sql = "
        SELECT 
            materias.id AS materia_id,
            materias.materia AS materia_nombre
        FROM materias
        JOIN grupo_materias ON materias.id = grupo_materias.materia_id
        WHERE grupo_materias.grupo_id = ?
        ORDER BY materias.materia";

    $stmt_materias = $conn->prepare($materias_sql);
    if (!$stmt_materias) {
        die("Error al preparar la consulta de materias: " . $conn->error);
    }
    $stmt_materias->bind_param("i", $grupo_seleccionado);
    $stmt_materias->execute();
    $materias_result = $stmt_materias->get_result();

    while ($materia = $materias_result->fetch_assoc()) {
        $materias[] = $materia;
    }

    while ($estudiante = $estudiantes_result->fetch_assoc()) {
        $estudiantes_info[$estudiante['estudiante_id']] = [
            'nombre' => $estudiante['estudiante_nombre'],
            'apellido' => $estudiante['estudiante_apellido']
        ];
        $notas_por_estudiante[$estudiante['estudiante_id']][$estudiante['materia_id']] = [
            'nota' => $estudiante['nota'],
            'observacion' => $estudiante['observacion']
        ];
    }

    $stmt_estudiantes->close();
    $stmt_materias->close();
}
?>
<div class="container mx-auto my-10">
    <h1 class="text-3xl font-semibold text-center mb-8">Asignar Notas a Estudiantes</h1>

    <?php if ($dias_restantes !== null): ?>
        <p class="text-center mb-6">Días restantes para que finalice el ciclo académico actual: <span class="font-bold"><?php echo $dias_restantes; ?> días</span></p>
    <?php endif; ?>

    <form method="GET" action="" class="flex justify-center mb-8">
        <div class="w-full max-w-md">
            <label for="grupo_id" class="block text-sm font-medium text-gray-700">Seleccione un grupo:</label>
            <div class="mt-1 relative">
                <select name="grupo_id" id="grupo_id" required class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">-- Seleccione un grupo --</option>
                    <?php foreach ($grupos_validos as $id => $nombre_grupo): ?>
                        <option value="<?php echo $id; ?>" <?php echo $grupo_seleccionado == $id ? 'selected' : ''; ?>
                            ><?php echo htmlspecialchars($nombre_grupo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="w-full py-2.5 rounded-md bg-blue-500 text-white hover:bg-blue-600">Filtrar</button>
            </div>
        </div>
    </form>

    <?php if ($grupo_seleccionado && !empty($estudiantes_info) && !empty($materias)): ?>
        <form method="POST" action="">
            <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo_seleccionado); ?>">
            <h2 class="text-2xl font-semibold mb-6">Estudiantes y Materias del Grupo: <?php echo htmlspecialchars($grupos_validos[$grupo_seleccionado]); ?></h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4">Estudiante</th>
                            <?php foreach ($materias as $materia): ?>
                                <th class="py-3 px-4"><?php echo htmlspecialchars($materia['materia_nombre']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        <?php foreach ($estudiantes_info as $estudiante_id => $estudiante): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?></td>
                                <?php foreach ($materias as $materia): ?>
                                    <?php
                                    $nota = $notas_por_estudiante[$estudiante_id][$materia['materia_id']]['nota'] ?? '';
                                    $observacion = $notas_por_estudiante[$estudiante_id][$materia['materia_id']]['observacion'] ?? '';
                                    ?>
                                    <td class="py-3 px-4">
                                        <input type="number" step="0.01" min="1" max="5" name="notas[<?php echo $estudiante_id; ?>][<?php echo $materia['materia_id']; ?>][nota]" value="<?php echo htmlspecialchars($nota); ?>" placeholder="Nota" class="w-full mb-2 px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" />
                                        <input type="text" name="notas[<?php echo $estudiante_id; ?>][<?php echo $materia['materia_id']; ?>][observacion]" value="<?php echo htmlspecialchars($observacion); ?>" placeholder="Observación" class="w-full px-2 py-1 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" />
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center mt-6">
                <button type="submit" class="w-60 py-2.5 rounded-md bg-green-500 text-white hover:bg-green-600">Guardar Notas</button>
            </div>
        </form>
    <?php elseif ($grupo_seleccionado): ?>
        <p class="text-center mt-10 text-xl">No hay estudiantes o materias asignadas a este grupo.</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
include_once __DIR__ . '/../footer.php';
?>
