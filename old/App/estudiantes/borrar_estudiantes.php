<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora') {
  echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'estudiantes';
          </script>";
  exit();
}

$id = $_GET['id'];

// Iniciar una transacción
$conn->begin_transaction();

try {
  // Eliminar registros relacionados en la tabla estudiante_grupo
  $sql_estudiante_grupo = "DELETE FROM estudiante_grupo WHERE estudiante_id = ?";
  $stmt_estudiante_grupo = $conn->prepare($sql_estudiante_grupo);
  $stmt_estudiante_grupo->bind_param("i", $id);
  $stmt_estudiante_grupo->execute();

  // Eliminar registros en la tabla usuarios
  $sql_usuario = "DELETE FROM usuarios WHERE estudiante_id = ?";
  $stmt_usuario = $conn->prepare($sql_usuario);
  $stmt_usuario->bind_param("i", $id);
  $stmt_usuario->execute();

  // Eliminar el estudiante de la tabla estudiantes
  $sql_estudiante = "DELETE FROM estudiantes WHERE id = ?";
  $stmt_estudiante = $conn->prepare($sql_estudiante);
  $stmt_estudiante->bind_param("i", $id);
  $stmt_estudiante->execute();

  // Confirmar la transacción
  $conn->commit();

  echo "<script>
            alert('Estudiante eliminado correctamente');
            window.location.href = 'estudiantes';
          </script>";
} catch (Exception $e) {
  // Revertir la transacción si algo falla
  $conn->rollback();
  echo "<script>
            alert('Error al eliminar el estudiante: " . $e->getMessage() . "');
            window.location.href = 'estudiantes';
          </script>";
}

$stmt_estudiante_grupo->close();
$stmt_usuario->close();
$stmt_estudiante->close();
$conn->close();

?>
