<?php require "sidebar.php";



// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechaNacimiento = $_POST['fecha_nacimiento'];
    $lugarNacimiento = $_POST['lugar_nacimiento'];
    $documentoIdentidad = $_POST['documento_identidad'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $eps = $_POST['eps'];
    $titulo = $_POST['titulo'];
    $email = $_POST['email'];

    // Obtener el valor anterior del documento_identidad
    $sql_get_old_doc_ident = "SELECT documento_identidad FROM administradores WHERE id = $id";
    $old_doc_ident_result = $conexion->query($sql_get_old_doc_ident);
    $old_doc_ident_row = $old_doc_ident_result->fetch_assoc();
    $old_doc_ident = $old_doc_ident_row['documento_identidad'];

    // Preparar la consulta SQL para actualizar los datos del administrador
    $sql = "UPDATE administradores SET 
                nombre = '$nombre', 
                apellido = '$apellido', 
                fecha_nacimiento = '$fechaNacimiento', 
                lugar_nacimiento = '$lugarNacimiento', 
                documento_identidad = '$documentoIdentidad', 
                direccion = '$direccion', 
                telefono = '$telefono', 
                eps = '$eps', 
                titulo = '$titulo', 
                email = '$email'
            WHERE id = $id";

    // Ejecutar la consulta de actualización
    if ($conexion->query($sql) === TRUE) {
        // Actualización exitosa
        echo "<script>alert('Los datos del administrador han sido actualizados correctamente.');</script>";
        echo "<script>window.location.href = 'administradores.php';</script>";
        // Actualizar también el nombre de usuario en la tabla usuarios
        $sql_update_usuario = "UPDATE usuarios SET nombre_usuario = '$documentoIdentidad' WHERE nombre_usuario = '$old_doc_ident'";

        // Ejecutar la consulta de actualización del nombre de usuario
        if ($conexion->query($sql_update_usuario) === TRUE) {
            // Actualización exitosa del nombre de usuario en la tabla usuarios
            // echo "El nombre de usuario ha sido actualizado correctamente en la tabla usuarios.";
        } else {
            // Error en la actualización del nombre de usuario
            echo "Error al actualizar el nombre de usuario en la tabla usuarios: " . $conexion->error;
        }
    } else {
        // Error en la actualización
        echo "Error al actualizar los datos del administrador: " . $conexion->error;
    }
}

// Obtener el ID del administrador a editar desde el parámetro GET
$id = $_GET['id'];

// Consulta SQL para obtener los datos del administrador con el ID proporcionado
$sql = "SELECT * FROM administradores WHERE id = $id";
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
            <li class="breadcrumb-item"><a href="./administradores.php">Administradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar administrador</li>
        </ol>
    </nav>
    <div style="border-radius: 20px; background-color: white;" class=" p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Editar Registro de administradores:</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos personales</span>
        <form action="" method="POST">
            <!-- Campo oculto para enviar el ID del administrador -->
            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
            <!-- Grupo 1 -->
            <div class="row mb-3 mt-3">
                <div class="col-md-6 mt-2">
                    <!-- Nombre -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input autocomplete="off" id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre:" aria-label="Username" aria-describedby="addon-wrapping" required maxlength="25" value="<?php echo $fila['nombre']; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <!-- Apellido -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input autocomplete="off" id="apellido" name="apellido" type="text" class="form-control" placeholder="Apellido:" aria-label="Username" aria-describedby="addon-wrapping" required maxlength="25" value="<?php echo $fila['apellido']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 1 -->

            <!-- Grupo 2 -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <div class="input-group flex-nowrap mt-2">
                            <i style="font-size: 27px;" class="fa-solid fa-calendar-days input-group-text"></i>
                            <input id="fecha_nacimiento" name="fecha_nacimiento" id="apellido" name="apellido" type="date" class="form-control" aria-describedby="addon-wrapping" value="<?php echo $fila['fecha_nacimiento']; ?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lugar_nacimiento">Lugar de nacimiento:</label>
                        <div class="input-group flex-nowrap mt-2">
                            <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                            <input autocomplete="of" id="lugar_nacimiento" name="lugar_nacimiento" type="text" class="form-control" placeholder="Lugar de nacimiento:" aria-label="Username" aria-describedby="addon-wrapping" maxlength="40" value="<?php echo $fila['lugar_nacimiento']; ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 2 -->

            <!-- Grupo 3 -->
            <div class="row">


                <div class="col-md-6 mt-2">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                        <input autocomplete="of" placeholder="Numero de documento" id="documento_identidad" name="documento_identidad" type="number" class="form-control" aria-describedby="addon-wrapping" required pattern="\d{1,10}" value="<?php echo $fila['documento_identidad']; ?>">

                    </div>
                </div>
            </div>
            <!-- Grupo 3 -->

            <!-- Grupo 5 -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-map-location-dot input-group-text"></i>
                        <input autocomplete="of" id="direccion" name="direccion" type="text" class="form-control" placeholder="Direccion:" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $fila['direccion']; ?>" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                        <input autocomplete="of" id="telefono" name="telefono" type="text" class="form-control" placeholder="Telefono:" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $fila['telefono']; ?>" required>
                    </div>

                </div>
            </div>
            <!-- Grupo 5 -->

            <span style="font-size: 16px;" class="badge text-bg-secondary ms-2 mt-4 mb-4">Otros datos</span>
            <!-- Grupo 6 -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <i style="font-size: 27px;" class="fa-solid fa-venus-mars input-group-text"></i>
                        <select class="form-control" id="eps" name="eps" required>
                            <option value="">Selecciona una EPS</option>
                            <option value="ALIANSALUD ENTIDAD PROMOTORA DE SALUD S.A.">ALIANSALUD ENTIDAD PROMOTORA DE SALUD S.A.</option>
                            <option value="ASOCIACIÓN INDÍGENA DEL CAUCA">ASOCIACIÓN INDÍGENA DEL CAUCA</option>
                            <option value="COMFENALCO VALLE E.P.S.">COMFENALCO VALLE E.P.S.</option>
                            <option value="COMPENSAR E.P.S.">COMPENSAR E.P.S.</option>
                            <option value="E.P.S. FAMISANAR LTDA.">E.P.S. FAMISANAR LTDA.</option>
                            <option value="E.P.S. SANITAS S.A.">E.P.S. SANITAS S.A.</option>
                            <option value="EPS SERVICIO OCCIDENTAL DE SALUD S.A.">EPS SERVICIO OCCIDENTAL DE SALUD S.A.</option>
                            <option value="EPS Y MEDICINA PREPAGADA SURAMERICANA S.A">EPS Y MEDICINA PREPAGADA SURAMERICANA S.A</option>
                            <option value="MALLAMAS">MALLAMAS</option>
                            <option value="FUNDACIÓN SALUD MIA EPS">FUNDACIÓN SALUD MIA EPS</option>
                            <option value="NUEVA EPS S.A.">NUEVA EPS S.A.</option>
                            <option value="SALUD TOTAL S.A. E.P.S.">SALUD TOTAL S.A. E.P.S.</option>
                            <option value="SALUDVIDA S.A .E.P.S">SALUDVIDA S.A .E.P.S</option>
                            <option value="SAVIA SALUD EPS">SAVIA SALUD EPS</option>
                            <option value="ASMET SALUD EPS">ASMET SALUD EPS</option>

                            <!-- Add more options as needed -->
                        </select>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <div class="col-md-6">
                            <div class="input-group flex-nowrap">
                                <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                                <input autocomplete="of" id="titulo" name="titulo" type="text" class="form-control" placeholder="titulo:" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $fila['titulo']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Agrega más campos aquí si es necesario -->
            </div>

            <!-- Grupo 6 -->

            <!-- Gruo 7 -->
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <div class="col-md-6">
                            <div class="input-group flex-nowrap">
                                <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                                <input autocomplete="of" id="email" name="email" type="email" class="form-control" placeholder="email:" aria-label="email" aria-describedby="addon-wrapping" value="<?php echo $fila['email']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 7 -->

            <!-- Continuar con los otros campos del formulario -->
            <!-- Grupo 2, Grupo 3, etc. -->

            <!-- Botón para enviar el formulario -->
            <button style="background-color: #2970a0; color: white;" type="submit" class="btn mt-4">Guardar cambios</button>
        </form>
    </div>
</main>
    <?php
} else {
    echo "No se encontró el administrador con el ID proporcionado.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
    ?>