<?php
include_once __DIR__ . '/../config/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$correo = $_POST['correo'];
$celular = $_POST['celular'];
$sexo = $_POST['sexo'];
$fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
$direccion = $_POST['direccion'];
$eps = $_POST['eps'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
$grupo = $_POST['grupo'];
$grupo_etnico = $_POST['grupo_etnico'];
$acudiente = $_POST['acudiente'];
$numero_acudiente = $_POST['numero_acudiente'];

// Inicializar $documentos como NULL
$documentos = NULL;

if (isset($_FILES['documentos']) && $_FILES['documentos']['error'] != UPLOAD_ERR_NO_FILE) {
    // Comprobar si hubo un error en la subida
    if ($_FILES['documentos']['error'] != UPLOAD_ERR_OK) {
        die("Error al subir el archivo.");
    }

    // Comprobar tamaño del archivo
    if ($_FILES['documentos']['size'] > 67108864) { // 64MB
        die("El archivo es demasiado grande.");
    }

    // Validar el tipo de archivo (opcional)
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($_FILES['documentos']['type'], $allowed_types)) {
        die("Tipo de archivo no permitido.");
    }

    // Crear la carpeta del estudiante si no existe
    $estudiante_carpeta = __DIR__ . '/../App/estudiantesdoc/documentos/' . $nombre . '_' . $apellido;
    if (!is_dir($estudiante_carpeta)) {
        mkdir($estudiante_carpeta, 0777, true);
    }

    // Generar un nombre único para el archivo y definir la ruta
    $documento_nombre = uniqid() . '-' . basename($_FILES['documentos']['name']);
    $documento_ruta = $estudiante_carpeta . '/' . $documento_nombre;

    // Mover el archivo a la ruta deseada
    if (!move_uploaded_file($_FILES['documentos']['tmp_name'], $documento_ruta)) {
        die("Error al mover el archivo.");
    }

    // Guardar la ruta del archivo para la base de datos
    $documentos = '/App/estudiantesdoc/documentos/' . $nombre . '_' . $apellido . '/' . $documento_nombre;
}

// Rol predeterminado
$rol = 'estudiante';

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

// Iniciar una transacción para asegurarnos de que ambos inserts se ejecuten correctamente
$conn->begin_transaction();

try {
    // Insertar en la tabla estudiantes con estado 'activo'
    $stmt = $conn->prepare("INSERT INTO estudiantes (
        nombre, apellido, cedula, correo, celular, sexo, fecha_de_nacimiento, direccion, eps, contrasena, documentos, rol, grupo_etnico, acudiente, numero_acudiente, grupo, estado
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo')");

    $stmt->bind_param("ssssssssssssssss", $nombre, $apellido, $cedula, $correo, $celular, $sexo, $fecha_de_nacimiento, $direccion, $eps, $contrasena, $documentos, $rol, $grupo_etnico, $acudiente, $numero_acudiente, $grupo);
    $stmt->execute();
    $estudiante_id = $conn->insert_id;

    // Insertar en la tabla estudiante_grupo
    $stmt = $conn->prepare("INSERT INTO estudiante_grupo (estudiante_id, grupo_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $estudiante_id, $grupo);
    $stmt->execute();

    // Insertar en la tabla usuarios
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol, estudiante_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombre, $correo, $contrasena, $rol, $estudiante_id);
    $stmt->execute();

    // Confirmar transacción
    $conn->commit();
    echo "<script>
        alert('Registro exitoso.');
        window.location.href = 'estudiantes';
    </script>";
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    echo "<script>
        alert('Error al registrar: " . $e->getMessage() . "');
        window.history.back();
    </script>";
}

$conn->close();
?>
