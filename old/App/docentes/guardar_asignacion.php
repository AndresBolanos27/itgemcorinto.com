<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout';
        </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docente_id = $_POST['docente_id'];
    $grupo_id = $_POST['grupo_id'];
    $materias = $_POST['materia_id'];
    $success = false; // Variable para rastrear si alguna asignación se guarda con éxito

    // Guardar la asignación de cada materia al docente
    foreach ($materias as $materia_id) {
        // Verificar si la asignación ya existe
        $check_sql = "SELECT COUNT(*) as count FROM docente_materias WHERE docente_id = ? AND materia_id = ? AND grupo_id = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("iii", $docente_id, $materia_id, $grupo_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();
        $stmt_check->close();

        if ($row['count'] == 0) {  // Solo insertar si no existe
            $insert_sql = "INSERT INTO docente_materias (docente_id, materia_id, grupo_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iii", $docente_id, $materia_id, $grupo_id);
            $stmt->execute();
            $stmt->close();
            $success = true; // Indicar que al menos una asignación fue exitosa
        } else {
            echo "<script>
                    alert('La materia ya está asignada al docente para este grupo.');
                                    window.location.href = 'add_docentes';

                </script>";
        }
    }

    // Mostrar alerta de éxito solo si se ha guardado al menos una asignación
    if ($success) {
        echo "<script>
                alert('Asignación guardada exitosamente.');
                window.location.href = 'add_docentes';
            </script>";
    }
}
$conn->close();
?>
