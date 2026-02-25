<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

if (isset($_POST['materia_id']) && isset($_POST['grupo_id'])) {
    $materia_id = $_POST['materia_id'];
    $grupo_id = $_POST['grupo_id'];

    $sql_borrar = "DELETE FROM grupo_materias WHERE materia_id = ? AND grupo_id = ?";
    $stmt = $conn->prepare($sql_borrar);
    $stmt->bind_param("ii", $materia_id, $grupo_id);

    if ($stmt->execute()) {
        echo "Materia eliminada exitosamente.";
    } else {
        echo "Error al eliminar la materia.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Datos incompletos.";
}
?>
