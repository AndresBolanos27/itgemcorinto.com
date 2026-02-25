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
$sql = "SELECT estudiantes.nombre
        FROM estudiantes 
        INNER JOIN usuarios ON estudiantes.usuario_id = usuarios.id 
        WHERE usuarios.id = $id_usuario";

$resultado = $conexion->query($sql);

// Verificar si se encontró el administrador correspondiente
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre_administrador = $fila['nombre'];
} else {
    $nombre_administrador = "Administrador no encontrado";
}

// Consultar las materias del grupo del estudiante
$sql_materias_grupo = "SELECT m.id AS id_materia, m.nombre_materia
                       FROM materias m
                       INNER JOIN grupos g ON m.grupo_asignado = g.id
                       INNER JOIN estudiantes e ON g.id = e.grupo_id
                       WHERE e.usuario_id = $id_usuario";

$resultado_materias_grupo = $conexion->query($sql_materias_grupo);



// Verificar si el usuario tiene el rol de 'docente'
if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'root') {
    // El usuario tiene el rol de 'docente' o 'estudiante', redirigirlo a otra página, como 'error.php'
    header("Location: error.php");
    exit; // Detener la ejecución del script después de redirigir
}

// El usuario ha iniciado sesión y no es 'docente', puedes continuar con el contenido de la página
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGEM ESTUDIANTE</title>

    <link rel="stylesheet" href="./recursos/style.css">
    <link rel="shortcut icon" href="./recursos/img/logo-grande.svg" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="./recursos/full.min.css">
    <script src="./recursos/cdn.js"></script>

</head>

<body>



    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col ">
            <!-- Page content here -->
            <div class="hero min-h-screen" style="background-image: url(./recursos/img/img1.jpg);">
                <div class="hero-overlay bg-opacity-60"></div>
                <div class="hero-content text-center text-neutral-content">
                    <div class="max-w-2xl">
                        <div class="avatar mb-8 ">
                            <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                <img src="./recursos/img/logo-grande.svg" />
                            </div>
                        </div>
                        <h1 class="mb-5 text-5xl font-bold">ITGEM - ESTUDIANTE</h1>

                        <div style="background: none !important; font-size: 30px;" class="espacecustom mt-4">

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
                            // Mostrar el rol del usuario en el badge
                            echo '<span style="color: black; border: none;" class="p-4 ms-1 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . '</span>';
                            echo '<a onclick="my_modal_5.showModal()"><span style="border: none;" class="p-4 ms-1 badge bg-">Ver mi reporte final de notas</span></a>'; ?>
                        </div>


                    </div>

                </div>
            </div>
            <!-- Page content here -->
        </div>
        <div class=" drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>

            <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">


                <ul class="menu p-4 min-h-full bg-base-200 w-56 text-base-content rounded-box">

                    <div class="avatar mb-5 ">
                        <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="./recursos/img/logo-grande.svg    " />
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold mb-6">Itgem</h1>



                    <div class="badge p-4 text-lg <?= isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin" ? "badge-neutral" : "badge-accent"; ?> mb-5"><?= isset($_SESSION["rol"]) ? htmlspecialchars($_SESSION["rol"]) : "Rol no disponible"; ?></div>




                    <li class=" text-lg">
                        <a href="./estudiante.php">
                            <i class="fa-solid fa-house"></i>
                            Inicio
                        </a>
                    </li>
                    <li class=" text-lg">
                        <a onclick="my_modal_5.showModal()">
                            <i class="fa-solid fa-user-shield"></i>
                            Mis Notas
                        </a>


                    </li>
                </ul>


                <ul class="menu menu-md bg-base-200 w-56 rounded-box absolute bottom-0 mb-5">

                    <li class="mb-5">
                        <label class="flex cursor-pointer gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="5" />
                                <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
                            </svg>
                            <input type="checkbox" id="themeToggle" class="toggle theme-controller" />
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                            </svg>
                        </label>
                    </li>
                    <li>
                        <a href="./logout.php" style="font-size: 18px;">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Cerrar Sesión
                        </a>
                    </li>

                </ul>
            </ul>

        </div>
        <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden fixed top-0 right-0 mt-4 mr-4">
            <i class="fa-solid fa-bars"></i>
            Menu</label>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const htmlTag = document.querySelector('html');

            // Verifica si hay una preferencia de tema almacenada en localStorage
            const currentTheme = localStorage.getItem('theme');

            // Si hay una preferencia de tema almacenada, aplica ese tema al cargar la página
            if (currentTheme) {
                htmlTag.setAttribute('data-theme', currentTheme);
                if (currentTheme === 'forest') {
                    themeToggle.checked = true;
                }
            }

            // Agrega un event listener para detectar cambios en el toggle de tema
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    htmlTag.setAttribute('data-theme', 'forest');
                    localStorage.setItem('theme', 'forest'); // Almacena el tema seleccionado en localStorage
                } else {
                    htmlTag.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light'); // Almacena el tema seleccionado en localStorage
                }
            });
        });
    </script>

    <style>
        .fondo-verde {
            background-color: #c9ffd5 !important;
            /* Cambia el color de fondo a verde */
        }

        .fondo-amarillo {
            background-color: #fff59d !important;
            /* Cambia el color de fondo a amarillo */
        }

        .fondo-rojo {
            background-color: #ffb3b3 !important;
            /* Rojo */
        }

        /* Estilos específicos para el tema "forest" */
        [data-theme="forest"] .fondo-verde,
        [data-theme="forest"] .fondo-amarillo,
        [data-theme="forest"] .fondo-rojo {
            color: black !important;
        }
    </style>


    <!-- Open the modal using ID.showModal() method -->
    <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <div class="flex justify-between">
                <h3 class="font-bold text-lg">Hola! <?php

                                                    echo '<span style="color: black; border: none;" class="p-4 ms-1 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . '</span>'; ?></h3>

                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn">Cerrar</button>
                </form>
            </div>




            <div class="container mt-5">
                <h2>Tabla de Notas</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consultar y mostrar las notas de las materias del grupo del estudiante
                            $sql_notas_estudiante = "SELECT m.nombre_materia, n.nota
                                         FROM notas n
                                         INNER JOIN materias m ON n.id_materia = m.id
                                         INNER JOIN estudiantes e ON n.id_estudiante = e.id
                                         WHERE e.usuario_id = $id_usuario";

                            $resultado_notas_estudiante = $conexion->query($sql_notas_estudiante);

                            while ($row_nota_estudiante = $resultado_notas_estudiante->fetch_assoc()) {
                                $nota = $row_nota_estudiante['nota'];
                                $clase_css = '';

                                // Determinar la clase CSS según el rango de notas
                                if ($nota >= 4.5 && $nota <= 5) {
                                    $clase_css = 'fondo-verde';
                                } elseif ($nota >= 3.8 && $nota <= 4.4) {
                                    $clase_css = 'fondo-amarillo';
                                } elseif ($nota >= 1.0 && $nota <= 3.7) {
                                    $clase_css = 'fondo-rojo';
                                }

                                echo '<tr>';
                                echo '<td>' . $row_nota_estudiante['nombre_materia'] . '</td>';
                                echo '<td class="' . $clase_css . '">' . $nota . '</td>'; // Aplicar la clase CSS condicional
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </dialog>

</body>

</html>