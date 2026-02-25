<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$correo = $_POST['correo'];
$celular = $_POST['celular'];
$titulo = $_POST['titulo'];
$fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
$direccion = $_POST['direccion'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
$rol = 'admin'; // Aseguramos que el rol sea admin para ambos registros

// Validaciones del servidor
if (!preg_match("/^[A-Za-z\s]+$/", $nombre)) {
    die("Nombre inválido.");
}

if (!preg_match("/^[A-Za-z\s]+$/", $apellido)) {
    die("Apellido inválido.");
}

if (!preg_match("/^\d+$/", $cedula)) {
    die("Cédula inválida.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo inválido.");
}

if ($celular && !preg_match("/^\d{10}$/", $celular)) {
    die("Celular inválido.");
}

if (strlen($_POST['contrasena']) < 8) {
    die("La contraseña debe tener al menos 8 caracteres.");
}

// Iniciar una transacción para asegurarnos de que ambos insert se ejecuten correctamente
$conn->begin_transaction();

try {
    // Insertar los datos en la tabla admin
    $sql_admin = "INSERT INTO admin (nombre, apellido, cedula, correo, celular, titulo, fecha_de_nacimiento, direccion, contrasena, rol) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ssssssssss", $nombre, $apellido, $cedula, $correo, $celular, $titulo, $fecha_de_nacimiento, $direccion, $contrasena, $rol);
    $stmt_admin->execute();

    // Obtener el id del admin recién insertado
    $admin_id = $stmt_admin->insert_id;

    // Insertar los datos en la tabla usuarios
    $sql_usuario = "INSERT INTO usuarios (nombre, correo, contrasena, rol, admin_id) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssssi", $nombre, $correo, $contrasena, $rol, $admin_id);
    $stmt_usuario->execute();

    // Si ambos inserts fueron exitosos, confirmamos la transacción
    $conn->commit();

    echo "<script>
            alert('Registro exitoso');
            window.location.href = 'admin';
          </script>";
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    if ($e->getCode() == 1062) {
        // Código de error 1062: Entrada duplicada para clave única
        echo "<script>
                alert('Error: Cédula o correo ya existen.');
                window.location.href = 'admin';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.location.href = 'admin';
              </script>";
    }
}

$stmt_admin->close();
$stmt_usuario->close();
$conn->close();
?>
