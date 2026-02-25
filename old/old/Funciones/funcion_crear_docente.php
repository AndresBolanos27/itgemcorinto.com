<?php
// Incluir el archivo de conexión a la base de datos
include '../App/conexion.php';

function insertarDocente($nombres, $apellidos, $fecha_nacimiento, $lugar_nacimiento, $tipo_documento, $documento_identidad,$expedido_en, $genero, $direccion_residencia, $direccion_demografica, $n_celular, $eps, $perfil, $correo_electronico, $conexion)
{
    // Verificar si ya existe un docente con el mismo documento de identidad o correo
    $sql_verificacion = "SELECT * FROM docentes WHERE documento_identidad = '$documento_identidad' OR correo_electronico = '$correo_electronico'";
    $resultado_verificacion = $conexion->query($sql_verificacion);

    if ($resultado_verificacion->num_rows > 0) {
        // Si ya existe un docente con alguno de estos datos, mostrar un mensaje de error
        echo "<script>alert('Ya existe un docente con alguno de estos datos.'); window.location.href = '../App/docentes.php';</script>";
    } else {
        // Si no existe, proceder con la inserción
        $sql = "INSERT INTO docentes (nombres, apellidos, fecha_nacimiento, lugar_nacimiento, tipo_documento, documento_identidad, expedido_en, genero, direccion_residencia, direccion_demografica, n_celular, eps, perfil, correo_electronico) 
                VALUES ('$nombres', '$apellidos', '$fecha_nacimiento', '$lugar_nacimiento', '$tipo_documento', '$documento_identidad', '$expedido_en', '$genero', '$direccion_residencia', '$direccion_demografica', '$n_celular', '$eps', '$perfil', '$correo_electronico')";

        // Ejecutar la consulta y verificar si fue exitosa
        if ($conexion->query($sql) === TRUE) {
            // Inserción exitosa
            echo "<script>alert('Nuevo docente insertado correctamente.'); window.location.href = '../App/docentes.php';</script>";

            // Obtener el ID del usuario recién insertado en la tabla usuarios
            $id_usuario = $conexion->insert_id;

            // Insertar también en la tabla usuarios
            $nombreUsuario = $documento_identidad; // El nombre de usuario será el mismo que el documento de identidad
            $contrasena = password_hash($documento_identidad, PASSWORD_DEFAULT); // Hash de la contraseña inicial

            // Preparar la consulta SQL para insertar en la tabla usuarios
            $sqlUsuarios = "INSERT INTO usuarios (nombre_usuario, contrasena, rol) 
                            VALUES ('$nombreUsuario', '$contrasena', 'docente')";

            // Ejecutar la consulta para insertar en la tabla usuarios
            if ($conexion->query($sqlUsuarios) === TRUE) {
                // No es necesario hacer nada más aquí, ya que la inserción en la tabla usuarios fue exitosa

                $id_usuario_insertado = $conexion->insert_id;

                // Actualizar el campo usuario_id en la tabla docentes con el ID del usuario
                $sqlUpdate = "UPDATE docentes SET usuario_id = $id_usuario_insertado WHERE correo_electronico = '$correo_electronico'";
                // Ejecutar la consulta de actualización
                if ($conexion->query($sqlUpdate) === TRUE) {
                    // No se necesita mostrar un mensaje aquí, ya que ya se ha mostrado la alerta de éxito
                } else {
                    echo "Error al actualizar campo usuario_id: " . $conexion->error;
                }
            } else {
                // Error en la inserción en la tabla usuarios
                echo "Error al insertar datos de docente en la tabla usuarios: " . $conexion->error;
            }
        } else {
            // Error en la inserción
            echo "Error al insertar docente: " . $conexion->error;
        }
    }
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $lugar_nacimiento = $_POST['lugar_nacimiento'];
    $tipo_documento = $_POST['tipo_documento'];
    $documento_identidad = $_POST['documento_identidad'];
    $expedido_en = $_POST['expedido_en'];

    $genero = $_POST['genero'];
    $direccion_residencia = $_POST['direccion_residencia'];
    $direccion_demografica = $_POST['direccion_demografica'];
    $n_celular = $_POST['n_celular'];
    $eps = $_POST['eps'];
    $perfil = $_POST['perfil'];
    $correo_electronico = $_POST['correo_electronico'];

    // Llamar a la función insertarDocente para insertar los datos en la base de datos
    insertarDocente($nombres, $apellidos, $fecha_nacimiento, $lugar_nacimiento, $tipo_documento, $documento_identidad, $expedido_en, $genero, $direccion_residencia, $direccion_demografica, $n_celular, $eps, $perfil, $correo_electronico, $conexion);
}
