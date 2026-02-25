<?php
// Incluir el archivo de conexión a la base de datos
include '../App/conexion.php';

// Iniciar la sesión
session_start();

// Variable para almacenar el mensaje de error
$error_message = "";

// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario y la contraseña enviados desde el formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para seleccionar el usuario con el nombre de usuario proporcionado
    $sql = "SELECT id, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Bind de los parámetros
    $stmt->bind_param("s", $nombre_usuario);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado de la consulta
    $resultado = $stmt->get_result();

    // Verificar si se encontró un usuario con el nombre de usuario proporcionado
    if ($resultado->num_rows == 1) {
        // Obtener los datos del usuario de la consulta
        $fila = $resultado->fetch_assoc();
        $id_usuario = $fila['id'];
        $nombre_usuario_bd = $fila['nombre_usuario'];
        $contrasena_bd = $fila['contrasena'];

        // Verificar si la contraseña ingresada coincide con el hash almacenado en la base de datos
        if (password_verify($contrasena, $contrasena_bd)) {
            // La contraseña es correcta, iniciar sesión
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre_usuario'] = $nombre_usuario_bd;

            // Consultar el rol del usuario en la base de datos
            $sql_rol = "SELECT rol FROM usuarios WHERE id = ?";

            // Preparar la consulta
            $stmt_rol = $conexion->prepare($sql_rol);

            // Bind de los parámetros
            $stmt_rol->bind_param("i", $id_usuario);

            // Ejecutar la consulta
            $stmt_rol->execute();

            // Obtener el resultado de la consulta
            $resultado_rol = $stmt_rol->get_result();

            // Obtener el rol del usuario
            $fila_rol = $resultado_rol->fetch_assoc();
            $rol_usuario = $fila_rol['rol'];

            // Establecer el rol del usuario en la variable de sesión
            $_SESSION['rol'] = $rol_usuario;

            // Redirigir según el rol del usuario
            if ($rol_usuario === 'docente') {
                // Redirigir al usuario a la página de docentes
                header("Location: ../docentes.php");
            } else if ($rol_usuario === 'admin') {
                // Redirigir al usuario a la página de inicio (index.php)
                header("Location: ../admin.php");
            } else if ($rol_usuario === 'estudiante') {
                // Redirigir al usuario a la página de estudiantes
                header("Location: ../estudiante.php");
            } else if ($rol_usuario === 'root') {
                // Redirigir al usuario a la página de estudiantes
                header("Location: ../index.php");
            } else {
                // Redirigir al usuario a otra página, como index.php
                header("Location: ../otra_pagina.php");
            }
        } else {
            // La contraseña es incorrecta, mostrar un mensaje de error
            echo '<script>alert("Contraseña incorrecta"); window.location.href = "../login.php";</script>';
        }
    } else {
        // No se encontró ningún usuario con el nombre de usuario proporcionado, mostrar un mensaje de error
        echo '<script>alert("Nombre de usuario incorrecto"); window.location.href = "../login.php";</script>';
    }

    // Cerrar las consultas preparadas
    $stmt->close();
    $stmt_rol->close();
}
