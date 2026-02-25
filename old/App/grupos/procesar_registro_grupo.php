<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$codigo = $_POST['Codigo'];
$nombre_grupo = $_POST['nombre_grupo'];
$estado = $_POST['estado'];
$nivel_id = $_POST['nivel_id']; // Obtener el nivel seleccionado por el usuario
$ciclo_id = $_POST['ciclo_id']; // Obtener el ciclo escolar seleccionado por el usuario
$fecha_creacion = date('Y-m-d H:i:s'); // Fecha de creación actual

// Validaciones del servidor
if (!preg_match("/^[A-Za-z0-9]+$/", $codigo)) {
    die("Código inválido. Solo se permiten letras y números.");
}

if (!preg_match("/^[A-Za-z0-9\s]+$/", $nombre_grupo)) {
    die("Nombre del grupo inválido. Solo se permiten letras, números y espacios.");
}


if (!in_array($estado, ['activo', 'inactivo'])) {
    die("Estado inválido.");
}

if (!filter_var($nivel_id, FILTER_VALIDATE_INT)) {
    die("Nivel educativo inválido.");
}

if (!filter_var($ciclo_id, FILTER_VALIDATE_INT)) {
    die("Ciclo escolar inválido.");
}

// Verificar si el código ya existe en la base de datos
$sql_verificar = "SELECT id FROM grupos WHERE Codigo = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("s", $codigo);
$stmt_verificar->execute();
$stmt_verificar->store_result();

if ($stmt_verificar->num_rows > 0) {
    // El código ya existe
    echo "<script>
            alert('Error: El código del grupo ya existe.');
            window.location.href = 'grupos';
          </script>";
    $stmt_verificar->close();
    $conn->close();
    exit();
}

$stmt_verificar->close();

// Iniciar una transacción para asegurarnos de que el insert se ejecute correctamente
$conn->begin_transaction();

try {
    // Insertar los datos en la tabla grupos
    $sql_grupo = "INSERT INTO grupos (Codigo, nombre_grupo, fecha_creacion, estado, nivel_id, ciclo_id) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_grupo = $conn->prepare($sql_grupo);
    $stmt_grupo->bind_param("ssssii", $codigo, $nombre_grupo, $fecha_creacion, $estado, $nivel_id, $ciclo_id);
    $stmt_grupo->execute();

    // Si el insert fue exitoso, confirmamos la transacción
    $conn->commit();

    echo "<script>
            alert('Registro exitoso');
            window.location.href = 'grupos';
          </script>";
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href = 'grupos';
          </script>";
}

$stmt_grupo->close();
$conn->close();
?>
