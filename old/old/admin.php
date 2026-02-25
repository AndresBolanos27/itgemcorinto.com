<?php

include_once './App/conexion.php';

// Iniciar la sesión
session_start();

$id_usuario = $_SESSION['id_usuario'];


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script después de redirigir
}

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



// Verificar si el usuario tiene el rol de 'estudiante'
if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'estudiante' || $_SESSION['rol'] === 'root') {
    // El usuario tiene el rol de 'docente' o 'estudiante', redirigirlo a otra página, como 'error.php'
    header("Location: error.php");
    exit; // Detener la ejecución del script después de redirigir
}


// El usuario ha iniciado sesión y no es 'docente', puedes continuar con el contenido de la página
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGEM</title>

    <link rel="stylesheet" href="./recursos/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./recursos/style.css">
    <link rel="stylesheet" href="./recursos/sidebar.css">
    <link rel="shortcut icon" href="./recursos/img/logo-grande.svg" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



</head>

<body>
    <div class="menu">
        <ion-icon name="menu-outline"></ion-icon>
        <ion-icon name="close-outline"></ion-icon>
    </div>

    <div class="barra-lateral">
        <div class="mb-5 mt-4">
            <div class="nombre-pagina">
                <img src="./recursos/img/logo-grande.svg" width="100px" class="mt-4" alt="">
            </div>

        </div>

         <nav class="navegacion">
            <ul style="padding: 0px !important;">
                <li class="mb-3">
                    <a id="inbox" href="./index.php">
                        <i class="fa-solid fa-house ms-2"></i>
                        <span class="ms-2">Inicio</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./App/administradores.php">
                        <i class="fa-solid fa-user-shield ms-2"></i> <span class="ms-3">Personal</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./App/docentes.php">
                        <i class="fa-solid fa-user ms-2"></i>
                        <span class="ms-3">Docentes</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./App/estudiantes.php">
                        <i class="fa-solid fa-graduation-cap ms-2"></i>
                        <span class="ms-3">Estudiantes</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./App/grupos.php">
                        <i class="fa-solid fa-school-circle-check ms-2"></i>
                        <span class="ms-3">Grupos</span>
                    </a>
                </li>

                <li class="mb-3">
                    <a href="./App/modulos.php">
                        <i class="fa-solid fa-book ms-2"></i>
                        <span class="ms-3">Modulos</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./App/notas_admin.php">
                    <i class="fa-solid fa-notes-medical ms-2"></i>
                        <span class="ms-3">Notas</span>
                    </a>
                </li>
                <li class="mb-3">
                    <a href="./cartera/">
                    <i class="fa-solid fa-dollar-sign ms-2"></i>
                        <span class="ms-3">Cartera</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div>
            <div class="linea mb-4"></div>

            <hr>
            <nav class="navegacion mt-4">
                <ul style="padding: 0px !important;">
                    <li class="mt-3">
                        <a href="./logout.php">
                            <i class="fa-solid fa-right-from-bracket ms-2"></i>
                            <span class="ms-3">Cerrar Seccion</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

    <main>
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

    ?>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content page-content-custom"><br>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 d-flex ms-2 me-2 mt-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-4">ITGEM - CORINTO</h1>
                    <h2><?php echo '<span class="mb-2 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . " " . ucfirst($apellido_administrador) .  '</span>'; ?> </h2>
                    <p style="width: 85%; font-size: 18px;">Maximiza la eficiencia de tu gestión académica con nuestra plataforma. Simplifica la administración de Grupos, notas y datos de estudiantes de manera integrada y eficaz.</p>
                    <div>
                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-tag me-2 rounded-circle p-2" style="font-size: 28px;"></i>
                            <h5 class="fw-bold mb-0">Gestión academica.</h5>
                        </span>

                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-user me-2 rounded-circle p-2" style="font-size: 25px;"></i>
                            <h5 class="fw-bold mb-0">Gestión de estudiantes</h5>
                        </span>


                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-clipboard me-2 rounded-circle p-2" style="font-size: 25px;"></i>
                            <h5 class="fw-bold mb-0">Calificación de Notas</h5>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class=" mb-4 d-flex justify-content-center img-custom">

                    </div>
                </div>
            </div>


            <br>
            <div style="padding: 12px;">

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 d-flex">
                    <!-- Usuarios -->
                    <div class="col mb-3">
                        <div class="radius-10 custom-card p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #172a54;" class="fw-bold">Docentes</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-circle-user"></i>
                            </div>
                            <hr style="border-color: #1c2e4f 2px;">
                            <h5 style="color: #1c2e4f;">Total de Docentes:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosDocentes; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/docentes.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Docentes</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Usuarios -->

                    <!-- Usuarios -->
                    <div class="col mb-3">
                        <div class="radius-10 custom-card2 p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #1c2e4f;" class="fw-bold">Estudiantes</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-person"></i>
                            </div>
                            <hr>
                            <h5 style="color: #1c2e4f;">Total de Estudiantes:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosEstudiantes; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/estudiantes.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Estudiantes</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col mb-3">
                        <div class="radius-10 custom-card3 p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #1c2e4f;" class="fw-bold">Grupos</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-tag"></i>
                            </div>
                            <hr>
                            <h5 style="color: #1c2e4f;">Total de Grupos:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosGrupos; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/grupos.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Grupos</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>

                <footer class="espacecustom mb-4 border p-3">
                    <center>
                        <p class="mb-0">Santander Valley Col Copyright © 2024. All rights reserved.</p>
                    </center>

                </footer>
                <br><br>
</main>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="./recursos/sidebar.js"></script>
</body>

</html>