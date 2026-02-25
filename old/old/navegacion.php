<?php
include_once './App/conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script después de redirigir
}

// Obtener el ID de usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];

// Consultar el nombre y apellido del administrador correspondiente al usuario actual
$sql = "SELECT administradores.nombre, administradores.apellido 
        FROM administradores 
        INNER JOIN usuarios ON administradores.usuario_id = usuarios.id 
        WHERE usuarios.id = $id_usuario";

$resultado = $conexion->query($sql);

// Verificar si se encontró el administrador correspondiente
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre_administrador = $fila['nombre'];
    $apellido_administrador = $fila['apellido'];
} else {
    $nombre_administrador = "Administrador no encontrado";
    $apellido_administrador = "";
}

// Verificar si el usuario tiene el rol de 'docente'
if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'estudiante' || $_SESSION['rol'] === 'admin') {
    // El usuario tiene el rol de 'docente', redirigirlo a otra página, como 'otra_pagina.php'
    header("Location: error.php");
    exit; // Detener la ejecución del script después de redirigir
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGEM</title>

    <link rel="stylesheet" href="./recursos/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./recursos/style.css">
    <link rel="shortcut icon" href="./recursos/img/logo-grande.svg" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">


</head>

<body>

    <div style="background: none !important; font-size: 20px;" class="espacecustom mt-4">

        <?php
        // Definir un array asociativo para mapear los roles a los colores de los badges
        $colores_roles = array(
            'admin' => 'danger',
            'docente' => 'primary',
            'estudiante' => 'success'
            // Agrega aquí más roles y colores si es necesario
        );

        // Obtener el rol del usuario actual desde la sesión
        $rol_usuario = $_SESSION['rol'];

        // Verificar si el rol del usuario está definido en el array de colores
        $badge_color = isset($colores_roles[$rol_usuario]) ? $colores_roles[$rol_usuario] : 'secondary';

        // Mostrar el rol del usuario en el badge
        echo '<span class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($rol_usuario) . '</span>';
        echo '<span class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . " " . ucfirst($apellido_administrador) .  '</span>';

        ?>
    </div>

    <div class="espacecustom mt-4 border">


        <!--  -->
        <nav class="navbar navbar-expand-lg" aria-label="Offcanvas navbar large">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="./recursos/img/logo-grande.svg" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
                    <span style="font-size: 24px; color: rgb(44, 44, 44);" class="ms-2 fw-bolder">Sistema
                        Académico</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar2" aria-labelledby="offcanvasNavbar2Label">
                    <div class="offcanvas-header">
                        <a class="navbar-brand d-flex align-items-center" href="#">
                            <img src="./recursos/img/logo-grande.svg" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
                            <span style="font-size: 24px; color: rgb(44, 44, 44);" class="ms-2 fw-bolder">Sistema
                                Académico</span>
                        </a>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link active fw-medium" aria-current="page" href="./index.php">Inicio</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link active fw-medium" aria-current="page" href="./cartera/index.php">Cartera</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="./App/docentes.php">Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./App/estudiantes.php">Estudiantes</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="./paginas/notas.php">Notas</a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="./paginas/academico.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Academico
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="./App/grupos.php">Grupos</a></li>
                                    <li><a class="dropdown-item" href="./App/modulos.php">Modulos</a></li>
                                </ul>
                            </li>
                        </ul>
                        <a href="./logout.php">
                            <button class="btn btn-dark" type="submit">Cerrar Sección</button>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!--  -->
    </div>
    <!--  -->


    <script src="./recursos/bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>