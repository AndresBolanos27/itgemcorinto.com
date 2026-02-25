<?php
include_once './App/conexion.php';
include_once './Funciones/contar.php';

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
                    <a target="_blank" href="./cartera/index.php">
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
    </main>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="./recursos/sidebar.js"></script>
</body>

</html>