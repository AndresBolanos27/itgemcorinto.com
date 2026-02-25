<?php
include_once './conexion.php';

// Iniciar la sesión
session_start();

$id_usuario = $_SESSION['id_usuario'];


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script después de redirigir
}


// Obtener el ID del docente asociado al usuario actual
$sql = "SELECT id FROM docentes WHERE usuario_id = $id_usuario";

// Ejecutar la consulta
$resultado = $conexion->query($sql);

// Verificar si se encontró el docente asociado
if ($resultado->num_rows > 0) {
    // Obtener el ID del docente
    $fila = $resultado->fetch_assoc();
    $id_docente = $fila['id'];

    // Consultar el nombre del docente
    $sql_nombre_docente = "SELECT nombres, apellidos FROM docentes WHERE id = $id_docente";

    // Ejecutar la consulta
    $resultado_nombre_docente = $conexion->query($sql_nombre_docente);

    // Verificar si se encontró el nombre del docente
    if ($resultado_nombre_docente->num_rows > 0) {
        // Obtener el nombre del docente
        $fila_nombre_docente = $resultado_nombre_docente->fetch_assoc();
        $nombre_docente = $fila_nombre_docente['nombres'] . " " . $fila_nombre_docente['apellidos'];

        // Mostrar el nombre del docente por consola
    } else {
        echo "No se encontró el nombre del docente.";
    }
} else {
    echo "No se encontró el docente asociado al usuario actual.";
}

// Verificar si se ha enviado un formulario con el grupo seleccionado
if (isset($_POST['grupo_materias'])) {
    $id_grupo_seleccionado = $_POST['grupo_materias'];

    // Consulta SQL para obtener las materias del grupo seleccionado que imparte el docente
    $sql_materias_grupo = "SELECT m.nombre_materia
                           FROM materias m
                           INNER JOIN grupos g ON m.grupo_asignado = g.id
                           WHERE m.docente_asignado IN (
                               SELECT id
                               FROM docentes
                               WHERE usuario_id = $id_usuario
                           )
                           AND g.id = $id_grupo_seleccionado";

    // Ejecutar la consulta para obtener las materias del grupo
    $resultado_materias_grupo = $conexion->query($sql_materias_grupo);
}

// Consultar el nombre y apellido del administrador correspondiente al usuario actual
$sql = "SELECT docentes.nombres, docentes.apellidos 
        FROM docentes 
        INNER JOIN usuarios ON docentes.usuario_id = usuarios.id 
        WHERE usuarios.id = $id_usuario";

$resultado = $conexion->query($sql);

// Verificar si se encontró el administrador correspondiente
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre_administrador = $fila['nombres'];
    $apellido_administrador = $fila['apellidos'];
} else {
    $nombre_administrador = "Administrador no encontrado";
    $apellido_administrador = "";
}


// Verificar si el usuario tiene el rol de 'estudiante'
if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'estudiante' || $_SESSION['rol'] === 'root') {
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
    <title>DOCENTE</title>

    <link rel="stylesheet" href="../recursos/style.css">
    <link rel="shortcut icon" href="./recursos/img/logo-grande.svg" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="../recursos/full.min.css">
    <script src="../recursos/cdn.js"></script>
</head>

</head>

<body>




    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col ">
            <!-- Page content here -->
            <div style="background-color: white;" class=" min-h-screen">




                <?php
                $colores_roles = array(
                    'admin' => 'danger',
                    'docente' => '',
                    'estudiante' => 'success'
                );

                $rol_usuario = $_SESSION['rol'];

                $badge_color = isset($colores_roles[$rol_usuario]) ? $colores_roles[$rol_usuario] : 'secondary';


                ?>

                <!-- NOTAS -->
                <?php
                // Verificar si el formulario ha sido enviado
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Obtener las notas ingresadas y subirlas a la base de datos
                    foreach ($_POST as $key => $value) {
                        // Verificar si el campo es una nota (tiene el formato nota_<id_estudiante>_<id_materia>)
                        if (substr($key, 0, 5) == "nota_") {
                            // Obtener el ID del estudiante y de la materia desde el nombre del campo
                            $parts = explode("_", $key);
                            $id_estudiante = $parts[1];
                            $id_materia = $parts[2];
                            $nota = $value;

                            // Aquí deberías escribir el código para insertar la nota en la base de datos
                            // Puedes utilizar la conexión a la base de datos que ya tienes establecida
                            // Por ejemplo:
                            $sql_insertar_nota = "INSERT INTO notas (id_estudiante, id_materia, nota) VALUES ($id_estudiante, $id_materia, $nota)";
                            $conexion->query($sql_insertar_nota);
                            // Esto es solo un ejemplo, asegúrate de usar consultas preparadas para prevenir inyección SQL

                        }
                    }
                }
                ?>



                <div class="container p-5 mt-20">
                    <form action="#" method="POST" onsubmit="return validarSeleccionGrupo()">
                        <div class="flex gap-3">
                            <div class="col mb-2">
                                <select class="select select-bordered w-full max-w-xs" name="grupo_materias" id="grupo_materias">
                                    <option value="">Seleccionar Grupo...</option>
                                    <?php
                                    // Consulta SQL para obtener los grupos que el docente tiene materias asignadas
                                    $sql_grupos = "SELECT DISTINCT g.id AS id_grupo, CONCAT(g.nombre_grupo, ' ', g.seccion) AS nombre_completo
                       FROM grupos g
                       INNER JOIN materias m ON g.id = m.grupo_asignado
                       INNER JOIN docentes d ON m.docente_asignado = d.id
                       WHERE d.usuario_id = $id_usuario"; // Filtrar por el ID de usuario del docente
                                    $resultado_grupos = $conexion->query($sql_grupos);

                                    // Iterar sobre los resultados de la consulta de grupos
                                    while ($row_grupo = $resultado_grupos->fetch_assoc()) {
                                        echo '<option value="' . $row_grupo['id_grupo'] . '">' . $row_grupo['nombre_completo'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col mb-2">
                                <button type="submit" class="btn btn-primary">Mostrar Materias</button>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="container p-5">
                    <?php
                    // Verificar si se ha enviado un formulario con el grupo seleccionado
                    if (isset($_POST['grupo_materias'])) {
                        // Obtener el ID del grupo seleccionado
                        $id_grupo_seleccionado = $_POST['grupo_materias'];
                        // Consulta SQL para obtener las materias del grupo seleccionado
                        $sql_materias_grupo = "SELECT m.id AS id_materia, m.nombre_materia
                       FROM materias m
                       INNER JOIN grupos g ON m.grupo_asignado = g.id
                       INNER JOIN docentes d ON m.docente_asignado = d.id
                       WHERE g.id = $id_grupo_seleccionado
                       AND d.id = $id_docente";


                        // Ejecutar la consulta solo si se ha seleccionado un grupo
                        if ($id_grupo_seleccionado != "") {
                            $resultado_materias_grupo = $conexion->query($sql_materias_grupo);
                            // Verificar si se encontraron materias para el grupo seleccionado
                            if ($resultado_materias_grupo->num_rows > 0) {
                                // Verificar si se ha enviado un formulario con el grupo seleccionado
                                if (isset($_POST['grupo_materias'])) {
                                    // Obtener el ID del grupo seleccionado
                                    $id_grupo_seleccionado = $_POST['grupo_materias'];
                                    // Consulta SQL para obtener el nombre del grupo seleccionado
                                    $sql_nombre_grupo = "SELECT CONCAT(nombre_grupo, ' ', seccion) AS nombre_completo FROM grupos WHERE id = $id_grupo_seleccionado";
                                    $resultado_nombre_grupo = $conexion->query($sql_nombre_grupo);
                                    // Obtener el nombre completo del grupo
                                    if ($row_nombre_grupo = $resultado_nombre_grupo->fetch_assoc()) {
                                        $nombre_grupo_seleccionado = $row_nombre_grupo['nombre_completo'];
                                    } else {
                                        $nombre_grupo_seleccionado = "Grupo no encontrado";
                                    }
                                    echo '<span style="font-size: 16px"; class="ms-1 mt-3 mb-5 p-5 fw-normal badge bg-' . $badge_color . '">' . ucfirst($nombre_grupo_seleccionado) . '</span>';
                                }
                    ?><form action="../Funciones/actualizar_notas.php" method="POST">
                                    <div class="overflow-x-auto">
                                        <table class="table table-xs">
                                            <thead>
                                                <tr>
                                                    <th class="sticky left-0 bg-base-200">Nombre del Estudiante</th>
                                                    <?php
                                                    // Consulta SQL para obtener la lista de estudiantes del grupo seleccionado, ordenados alfabéticamente por nombre
                                                    $sql_estudiantes_grupo = "SELECT * FROM estudiantes WHERE grupo_id = $id_grupo_seleccionado ORDER BY nombre";
                                                    $resultado_estudiantes_grupo = $conexion->query($sql_estudiantes_grupo);

                                                    // Iterar sobre los resultados de la consulta de materias del grupo para mostrar los encabezados de las notas
                                                    $resultado_materias_grupo->data_seek(0); // Reiniciar el puntero del resultado de la consulta de materias
                                                    while ($row_materia = $resultado_materias_grupo->fetch_assoc()) {
                                                        echo '<th>' . $row_materia['nombre_materia'] . '</th>';
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Iterar sobre los resultados de la consulta de estudiantes del grupo seleccionado
                                                while ($row_estudiante = $resultado_estudiantes_grupo->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td class="sticky left-0 bg-base-100">' . $row_estudiante['nombre'] . '</td>';

                                                    // Iterar sobre los resultados de la consulta de materias del grupo para mostrar los campos de notas
                                                    $resultado_materias_grupo->data_seek(0); // Reiniciar el puntero del resultado de la consulta de materias
                                                    while ($row_materia = $resultado_materias_grupo->fetch_assoc()) {
                                                        $id_estudiante = $row_estudiante['id'];
                                                        $id_materia = $row_materia['id_materia'];

                                                        // Consultar la nota correspondiente en la base de datos
                                                        $sql_nota = "SELECT nota FROM notas WHERE id_estudiante = $id_estudiante AND id_materia = $id_materia";
                                                        $resultado_nota = $conexion->query($sql_nota);
                                                        $row_nota = $resultado_nota->fetch_assoc();
                                                        $nota = isset($row_nota['nota']) ? $row_nota['nota'] : '0';

                                                        // Mostrar el campo de nota con el valor correspondiente
                                                        echo '<td><input min="0" max="5" step="0.1" class="input input-bordered w-16" type="number" name="nota_' . $id_estudiante . '_' . $id_materia . '" value="' . $nota . '"></td>';
                                                    }

                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <button type="submit" class="mt-4 btn btn-primary" onclick="mostrarAlerta()">Subir Notas</button>
                                </form>
                    <?php
                            } else {
                                // No se encontraron materias para el grupo seleccionado
                                echo '<p>No se encontraron materias para este grupo.</p>';
                            }
                        } else {
                            // Mensaje para indicar que se debe seleccionar un grupo
                            echo '<p id="mensaje-seleccion">Por favor, selecciona un grupo.</p>';
                        }
                    }
                    ?>
                </div>
                <script>
                    function validarSeleccionGrupo() {
                        var grupoSeleccionado = document.getElementById('grupo_materias').value;
                        if (grupoSeleccionado === "") {
                            alert("Por favor, selecciona un grupo primero.");
                            return false;
                        }
                        return true;
                    }
                </script>



                <script>
                    // Función para mostrar la alerta solo cuando se hace clic en el botón de "Subir Notas"
                    function mostrarAlerta() {
                        alert("¡Notas actualizadas exitosamente!");
                    }
                </script>



                <!-- NOTAS -->

            </div>
            <!-- Page content here -->
        </div>
        <div class=" drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>

            <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">


                <ul class="menu p-4 min-h-full bg-base-200 w-56 text-base-content rounded-box">

                    <div class="avatar mb-5 ">
                        <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="../recursos/img/logo-grande.svg    " />
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold mb-6">Itgem</h1>



                    <div class="badge p-4 text-lg <?= isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin" ? "badge-neutral" : "badge-accent"; ?> mb-5"><?= isset($_SESSION["rol"]) ? htmlspecialchars($_SESSION["rol"]) : "Rol no disponible"; ?></div>




                    <li class=" text-lg">
                        <a href="../docentes.php">
                            <i class="fa-solid fa-house"></i>
                            Inicio
                        </a>
                    </li>
                    <li class=" text-lg">
                        <a href="#">
                            <i class="fa-solid fa-user-shield"></i>
                            Calificar
                        </a>


                    </li>
                </ul>


                <ul class="menu menu-md bg-base-200 w-56 rounded-box absolute bottom-0 mb-5">
                    <!-- 
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
                    </li> -->
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
                    htmlTag.setAttribute('data-theme', 'fantasy');
                    localStorage.setItem('theme', 'fantasy'); // Almacena el tema seleccionado en localStorage
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




</body>

</html>