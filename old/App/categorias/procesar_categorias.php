<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$categoria = $_POST['categoria'];



try {
    // Iniciar una transacción para asegurarnos de que el insert se ejecute correctamente
    $conn->begin_transaction();

    // Verificar si la categoría ya existe
    $sql_verificar = "SELECT id FROM categorias WHERE categoria = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    if (!$stmt_verificar) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_verificar->bind_param("s", $categoria);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        throw new Exception("La categoría ya existe.");
    }

    // Insertar los datos en la tabla categorias
    $sql_categoria = "INSERT INTO categorias (categoria) VALUES (?)";
    $stmt_categoria = $conn->prepare($sql_categoria);
    if (!$stmt_categoria) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_categoria->bind_param("s", $categoria);
    $stmt_categoria->execute();

    // Si el insert fue exitoso, confirmamos la transacción
    $conn->commit();

    // Mostrar alerta de éxito y redireccionar a la página de categorías
    echo "<script>
            alert('Categoría registrada exitosamente.');
            window.location.href = 'categorias';
          </script>";
} catch (Exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    // Mostrar alerta de error y redireccionar
    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.history.back();
          </script>";
} finally {
    // Cerrar los statements y la conexión
    if (isset($stmt_verificar)) {
        $stmt_verificar->close();
    }
    if (isset($stmt_categoria)) {
        $stmt_categoria->close();
    }
    $conn->close();
}
?>
