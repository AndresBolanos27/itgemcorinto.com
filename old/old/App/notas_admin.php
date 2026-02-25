<?php
include_once './conexion.php';
include_once './sidebar.php';
// Iniciar la sesión

$id_usuario = $_SESSION['id_usuario'];

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Detener la ejecución del script después de redirigir
}

// Consultar el nombre y apellido del administrador correspondiente al usuario actual
$sql = "SELECT nombre, apellido 
        FROM administradores 
        WHERE usuario_id = $id_usuario";

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
if ($_SESSION['rol'] === 'docente' || $_SESSION['rol'] === 'estudiante') {
    // El usuario tiene el rol de 'docente' o 'estudiante', redirigirlo a otra página, como 'error.php'
    header("Location: ../error.php");
    exit; // Detener la ejecución del script después de redirigir
}

// El usuario ha iniciado sesión y no es 'docente', puedes continuar con el contenido de la página
?>


<main>
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
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg" aria-label="Offcanvas navbar large">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
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
                            <img src="../recursos/img/logo-grande.svg" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
                            <span style="font-size: 24px; color: rgb(44, 44, 44);" class="ms-2 fw-bolder">Sistema
                                Académico</span>
                        </a>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                       
                       
                    </div>
                </div>
            </div>
        </nav>
        <!-- Fin Navbar -->
    </div>

    <div class="espacecustom mt-4 border">
        <div class="table-responsive mt-4">
            <div class="container mt-4">
                <form method="POST">
                    <div class="mb-3">
                        <label for="grupo" class="form-label">Seleccione un grupo:</label>
                        <select class="form-select" name="grupo" id="grupo">
                            <option value="">Todos los grupos</option>
                            <?php
                            // Consultar todos los grupos disponibles
                            $sql_grupos = "SELECT id, nombre_grupo, seccion FROM grupos";
                            $resultado_grupos = $conexion->query($sql_grupos);

                            // Mostrar opciones de selección para cada grupo
                            while ($row_grupo = $resultado_grupos->fetch_assoc()) {
                                echo '<option value="' . $row_grupo['id'] . '">' . $row_grupo['nombre_grupo'] . ' ' . $row_grupo['seccion'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>

            <?php
            // Verificar si se envió el formulario con el filtro de grupo
            if (isset($_POST['grupo']) && $_POST['grupo'] !== '') {
                // Obtener el ID del grupo seleccionado
                $grupo_seleccionado = $_POST['grupo'];
                // Consultar el nombre del grupo seleccionado
                $sql_nombre_grupo = "SELECT nombre_grupo, seccion FROM grupos WHERE id = $grupo_seleccionado";
                $resultado_nombre_grupo = $conexion->query($sql_nombre_grupo);
                $fila_nombre_grupo = $resultado_nombre_grupo->fetch_assoc();
                $nombre_grupo = $fila_nombre_grupo['nombre_grupo'];
                $seccion_grupo = $fila_nombre_grupo['seccion'];

                echo "<h3 class='mt-4 mb-4 ms-2'>Grupo Seleccionado: $nombre_grupo $seccion_grupo</h3>";
            ?>
                <div class="table-responsive mt-4">
                    <?php
                    // Construir la consulta SQL base para obtener las notas de los estudiantes
                    $sql_estudiantes_materias_notas = "SELECT e.nombre AS nombre_estudiante, m.nombre_materia AS materia,
                                        IFNULL(n.nota, 'Sin nota') AS nota
                                        FROM estudiantes e
                                        JOIN grupos g ON e.grupo_id = g.id
                                        JOIN materias m ON m.grupo_asignado = g.id
                                        LEFT JOIN notas n ON e.id = n.id_estudiante AND m.id = n.id_materia";

                    // Agregar una condición a la consulta para filtrar por el grupo seleccionado
                    $sql_estudiantes_materias_notas .= " WHERE g.id = $grupo_seleccionado";

                    // Ordenar por nombre de estudiante y materia
                    $sql_estudiantes_materias_notas .= " ORDER BY e.nombre, m.nombre_materia";

                    // Ejecutar la consulta SQL
                    $resultado_estudiantes_materias_notas = $conexion->query($sql_estudiantes_materias_notas);

                    // Verificar si se encontraron resultados
                    if ($resultado_estudiantes_materias_notas->num_rows > 0) {
                        $current_student = "";
                        // Iterar sobre los resultados y mostrar los datos
                        while ($row = $resultado_estudiantes_materias_notas->fetch_assoc()) {
                            // Verificar si es un nuevo estudiante
                            if ($current_student != $row["nombre_estudiante"]) {
                                // Cerrar la tabla anterior si ya se ha creado
                                if ($current_student != "") {
                                    echo "</tbody></table></div>";
                                }
                                // Crear una nueva tabla para el estudiante
                                echo "<div class='mt-4'>";
                                echo "<h5 class='fw-bold'>Nombre del Estudiante: " . $row["nombre_estudiante"] . "</h5>";
                                echo "<table class='table table-striped table-bordered'>";
                                echo "<thead><tr><th class='text-center'>Materia</th><th class='text-center'>Nota</th></tr></thead>";
                                echo "<tbody>";
                                $current_student = $row["nombre_estudiante"];
                            }
                            // Mostrar materia y nota del estudiante actual
                            echo "<tr><td>" . $row["materia"] . "</td><td>" . $row["nota"] . "</td></tr>";
                        }
                        // Cerrar la última tabla
                        echo "</tbody></table></div>";
                    } else {
                        // Mostrar mensaje si no se encontraron datos
                        echo "<p class='text-center'>No se encontraron datos.</p>";
                    }
                    ?>
                </div>
            <?php
            } else {
                // Mostrar mensaje si no se ha seleccionado ningún grupo
                echo "<p class='text-center'>Seleccione un grupo para ver las materias.</p>";
            }
            ?>
        </div>
    </div>
</main>