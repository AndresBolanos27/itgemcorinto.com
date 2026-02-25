<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora') {
    echo "<script>
              alert('No tienes permiso para acceder a esta página');
              window.location.href = 'cicloescolar';
          </script>";
    exit();
}

$id = $_GET['id'];

// Iniciar una transacción
$conn->begin_transaction();

try {
    // Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM ciclos_escolares WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Confirmar la transacción
    $conn->commit();

    echo "<script>
              alert('Ciclo escolar eliminado correctamente');
              window.location.href = 'cicloescolar';
          </script>";
} catch (Exception $e) {
    // Revertir la transacción si algo falla
    $conn->rollback();
    echo "<script>
              alert('Error al eliminar el ciclo escolar');
              window.location.href = 'cicloescolar';
          </script>";
}

$stmt->close();
$conn->close();
?>
