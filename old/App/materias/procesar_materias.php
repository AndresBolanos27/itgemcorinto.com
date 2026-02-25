<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$materia = $_POST['materia'];
$categoria_id = $_POST['categoria_id']; // Asegúrate de que este campo esté en tu formulario

// Validaciones del servidor
if (!preg_match("/^[A-Za-z\s]+$/", $materia)) {
    die("Nombre de la materia inválido.");
}

// Iniciar una transacción para asegurarnos de que el insert se ejecute correctamente
$conn->begin_transaction();

try {
    // Verificar si la materia ya existe
    $sql_verificar = "SELECT id FROM materias WHERE materia = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $materia);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        throw new Exception("La materia ya existe.");
    }

    // Insertar los datos en la tabla materias
    $sql_materia = "INSERT INTO materias (materia, categoria_id) VALUES (?, ?)";
    $stmt_materia = $conn->prepare($sql_materia);
    $stmt_materia->bind_param("si", $materia, $categoria_id);
    $stmt_materia->execute();

    // Si el insert fue exitoso, confirmamos la transacción
    $conn->commit();

    echo "<script>
            alert('Registro exitoso');
            window.location.href = 'materias';
          </script>";
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'materias';
          </script>";
} catch (Exception $e) {
    // Si la materia ya existe
    $conn->rollback();

    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'materias';
          </script>";
} finally {
    // Asegurarse de cerrar el statement si fue inicializado
    if (isset($stmt_materia)) {
        $stmt_materia->close();
    }
    if (isset($stmt_verificar)) {
        $stmt_verificar->close();
    }
    $conn->close();
}
?>
