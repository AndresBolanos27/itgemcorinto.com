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
$sexo = $_POST['sexo'];
$titulo = $_POST['titulo'];
$fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
$direccion = $_POST['direccion'];
$eps = $_POST['eps'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
$pension = $_POST['pension'];
$caja_comp = $_POST['caja_comp'];

$fecha_registro = date('Y-m-d H:i:s');
$rol = 'docentes'; // Asegúrate de que el rol sea correcto

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
    // Manejo de la subida de archivos
    $documentos = NULL; // Inicializar la variable

    if (isset($_FILES['documentos']) && $_FILES['documentos']['error'] != UPLOAD_ERR_NO_FILE) {
        // Comprobar si hubo un error en la subida
        if ($_FILES['documentos']['error'] != UPLOAD_ERR_OK) {
            throw new Exception("Error al subir el archivo.");
        }

        // Comprobar tamaño del archivo
        if ($_FILES['documentos']['size'] > 67108864) { // 64MB
            throw new Exception("El archivo es demasiado grande.");
        }

        // Validar el tipo de archivo (opcional)
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($_FILES['documentos']['type'], $allowed_types)) {
            throw new Exception("Tipo de archivo no permitido.");
        }

        // Crear la carpeta del docente si no existe
        $docente_carpeta = __DIR__ . '/../App/docentes/documentos/' . $nombre . '_' . $apellido;
        if (!is_dir($docente_carpeta)) {
            mkdir($docente_carpeta, 0777, true);
        }

        // Generar un nombre único para el archivo y definir la ruta
        $documento_nombre = uniqid() . '-' . basename($_FILES['documentos']['name']);
        $documento_ruta = $docente_carpeta . '/' . $documento_nombre;

        // Mover el archivo a la ruta deseada
        if (!move_uploaded_file($_FILES['documentos']['tmp_name'], $documento_ruta)) {
            throw new Exception("Error al mover el archivo.");
        }

        // Guardar la ruta del archivo para la base de datos
        $documentos = 'App/docentes/documentos/' . $nombre . '_' . $apellido . '/' . $documento_nombre;
    }

    // Insertar en la tabla docentes
    $stmt = $conn->prepare("INSERT INTO docentes (nombre, apellido, cedula, correo, celular, sexo, titulo, fecha_de_nacimiento, direccion, eps, contrasena, pension, caja_comp, documentos, fecha_registro, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", $nombre, $apellido, $cedula, $correo, $celular, $sexo, $titulo, $fecha_de_nacimiento, $direccion, $eps, $contrasena, $pension, $caja_comp, $documentos, $fecha_registro, $rol);
    $stmt->execute();
    $docente_id = $conn->insert_id;

    // Insertar en la tabla usuarios
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol, docente_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombre, $correo, $contrasena, $rol, $docente_id);
    $stmt->execute();

    // Confirmar transacción
    $conn->commit();

    // Mostrar alerta de éxito y redireccionar a la página de docentes
    echo "<script>
        alert('Registro exitoso.');
        window.location.href = 'docentes';
    </script>";
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    echo "<script>
        alert('Error al registrar: " . $e->getMessage() . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
