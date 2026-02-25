<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora') {
  echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'docentes';
          </script>";
  exit();
}

$id = $_GET['id'];

// Iniciar una transacción
$conn->begin_transaction();

try {
  // Preparar y ejecutar la consulta de eliminación de la tabla usuarios
  $sql_usuario = "DELETE FROM usuarios WHERE docente_id = ?";
  $stmt_usuario = $conn->prepare($sql_usuario);
  $stmt_usuario->bind_param("i", $id);
  $stmt_usuario->execute();

  // Preparar y ejecutar la consulta de eliminación de la tabla docentes
  $sql_docente = "DELETE FROM docentes WHERE id = ?";
  $stmt_docente = $conn->prepare($sql_docente);
  $stmt_docente->bind_param("i", $id);
  $stmt_docente->execute();

  // Confirmar la transacción
  $conn->commit();

  echo "<script>
            alert('Docente eliminado correctamente');
            window.location.href = 'docentes';
          </script>";
} catch (Exception $e) {
  // Revertir la transacción si algo falla
  $conn->rollback();
  echo "<script>
            alert('Error al eliminar el docente: " . $e->getMessage() . "');
            window.location.href = 'docentes';
          </script>";
}

$stmt_docente->close();
$stmt_usuario->close();
$conn->close();
?>
