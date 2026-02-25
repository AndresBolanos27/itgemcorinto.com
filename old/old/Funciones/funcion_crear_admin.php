<?php
// Incluir el archivo de conexión a la base de datos
include '../App/conexion.php';

function insertarAdministrador($nombre, $apellido, $fechaNacimiento, $lugarNacimiento, $tipoDocumento, $documentoIdentidad, $genero, $direccion, $telefono, $eps, $titulo, $email, $conexion)
{
    // Verificar si ya existe un administrador con el mismo documento de identidad o correo
    $sql_verificacion = "SELECT * FROM administradores WHERE documento_identidad = '$documentoIdentidad' OR email = '$email'";
    $resultado_verificacion = $conexion->query($sql_verificacion);

    if ($resultado_verificacion->num_rows > 0) {
        // Si ya existe un administrador con alguno de estos datos, mostrar un mensaje de error
        echo "<script>alert('Ya existe un administrador con alguno de estos datos.'); window.location.href = '../App/administradores.php';</script>";
    } else {
        // Si no existe, proceder con la inserción
        $sql = "INSERT INTO administradores (nombre, apellido, fecha_nacimiento, lugar_nacimiento, tipo_documento, documento_identidad, genero, direccion, telefono, eps, titulo, email, usuario_id) 
                VALUES ('$nombre', '$apellido', '$fechaNacimiento', '$lugarNacimiento', '$tipoDocumento', '$documentoIdentidad', '$genero', '$direccion', '$telefono', '$eps', '$titulo', '$email', NULL)";

        // Ejecutar la consulta y verificar si fue exitosa
        if ($conexion->query($sql) === TRUE) {
            // Inserción exitosa
            echo "<script>alert('Nuevo administrador insertado correctamente.'); window.location.href = '../App/administradores.php';</script>";

            // Obtener el ID del usuario recién insertado en la tabla usuarios
            $id_usuario = $conexion->insert_id;

            // Insertar también en la tabla usuarios
            $nombreUsuario = $documentoIdentidad; // El nombre de usuario será el mismo que el documento de identidad
            $contrasena = password_hash($documentoIdentidad, PASSWORD_DEFAULT); // Usar el documento de identidad como contraseña inicial

            // Preparar la consulta SQL para insertar en la tabla usuarios
            $sqlUsuarios = "INSERT INTO usuarios (nombre_usuario, contrasena, rol) 
                            VALUES ('$nombreUsuario', '$contrasena', 'admin')";

            // Ejecutar la consulta para insertar en la tabla usuarios
            if ($conexion->query($sqlUsuarios) === TRUE) {
                // Obtener el ID del usuario recién insertado en la tabla usuarios
                $id_usuario_insertado = $conexion->insert_id;

                // Actualizar el campo usuario_id en la tabla administradores con el ID del usuario
                $sqlUpdate = "UPDATE administradores SET usuario_id = $id_usuario_insertado WHERE email = '$email'";

                // Ejecutar la consulta de actualización
                if ($conexion->query($sqlUpdate) === TRUE) {
                    // No se necesita mostrar un mensaje aquí, ya que ya se ha mostrado la alerta de éxito
                } else {
                    echo "Error al actualizar campo usuario_id: " . $conexion->error;
                }
            } else {
                // Error en la inserción en la tabla usuarios
                echo "Error al insertar datos de administrador en la tabla usuarios: " . $conexion->error;
            }
        } else {
            // Error en la inserción
            echo "Error al insertar administrador: " . $conexion->error;
        }
    }
}
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fecha_nacimiento'];
    $lugarNacimiento = $_POST['lugar_nacimiento'];
    $tipoDocumento = $_POST['tipo_documento'];
    $documentoIdentidad = $_POST['documento_identidad'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $eps = $_POST['eps'];
    $titulo = $_POST['titulo'];
    $email = $_POST['email'];

    // Llamar a la función insertarAdministrador para insertar los datos en la base de datos
    insertarAdministrador($nombre, $apellido, $fechaNacimiento, $lugarNacimiento, $tipoDocumento, $documentoIdentidad, $genero, $direccion, $telefono, $eps, $titulo, $email, $conexion);
}
?>
