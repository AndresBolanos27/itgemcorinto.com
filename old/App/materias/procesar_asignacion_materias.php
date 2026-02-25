<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$grupo_id = $_POST['grupo_id'];
$materia_id = $_POST['materia_id'];

// Validaciones del servidor
if (!isset($grupo_id, $materia_id)) {
    die("Datos incompletos.");
}

// Iniciar una transacción para asegurarnos de que el insert se ejecute correctamente
$conn->begin_transaction();

try {
    // Verificar si la asignación ya existe
    $sql_verificar = "SELECT id FROM grupo_materias WHERE grupo_id = ? AND materia_id = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("ii", $grupo_id, $materia_id);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        throw new Exception("La asignación ya existe.");
    }

    // Insertar la asignación en la tabla grupo_materias
    $sql_asignacion = "INSERT INTO grupo_materias (grupo_id, materia_id) VALUES (?, ?)";
    $stmt_asignacion = $conn->prepare($sql_asignacion);
    $stmt_asignacion->bind_param("ii", $grupo_id, $materia_id);
    $stmt_asignacion->execute();

    // Si el insert fue exitoso, confirmamos la transacción
    $conn->commit();

    echo "<script>
            alert('Asignación exitosa');
            window.location.href = 'add_materias';
          </script>";
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'add_materias';
          </script>";
} catch (Exception $e) {
    // Si la asignación ya existe
    $conn->rollback();

    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'add_materias';
          </script>";
} finally {
    // Asegurarse de cerrar el statement si fue inicializado
    if (isset($stmt_asignacion)) {
        $stmt_asignacion->close();
    }
    if (isset($stmt_verificar)) {
        $stmt_verificar->close();
    }
    $conn->close();
}
