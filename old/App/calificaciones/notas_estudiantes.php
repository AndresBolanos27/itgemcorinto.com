<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
include_once __DIR__ . '/../config/database.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'estudiante') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout'; // Redirige a la página de inicio de sesión o a donde desees
          </script>";
    exit();
}

// Obtener el ID del estudiante desde la sesión
$estudiante_id = $_SESSION['usuario_id'];

// Obtener el grupo al que pertenece el estudiante
$grupo_sql = "
    SELECT grupo_id
    FROM estudiante_grupo
    WHERE estudiante_id = ?
";
$stmt_grupo = $conn->prepare($grupo_sql);
$stmt_grupo->bind_param("i", $estudiante_id);
$stmt_grupo->execute();
$result_grupo = $stmt_grupo->get_result();

if ($result_grupo->num_rows > 0) {
    $grupo = $result_grupo->fetch_assoc();
    $grupo_id = $grupo['grupo_id'];

    // Obtener las materias del grupo
    $materias_sql = "
        SELECT materias.id AS materia_id, materias.materia AS materia_nombre
        FROM materias
        JOIN grupo_materias ON materias.id = grupo_materias.materia_id
        WHERE grupo_materias.grupo_id = ?
        ORDER BY materias.materia
    ";
    $stmt_materias = $conn->prepare($materias_sql);
    $stmt_materias->bind_param("i", $grupo_id);
    $stmt_materias->execute();
    $result_materias = $stmt_materias->get_result();

    $materias = [];
    while ($materia = $result_materias->fetch_assoc()) {
        $materias[] = $materia;
    }

    // Obtener las notas del estudiante
    $notas_sql = "
        SELECT notas_estudiantes.materia_id, notas_estudiantes.nota, notas_estudiantes.observacion
        FROM notas_estudiantes
        WHERE notas_estudiantes.estudiante_id = ? AND notas_estudiantes.grupo_id = ?
    ";
    $stmt_notas = $conn->prepare($notas_sql);
    $stmt_notas->bind_param("ii", $estudiante_id, $grupo_id);
    $stmt_notas->execute();
    $result_notas = $stmt_notas->get_result();

    $notas = [];
    while ($nota = $result_notas->fetch_assoc()) {
        $notas[$nota['materia_id']] = [
            'nota' => $nota['nota'],
            'observacion' => $nota['observacion']
        ];
    }

} else {
    echo "<div class='text-center mt-20 text-xl'>No estás asignado a ningún grupo.</div>";
    exit();
}

// Cerrar las conexiones y declaraciones
$stmt_grupo->close();
$stmt_materias->close();
$stmt_notas->close();
$conn->close();
?>

<div class="container mx-auto my-10">
    <h1 class="text-3xl font-semibold text-center mb-8">Mis Materias y Notas</h1>

    <?php if (!empty($materias)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Materia</th>
                        <th class="py-3 px-6">Nota</th>
                        <th class="py-3 px-6">Observación</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php foreach ($materias as $materia): ?>
                        <?php
                        $materia_id = $materia['materia_id'];
                        $nota = isset($notas[$materia_id]) ? $notas[$materia_id]['nota'] : 'Sin nota';
                        $observacion = isset($notas[$materia_id]) ? $notas[$materia_id]['observacion'] : '';
                        ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6"><?php echo htmlspecialchars($materia['materia_nombre']); ?></td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($nota); ?></td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($observacion); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center mt-20 text-xl">No hay materias asignadas a tu grupo.</p>
    <?php endif; ?>
</div>

<?php
include_once __DIR__ . '/../footer.php';
?>
    