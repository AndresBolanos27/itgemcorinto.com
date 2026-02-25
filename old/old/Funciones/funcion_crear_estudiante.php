<?php
include '../App/conexion.php';

function insertarEstudiante($grupo_id, $nombre, $fecha_nacimiento, $lugar_nacimiento, $tipo_documento, $documento_identidad, $lugar_expedicion, $genero, $direccion, $telefono, $nombre_acudiente, $tipo_documento_acudiente, $documento_identidad_acudiente, $eps, $correo, $ruta_pdf, $conexion)
{
    // Verificar si ya existe un estudiante con el mismo nombre, documento de identidad o correo
    $sql_verificacion = "SELECT * FROM estudiantes WHERE nombre = '$nombre' OR documento_identidad = '$documento_identidad' OR correo = '$correo'";
    $resultado_verificacion = $conexion->query($sql_verificacion);

    if ($resultado_verificacion->num_rows > 0) {
        // Si ya existe un estudiante con alguno de estos datos, mostrar un mensaje de error
        echo "<script>alert('Ya existe un estudiante con alguno de estos datos.'); window.location.href = '../App/estudiantes.php';</script>";
    } else {
        // Si no existe, proceder con la inserción
        $sql = "INSERT INTO estudiantes (nombre, fecha_nacimiento, lugar_nacimiento, tipo_documento, documento_identidad, lugar_expedicion, genero, direccion, telefono, nombre_acudiente, tipo_documento_acudiente, documento_identidad_acudiente, eps, correo, grupo_id, estado_matricula, ruta_pdf)
        VALUES ('$nombre', '$fecha_nacimiento', '$lugar_nacimiento', '$tipo_documento', '$documento_identidad', '$lugar_expedicion', '$genero', '$direccion', '$telefono', '$nombre_acudiente', '$tipo_documento_acudiente', '$documento_identidad_acudiente', '$eps', '$correo', '$grupo_id', 'Matriculado', '$ruta_pdf')";

        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Nuevo estudiante insertado correctamente.'); window.location.href = '../App/estudiantes.php';</script>";

            // Ahora procedemos a insertar en la segunda base de datos
            $segunda_conexion = new mysqli("localhost", "santand1", "AndresBrown11@", "santand1_carteraitgem");
            if ($segunda_conexion->connect_error) {
                die("Error de conexión a la base de datos santand1_carteraitgem: " . $segunda_conexion->connect_error);
            }

            // Inserción en la segunda base de datos
            $sql_insertar_segunda = "INSERT INTO clientes (identidad, num_identidad, nombre, telefono, correo, direccion, fecha)
                                     VALUES ('$tipo_documento', '$documento_identidad', '$nombre', '$telefono', '$correo', '$direccion', '$fecha_nacimiento')";

            if ($segunda_conexion->query($sql_insertar_segunda) === TRUE) {
                echo "<script>alert('Nuevo estudiante insertado correctamente en la base de datos santand1_carteraitgem.');</script>";
            } else {
                echo "Error al insertar estudiante en la base de datos santand1_carteraitgem: " . $segunda_conexion->error;
            }

            // Cerrar la conexión a la segunda base de datos
            $segunda_conexion->close();

            $id_usuario = $conexion->insert_id;
            $nombreUsuario = $documento_identidad;
            $contrasena = password_hash($documento_identidad, PASSWORD_DEFAULT);
            $sqlUsuarios = "INSERT INTO usuarios (nombre_usuario, contrasena, rol)
                            VALUES ('$nombreUsuario', '$contrasena', 'estudiante')";

            if ($conexion->query($sqlUsuarios) === TRUE) {
                $id_usuario_insertado = $conexion->insert_id;
                $sqlUpdate = "UPDATE estudiantes SET usuario_id = $id_usuario_insertado WHERE correo = '$correo'";
                if ($conexion->query($sqlUpdate) === TRUE) {
                } else {
                    echo "Error al actualizar campo usuario_id: " . $conexion->error;
                }
            } else {
                echo "Error al insertar datos de docente en la tabla usuarios: " . $conexion->error;
            }
        } else {
            echo "Error al insertar estudiante: " . $conexion->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $lugar_nacimiento = $_POST["lugar_nacimiento"];
    $tipo_documento = $_POST["tipo_documento"];
    $documento_identidad = $_POST["documento_identidad"];
    $lugar_expedicion = $_POST["lugar_expedicion"];
    $genero = $_POST["genero"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $nombre_acudiente = $_POST["nombre_acudiente"];
    $tipo_documento_acudiente = $_POST["tipo_documento_acudiente"];
    $documento_identidad_acudiente = $_POST["documento_identidad_acudiente"];
    $eps = $_POST["eps"];
    $correo = $_POST["correo"];
    $grupo_id = $_POST["grupo_id"];

    // Manejar la subida del archivo
    $target_dir = "../uploads/";  // Directorio donde se guardarán los archivos
    $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $nombre . "_" . time() . "." . $fileType;  // Añadir el nombre del estudiante y timestamp al nombre del archivo
    $uploadOk = 1;

    // Verificar si el archivo es un PDF o un Word
    if($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "Lo sentimos, solo se permiten archivos PDF, DOC y DOCX.";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk es 0 por algún error
    if ($uploadOk == 0) {
        echo "Lo sentimos, tu archivo no fue subido.";
    // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "El archivo ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " ha sido subido.";
            insertarEstudiante($grupo_id, $nombre, $fecha_nacimiento, $lugar_nacimiento, $tipo_documento, $documento_identidad, $lugar_expedicion, $genero, $direccion, $telefono, $nombre_acudiente, $tipo_documento_acudiente, $documento_identidad_acudiente, $eps, $correo, $target_file, $conexion);
        } else {
            echo "Lo sentimos, hubo un error al subir tu archivo.";
        }
    }
}
?>
