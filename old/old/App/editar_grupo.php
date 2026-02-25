<?php require "sidebar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_GET['id'];
        $nombre_grupo = $_POST["nombre_grupo"];
        $ano_lectivo = $_POST["ano_lectivo"];
        $nivel_educativo = $_POST["nivel_educativo"];
        $seccion = $_POST["seccion"];
        $horario = $_POST["horario"];
        $aula_asignada = $_POST["aula_asignada"];

        $sql = "UPDATE grupos SET
        nombre_grupo = '$nombre_grupo',
        ano_lectivo = '$ano_lectivo',
        nivel_educativo = '$nivel_educativo',
        seccion = '$seccion',
        horario = '$horario',
        aula_asignada = '$aula_asignada'
        WHERE id = $id";


        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Los datos del grupo se han sido actualizados correctamente.');</script>";
            echo "<script>console.log('Redirigiendo a estudiantes.php'); window.location.href = 'grupos.php';</script>";
        } else {
            // Error en la actualización
            echo "Error al actualizar los datos: " . $conexion->error;
        }
    } else {
        echo "No se ha proporcionado un ID en el formulario.";
    }
}

$id = $_GET['id'];

$sql = "SELECT * FROM grupos WHERE id = $id";
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
                <li class="breadcrumb-item"><a href="./grupos.php">Estudiantes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar estudiante</li>
            </ol>
        </nav>
        <div style="border-radius: 20px; background-color: white;" id="formulario1" class=" p-4 border p-custom mt-5">
            <h2 class="fw-bolder ms-2">Editar de grupo:</h2>
            <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos del grupo</span>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

                <div class="row">
                    <!-- Primera Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="nombre_grupo">Nombre de Grupo</label>
                            <input value="<?php echo $fila['nombre_grupo']; ?>" type="text" class="form-control" id="nombre_grupo" name="nombre_grupo" required>
                        </div>
                    </div>

                    <!-- Segunda Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="ano_lectivo">Año lectivo</label>
                            <input value="<?php echo $fila['ano_lectivo']; ?>" type="text" class="form-control" id="ano_lectivo" name="ano_lectivo" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <!-- Tercera Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="nivel_educativo">Nivel educativo</label>
                            <input value="<?php echo $fila['nivel_educativo']; ?>" type="text" class="form-control" id="nivel_educativo" name="nivel_educativo" required>
                        </div>
                    </div>

                    <!-- Cuarta Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="seccion">Grupo</label>
                            <input value="<?php echo $fila['seccion']; ?>" type="text" class="form-control" id="seccion" name="seccion" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <!-- Quinta Columna -->
                   <div class="col-md-6">
    <div class="input-group mt-4">
        <label class="input-group-text" for="horario">Horario</label>
        <select class="form-select" id="horario" name="horario" required>
            <option value="">Seleccionar horario</option>
            <option value="Viernes 8-1 Pm">Viernes 8-1 Pm</option>
            <option value="Viernes 1-5 Pm">Viernes 1-5 Pm</option>
            <option value="Sabados 8-1 Pm">Sábados 8-1 Pm</option>
            <option value="Sabados 1-5 Pm">Sábados 1-5 Pm</option>
            <option value="Domingos 8-1 Pm">Domingos 8-1 Pm</option>
            <option value="Domingos 1-5 Pm">Domingos 1-5 Pm</option>
            <option value="Sabados">Sábados</option>
        </select>
    </div>
</div>


                    <!-- Sexta Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="aula_asignada">Salon Asignado</label>
                            <input value="<?php echo $fila['aula_asignada']; ?>" type="text" class="form-control" id="aula_asignada" name="aula_asignada">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Botón de Enviar -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Actualizar Grupo</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <br>
    <br>

<?php
} else {
    echo "No se encontró el estudiante con el ID proporcionado.";
}
?>