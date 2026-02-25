<?php
include_once './conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script después de redirigir
}

// Verificar si el usuario tiene el rol de 'docente'
// Verificar si el usuario tiene el rol de 'docente' o 'estudiante'
if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'estudiante') {
    // El usuario tiene el rol de 'docente' o 'estudiante', redirigirlo a otra página, como 'error.php'
    header("Location: ../error.php");
    exit; // Detener la ejecución del script después de redirigir
}
// El usuario ha iniciado sesión y no es 'docente', puedes continuar con el contenido de la página
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGEM</title>

    <link rel="stylesheet" href="../recursos/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../recursos/style.css">
    <link rel="shortcut icon" href="../recursos/img/logo-grande.svg" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">


</head>

<body>
  
    <div class="espacecustom mt-4 border">
        <!--  -->
        <nav class="navbar navbar-expand-lg" aria-label="Offcanvas navbar large">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="../index.php">
                    <img src="../recursos/img/logo-grande.svg" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
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
                                <a class="nav-link active fw-medium" aria-current="page" href="../index.php">Inicio</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link active fw-medium" aria-current="page" href="./cartera/index.php">Cartera</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="../App/docentes.php">Docentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../App/estudiantes.php">Estudiantes</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="./paginas/notas.php">Notas</a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="./paginas/academico.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Academico
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="./grupos.php">Grupos</a></li>
                                    <li><a class="dropdown-item" href="./modulos.php">Modulos</a></li>
                                   
                                </ul>
                            </li>
                        </ul>
                        <a href="../logout.php">
                            <button class="btn btn-dark" type="submit">Cerrar Sección</button>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <!--  -->
    </div>
    <!--  -->


    <script src="../recursos/bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>