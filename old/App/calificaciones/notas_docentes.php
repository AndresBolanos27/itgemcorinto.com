<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
include_once __DIR__ . '/../config/database.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'docente') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout'; // Redirige a la página de inicio de sesión o a donde desees
        </script>";
    exit();
}

// Obtener el ID del docente desde la sesión
$docente_id = $_SESSION['usuario_id'];

// Obtener la lista de grupos que tienen asignado al docente dentro de un ciclo académico activo
$grupos_sql = "
    SELECT DISTINCT grupos.id, grupos.nombre_grupo, ciclos_escolares.fecha_fin
    FROM grupos
    JOIN docente_materias ON grupos.id = docente_materias.grupo_id
    JOIN ciclos_escolares ON grupos.ciclo_id = ciclos_escolares.id
    WHERE docente_materias.docente_id = ?
      AND ciclos_escolares.fecha_inicio <= CURDATE()
      AND ciclos_escolares.fecha_fin >= CURDATE()
    ORDER BY grupos.nombre_grupo";

$stmt_grupos = $conn->prepare($grupos_sql);
$stmt_grupos->bind_param("i", $docente_id);
$stmt_grupos->execute();
$grupos_result = $stmt_grupos->get_result();

// Crear una lista de grupos válidos y obtener la fecha de fin del ciclo
$grupos_validos = [];
$ciclo_fecha_fin = null;

while ($grupo = $grupos_result->fetch_assoc()) {
    $grupos_validos[$grupo['id']] = $grupo['nombre_grupo'];
    // Almacenar la fecha de fin del ciclo del primer grupo válido
    if ($ciclo_fecha_fin === null) {
        $ciclo_fecha_fin = $grupo['fecha_fin'];
    }
}

// Calcular los días restantes para el ciclo académico actual
$dias_restantes = null;
if ($ciclo_fecha_fin) {
    $fecha_fin = new DateTime($ciclo_fecha_fin);
    $fecha_actual = new DateTime();
    $intervalo = $fecha_actual->diff($fecha_fin);
    $dias_restantes = $intervalo->days; // Obtener el número de días restantes
}

// Variable para almacenar el grupo seleccionado
$grupo_seleccionado = isset($_GET['grupo_id']) ? $_GET['grupo_id'] : '';

// Verificar si el grupo seleccionado está en la lista de grupos válidos
if (!empty($grupo_seleccionado) && !array_key_exists($grupo_seleccionado, $grupos_validos)) {
    die("Grupo no válido seleccionado.");
}

// Inicializar variables
$estudiantes_info = [];  // Array para almacenar información de estudiantes
$materias = [];  // Inicializar el array de materias
$notas_por_estudiante = [];

if (!empty($grupo_seleccionado)) {
    // Consulta para obtener estudiantes del grupo seleccionado con sus notas por materia
    $estudiantes_sql = "SELECT 
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
    $stmt_estudiantes->bind_param("i", $grupo_seleccionado);
    $stmt_estudiantes->execute();
    $estudiantes_result = $stmt_estudiantes->get_result();

    // Consulta para obtener materias del grupo seleccionado que están asignadas al docente
    $materias_sql = "SELECT 
                        DISTINCT materias.id AS materia_id,
                        materias.materia AS materia_nombre
                    FROM materias
                    JOIN grupo_materias ON materias.id = grupo_materias.materia_id
                    JOIN docente_materias ON materias.id = docente_materias.materia_id
                    WHERE grupo_materias.grupo_id = ? 
                      AND docente_materias.docente_id = ? 
                      AND docente_materias.grupo_id = ? 
                    ORDER BY materias.materia";

    $stmt_materias = $conn->prepare($materias_sql);
    $stmt_materias->bind_param("iii", $grupo_seleccionado, $docente_id, $grupo_seleccionado);
    $stmt_materias->execute();
    $materias_result = $stmt_materias->get_result();

    // Almacenar materias en un array para su uso posterior
    while ($materia = $materias_result->fetch_assoc()) {
        $materias[] = $materia;
    }

    // Organizar las notas por estudiante y materia, y almacenar información del estudiante
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

    // Cerrar declaraciones
    $stmt_estudiantes->close();
    $stmt_materias->close();
}
?>
<div class="container mx-auto my-10">
    <h1 class="text-3xl font-semibold text-center mb-8">Asignar Notas a Estudiantes</h1>

    <!-- Mostrar días restantes para el ciclo académico actual -->
    <?php if ($dias_restantes !== null): ?>
        <p class="text-center mb-6">Días restantes para que finalice el ciclo académico actual: <span class="font-bold"><?php echo $dias_restantes; ?> días</span></p>
    <?php endif; ?>

    <!-- Formulario para seleccionar el grupo -->
    <form method="GET" action="" class="flex justify-center mb-8">
        <div class="w-full max-w-md">
            <label for="grupo_id" class="block text-sm font-medium text-gray-700">Seleccione un grupo:</label>
            <div class="mt-1 relative">
                <select name="grupo_id" id="grupo_id" required class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">-- Seleccione un grupo --</option>
                    <?php foreach ($grupos_validos as $id => $nombre_grupo): ?>
                        <option value="<?php echo $id; ?>" <?php echo $grupo_seleccionado == $id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($nombre_grupo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="w-full py-2.5 rounded-md bg-blue-500 text-white hover:bg-blue-600">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Mostrar formulario de asignación de notas -->
    <?php if (!empty($grupo_seleccionado) && !empty($estudiantes_info) && !empty($materias)): ?>
        <form method="POST" action="guardar_notas">
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
                                    $nota = isset($notas_por_estudiante[$estudiante_id][$materia['materia_id']]) ? $notas_por_estudiante[$estudiante_id][$materia['materia_id']]['nota'] : '';
                                    $observacion = isset($notas_por_estudiante[$estudiante_id][$materia['materia_id']]) ? $notas_por_estudiante[$estudiante_id][$materia['materia_id']]['observacion'] : '';
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
    <?php elseif (!empty($grupo_seleccionado)): ?>
        <p class="text-center mt-10 text-xl">No hay estudiantes o materias asignadas a este grupo.</p>
    <?php endif; ?>
</div>

<?php
// Cerrar la conexión y las declaraciones preparadas
$stmt_grupos->close();
$conn->close();
include_once __DIR__ . '/../footer.php';
?>
