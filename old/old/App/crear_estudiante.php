<?php
include_once './sidebar.php';
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
            <li class="breadcrumb-item"><a href="./estudiantes.php">Estudiantes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear estudiante</li>
        </ol>
    </nav>

    <div style="border-radius: 20px; background-color: white;" id="formulario1" class="p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Registro de Estudiantes:</h2>

        <form action="../Funciones/funcion_crear_estudiante.php" method="POST" enctype="multipart/form-data">
        <br>
        <div class="col-md-6">
            <div class="input-group mb-3">
                <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                <label class="input-group-text" for="grupo">Selecciona un grupo</label>

                <?php
                // Aquí debes establecer la conexión a tu base de datos
                include '../App/conexion.php'; // Incluye la conexión aquí si no está ya incluida

                // Consulta SQL para obtener todos los grupos
                $sql = "SELECT id, CONCAT(nombre_grupo, ' ', 'Grupo ', seccion) AS nombre_completo FROM grupos";

                // Ejecutar la consulta
                $resultado = $conexion->query($sql);

                // Comprobar si se encontraron grupos
                if ($resultado->num_rows > 0) {
                    // Mostrar un select con los grupos
                    echo '<select required id="grupo_id" name="grupo_id" class="form-select">';
                    echo '<option selected>grupos...</option>';
                    // Iterar sobre los resultados y mostrar cada grupo como una opción en el select
                    while ($row = $resultado->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['nombre_completo'] . '</option>';
                    }
                    echo '</select>';
                } else {
                    echo "No se encontraron grupos disponibles.";
                }

                // Cerrar la conexión
                ?>
            </div>
        </div>
        <!-- Resto de tu formulario -->
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mb-3 mt-3">Datos personales</span>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <div class="form-group">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                        <input autocomplete="off" id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-group">
                    <!-- Deja este espacio en blanco para agregar más campos si es necesario -->
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-calendar-days input-group-text"></i>
                        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="lugar_nacimiento">Lugar de nacimiento:</label>
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                        <input autocomplete="off" id="lugar_nacimiento" name="lugar_nacimiento" type="text" class="form-control" placeholder="Lugar de nacimiento:" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                    <select id="tipo_documento" name="tipo_documento" class="form-select">
                        <option selected>Selecciona un tipo de documento..</option>
                        <option value="DNI">Tarjeta de identidad</option>
                        <option value="Cedula">Cedula</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group flex-nowrap">
                    <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                    <input autocomplete="off" placeholder="Numero de documento" id="documento_identidad" name="documento_identidad" type="number" class="form-control" aria-describedby="addon-wrapping" required>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="lugar_expedicion">Expedida en:</label>
                <div class="input-group flex-nowrap">
                    <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                    <input autocomplete="off" id="lugar_expedicion" name="lugar_expedicion" type="text" class="form-control" placeholder="Lugar de Expedición:" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="input-group">
                    <i style="font-size: 27px;" class="fa-solid fa-venus-mars input-group-text"></i>
                    <select id="genero" name="genero" class="form-select" required>
                        <option value="">Selecciona un género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group flex-nowrap">
                    <i style="font-size: 27px;" class="fa-solid fa-map-location-dot input-group-text"></i>
                    <input autocomplete="off" id="direccion" name="direccion" type="text" class="form-control" placeholder="Direccion Demografica:" aria-label="Username" aria-describedby="addon-wrapping">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group flex-nowrap">
                    <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                    <input autocomplete="off" id="telefono" name="telefono" type="text" class="form-control" placeholder="Telefono:" aria-label="Username" aria-describedby="addon-wrapping">
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
                        <input autocomplete="off" id="nombre_acudiente" name="nombre_acudiente" type="text" class="form-control" placeholder="Nombre:" aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-group">
                    <!-- Deja este espacio en blanco para agregar más campos si es necesario -->
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                    <select id="tipo_documento_acudiente" name="tipo_documento_acudiente" class="form-select">
                        <option selected>Selecciona un tipo de documento..</option>
                        <option value="DNI">Tarjeta de identidad</option>
                        <option value="Cedula">Cedula</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group flex-nowrap">
                    <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                    <input autocomplete="off" placeholder="Numero de documento_acudiente" id="documento_identidad_acudiente" name="documento_identidad_acudiente" type="number" class="form-control" aria-describedby="addon-wrapping">
                </div>
            </div>
        </div>

        <div class="row mb-3">
        </div>

        <br>
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos academicos</span>
        <br>
        <br>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="eps">EPS:</label>
                    <select class="form-control" id="eps" name="eps">
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
            <!-- Agrega más campos aquí si es necesario -->
        </div>

        <div class="form-group mb-3">
            <label for="correo">Correo:</label>
            <input autocomplete="off" type="email" class="form-control" id="correo" name="correo">
        </div>

        <div class="form-group mb-3">
            <label for="fileToUpload">Documento PDF:</label>
            <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" required>
        </div>
        
        <br>
        <button type="submit" class="btn btn-primary">Registrar Estudiante</button>
    </form>

      

    </div>
</main>