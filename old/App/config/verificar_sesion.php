<?php
session_start();

function verificar_sesion()
{
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login");
        exit();
    }

    // Mostrar el nombre, apellido y rol del usuario
    // echo "Usuario: " . $_SESSION['usuario_nombre'] . " " . $_SESSION['usuario_apellido'] . " - Rol: " . $_SESSION['usuario_rol'];
}
    