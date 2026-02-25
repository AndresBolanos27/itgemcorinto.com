<?php 
require "sidebar.php";
require "../App/conexion.php"; // Asegúrate de que esta línea incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_POST['id']; // Cambiado a POST para mayor seguridad

        $nombre = $_POST['nombre'];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
        $lugar_nacimiento = $_POST["lugar_nacimiento"];
        $documento_identidad = $_POST["documento_identidad"];
        $lugar_expedicion = $_POST["lugar_expedicion"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $nombre_acudiente = $_POST["nombre_acudiente"];
        $documento_identidad_acudiente = $_POST["documento_identidad_acudiente"];
        $eps = $_POST["eps"];
        $estado_matricula = $_POST["estado_matricula"];
        $correo = $_POST["correo"];
        $grupo_id = $_POST["grupo_id"]; // Nuevo campo para el grupo

        // Obtener el valor anterior del documento_identidad
        $stmt_old_doc = $conexion->prepare("SELECT documento_identidad, ruta_pdf FROM estudiantes WHERE id = ?");
        $stmt_old_doc->bind_param("i", $id);
        $stmt_old_doc->execute();
        $result_old_doc = $stmt_old_doc->get_result();
        $old_doc_ident_row = $result_old_doc->fetch_assoc();
        $old_doc_ident = $old_doc_ident_row['documento_identidad'];
        $ruta_pdf = $old_doc_ident_row['ruta_pdf'] ?? '';
        $stmt_old_doc->close();

        // Manejar la subida del archivo si se proporciona un nuevo archivo
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
            $target_dir = "../uploads/";  // Directorio donde se guardarán los archivos
            $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
            $target_file = $target_dir . $nombre . "_" . time() . "." . $fileType;  // Añadir el nombre del estudiante y timestamp al nombre del archivo
            $uploadOk = 1;

            // Verificar si el archivo es un PDF o un Word
            if($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
                echo "Lo sentimos, solo se permiten archivos PDF, DOC y DOCX.";
                $uploadOk = 0;
            }

            // Verificar si $uploadOk es 0 por algún error
            if ($uploadOk == 0) {
                echo "Lo sentimos, tu archivo no fue subido.";
            // Si todo está bien, intenta subir el archivo
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "El archivo ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " ha sido subido.";
                    $ruta_pdf = $target_file;
                } else {
                    echo "Lo sentimos, hubo un error al subir tu archivo.";
                }
            }
        }

        // Actualizar los datos del estudiante
        $stmt_update = $conexion->prepare("UPDATE estudiantes SET 
            nombre = ?, fecha_nacimiento = ?, lugar_nacimiento = ?, documento_identidad = ?, lugar_expedicion = ?, direccion = ?, telefono = ?, nombre_acudiente = ?, documento_identidad_acudiente = ?, eps = ?, estado_matricula = ?, correo = ?, grupo_id = ?, ruta_pdf = ? WHERE id = ?");
        $stmt_update->bind_param("ssssssssssssssi", $nombre, $fecha_nacimiento, $lugar_nacimiento, $documento_identidad, $lugar_expedicion, $direccion, $telefono, $nombre_acudiente, $documento_identidad_acudiente, $eps, $estado_matricula, $correo, $grupo_id, $ruta_pdf, $id);

        if ($stmt_update->execute()) {
            echo "<script>alert('Los datos del estudiante se han actualizado correctamente.');</script>";
            echo "<script>window.location.href = 'estudiantes.php';</script>";

            // Actualizar el nombre de usuario en la tabla usuarios
            $stmt_update_usuario = $conexion->prepare("UPDATE usuarios SET nombre_usuario = ? WHERE nombre_usuario = ?");
            $stmt_update_usuario->bind_param("ss", $documento_identidad, $old_doc_ident);

            if (!$stmt_update_usuario->execute()) {
                echo "Error al actualizar el nombre de usuario en la tabla usuarios: " . $conexion->error;
            }

            $stmt_update_usuario->close();
        } else {
            echo "Error al actualizar los datos del estudiante: " . $conexion->error;
        }

        $stmt_update->close();
    } else {
        echo "No se ha proporcionado un ID en el formulario.";
    }
}

$id = $_GET['id'];

$stmt_estudiante = $conexion->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt_estudiante->bind_param("i", $id);
$stmt_estudiante->execute();
$resultado = $stmt_estudiante->get_result();

// Verificar si se encontraron resultados
if ($resultado->num_rows > 0) {
    // Obtener los datos del estudiante
    $fila = $resultado->fetch_assoc();

    // Mostrar el formulario de edición con los campos prellenados
?>

<main>
    <div style="background: none !important;" class="espacecustom rounded ">
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <?php
                // Definir un array asociativo para mapear los roles a los colores de los badges
                $colores_roles = array(
                    'admin' => 'danger',
                    'docente' => 'primary',
                    'estudiante' => 'success'
                );

                // Obtener el rol del usuario actual desde la sesión
                $rol_usuario = $_SESSION['rol'];

                // Verificar si el rol del usuario está definido en el array de colores
                $badge_color = isset($colores_roles[$rol_usuario]) ? $colores_roles[$rol_usuario] : 'secondary';

                // Mostrar el rol del usuario en el badge
                echo '<span style="font-size: 16px;" class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($rol_usuario) . '</span>';
                echo '<span style="font-size: 16px;" class="ms-1 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . " " . ucfirst($apellido_administrador) .  '</span>'; 
                ?>
            </li>
        </ul>
    </div>

    <nav class="ms-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./estudiantes.php">Estudiantes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar estudiante</li>
        </ol>
    </nav>

    <div style="border-radius: 20px; background-color: white;" id="formulario1" class="p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Editar estudiante:</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos personales</span>
        <form action="" method="POST" enctype="multipart/form-data">
            <br>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="estadode">Estado:</label>
                        <select class="form-control" id="estado_matricula" name="estado_matricula">
                            <option value="">Selecciona...</option>
                            <option value="Matriculado" <?php if ($fila['estado_matricula'] == 'Matriculado') echo 'selected'; ?>>Matriculado</option>
                            <option value="Egresado" <?php if ($fila['estado_matricula'] == 'Egresado') echo 'selected'; ?>>Egresado</option>
                            <option value="Retirado" <?php if ($fila['estado_matricula'] == 'Retirado') echo 'selected'; ?>>Retirado</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
            <div class="row mb-3">
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input value="<?php echo htmlspecialchars($fila['nombre']); ?>" autocomplete="off" id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre:" aria-label="Username" aria-describedby="addon-wrapping" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-calendar-days input-group-text"></i>
                            <input value="<?php echo $fila['fecha_nacimiento']; ?>" id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control" aria-describedby="addon-wrapping" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lugar_nacimiento">Lugar de nacimiento:</label>
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                            <input value="<?php echo htmlspecialchars($fila['lugar_nacimiento']); ?>" autocomplete="off" id="lugar_nacimiento" name="lugar_nacimiento" type="text" class="form-control" placeholder="Lugar de nacimiento:" aria-label="Username" aria-describedby="addon-wrapping" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                        <input value="<?php echo htmlspecialchars($fila['documento_identidad']); ?>" autocomplete="off" placeholder="Numero de documento" id="documento_identidad" name="documento_identidad" type="number" class="form-control" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lugar_expedicion">Expedida en:</label>
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                        <input value="<?php echo htmlspecialchars($fila['lugar_expedicion']); ?>" autocomplete="off" id="lugar_expedicion" name="lugar_expedicion" type="text" class="form-control" placeholder="Lugar de Expedición:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-map-location-dot input-group-text"></i>
                        <input value="<?php echo htmlspecialchars($fila['direccion']); ?>" autocomplete="off" id="direccion" name="direccion" type="text" class="form-control" placeholder="Direccion:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                        <input value="<?php echo htmlspecialchars($fila['telefono']); ?>" autocomplete="off" id="telefono" name="telefono" type="text" class="form-control" placeholder="Telefono:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>

            <br>
            <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos del acudiente</span>
            <br>
            <br>

            <div class="row mb-3">
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input value="<?php echo htmlspecialchars($fila['nombre_acudiente']); ?>" autocomplete="off" id="nombre_acudiente" name="nombre_acudiente" type="text" class="form-control" placeholder="Nombre:" aria-label="Username" aria-describedby="addon-wrapping" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                        <input value="<?php echo htmlspecialchars($fila['documento_identidad_acudiente']); ?>" autocomplete="off" placeholder="Numero de documento_acudiente" id="documento_identidad_acudiente" name="documento_identidad_acudiente" type="number" class="form-control" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>

            <br>
            <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos académicos</span>
            <br>
            <br>

            <div class="form-group mb-3">
                <label for="eps">eps:</label>
                <input value="<?php echo htmlspecialchars($fila['eps']); ?>" autocomplete="off" type="text" class="form-control" id="eps" name="eps" required>
            </div>

            <div class="form-group mb-3">
                <label for="correo">Correo:</label>
                <input value="<?php echo htmlspecialchars($fila['correo']); ?>" autocomplete="off" type="email" class="form-control" id="correo" name="correo" required>
            </div>

            <!-- Añadir selección de grupo -->
            <div class="form-group mb-3">
                <label for="grupo_id">Grupo:</label>
                <select class="form-control" id="grupo_id" name="grupo_id" required>
                    <option value="">Selecciona un grupo</option>
                    <?php
                    // Consulta para obtener los grupos
                    $sqlGrupos = "SELECT id, nombre_grupo, seccion FROM grupos";
                    $resultadoGrupos = $conexion->query($sqlGrupos);
                    if ($resultadoGrupos->num_rows > 0) {
                        while ($grupo = $resultadoGrupos->fetch_assoc()) {
                            $selected = $fila['grupo_id'] == $grupo['id'] ? 'selected' : '';
                            echo '<option value="' . $grupo['id'] . '" ' . $selected . '>' . $grupo['nombre_grupo'] . ' ' . $grupo['seccion'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Mostrar ruta del PDF con enlace -->
            <div class="form-group mb-3">
                <label for="ruta_pdf">Ruta del PDF:</label>
                <input value="<?php echo htmlspecialchars($fila['ruta_pdf']); ?>" autocomplete="off" type="text" class="form-control" id="ruta_pdf" name="ruta_pdf" readonly>
                <?php if (!empty($fila['ruta_pdf'])) { ?>
                    <a href="<?php echo '../uploads/' . basename($fila['ruta_pdf']); ?>" target="_blank">Ver PDF</a>
                <?php } ?>
            </div>

            <?php if (empty($fila['ruta_pdf'])) { ?>
                <!-- Campo para subir PDF si no hay PDF existente -->
                <div class="form-group mb-3">
                    <label for="fileToUpload">Subir PDF:</label>
                    <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" accept=".pdf,.doc,.docx">
                </div>
            <?php } ?>

            <br>
            <button type="submit" class="btn btn-primary">Editar Estudiante</button>
        </form>
    </div>
</main>

<?php
} else {
    echo "No se encontró el estudiante con el ID proporcionado.";
}
$stmt_estudiante->close();
?>
