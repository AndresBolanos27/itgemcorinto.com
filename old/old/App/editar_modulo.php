<?php require "sidebar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_GET['id'];
        $nombre_materia = $_POST["nombre_materia"];
        $docente_asignado = $_POST["docente_asignado"];
        $grupo_asignado = $_POST["grupo_asignado"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_finalizacion = $_POST["fecha_finalizacion"];
        $estado = $_POST["estado"];

        $sql = "UPDATE materias SET
        nombre_materia = '$nombre_materia',
        docente_asignado = '$docente_asignado',
        grupo_asignado = '$grupo_asignado',
        fecha_inicio = '$fecha_inicio',
        fecha_finalizacion = '$fecha_finalizacion',
        estado = '$estado'
        WHERE id = $id";

        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Los datos del modulo se han sido actualizados correctamente.');</script>";
            echo "<script>console.log('Redirigiendo a estudiantes.php'); window.location.href = 'modulos.php';</script>";
        } else {
            // Error en la actualización
            echo "Error al actualizar los datos: " . $conexion->error;
        }
    } else {
        echo "No se ha proporcionado un ID en el formulario.";
    }
}


$id = $_GET['id'];

$sql = "SELECT * FROM materias WHERE id = $id";
$resultado = $conexion->query($sql);

// Verificar si se encontraron resultados
if ($resultado->num_rows > 0) {
    // Obtener los datos del administrador
    $fila = $resultado->fetch_assoc();

    // Mostrar el formulario de edición con los campos prellenados
?>

<main>

<div style="background: none !important;" class="espacecustom  rounded ">
        <ul class="nav justify-content-end">
            <li class="nav-item">
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
                echo '<span style="font-size: 16px;" class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($rol_usuario) . '</span>';
                echo '<span style="font-size: 16px;" class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . " " . ucfirst($apellido_administrador) .  '</span>'; ?>

            </li>
        </ul>
    </div>

    <nav class="ms-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./modulos.php">Modulos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar modulos</li>
        </ol>
    </nav>

    <div  style="border-radius: 20px; background-color: white;" id="formulario1" class=" p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Editar de modulo:</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos del modulo</span>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
            <div class="row">
                <div class="row mt-2">
                    <!-- Primera Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="grupo">Nombre del Modulo</label>
                            <input value="<?php echo $fila['nombre_materia']; ?>" type="text" class="form-control" id="nombre_materia" name="nombre_materia" required>
                        </div>
                    </div>
                </div>
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Grupo</label>
                        <input value="<?php echo $fila['grupo_asignado']; ?>" type="text" class="form-control" id="grupo_asignado" name="grupo_asignado" required>
                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Docente</label>
                        <input value="<?php echo $fila['docente_asignado']; ?>" type="text" class="form-control" id="docente_asignado" name="docente_asignado" required>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Fecha de inicio</label>
                        <input value="<?php echo $fila['fecha_inicio']; ?>" type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                </div>
                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Fecha de Finalizacion</label>
                        <input value="<?php echo $fila['fecha_finalizacion']; ?>" type="date" class="form-control" id="fecha_finalizacion" name="fecha_finalizacion" required>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Estado</label>
                        <input value="<?php echo $fila['estado']; ?>" type="text" class="form-control" id="estado" name="estado" required>
                    </div>
                </div>
            </div>


            <div class="row mt-4">
                <!-- Quinta Columna (Botón de Enviar) -->
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Registrar Grupo</button>
                </div>
            </div>
        </form>
    </div>
    </main>

<?php
} else {
    echo "No se encontró el estudiante con el ID proporcionado.";
}
?>