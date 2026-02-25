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
            <li class="breadcrumb-item"><a href="./modulos.php">Modulos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear modulos</li>
        </ol>
    </nav>

    <div style="background-color: white; border-radius: 20px !important;" id="formulario1" class=" p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Registro de Modulos:</h2>

        <form action="../Funciones/funcion_crear_modulo.php" method="POST">
            <div class="row">



                <div class="row mt-2">
                    <!-- Primera Columna -->
                    <div class="col-md-6">
                        <div class="input-group mt-4">
                            <label class="input-group-text" for="grupo">Nombre del Modulo</label>
                            <input type="text" class="form-control" id="nombre_materia" name="nombre_materia" required>

                        </div>
                    </div>


                </div>

                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Grupo</label>
                        <?php
                        // Aquí debes establecer la conexión a tu base de datos

                        // Consulta SQL para obtener todos los grupos
                        $sql = "SELECT id, CONCAT(nombre_grupo, ' ', 'Grupo ', seccion) AS nombre_completo FROM grupos";

                        // Ejecutar la consulta
                        $resultado = $conexion->query($sql);

                        // Comprobar si se encontraron grupos
                        if ($resultado->num_rows > 0) {
                            // Mostrar un select con los grupos
                            echo '<select required id="grupo_asignado" name="grupo_asignado" class="form-select">';
                            echo '<option selected>Selecciona un grupo...</option>';
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

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Docente</label>
                        <?php
                        // Aquí debes establecer la conexión a tu base de datos

                        // Consulta SQL para obtener todos los docentes
                        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM docentes";

                        // Ejecutar la consulta
                        $resultado = $conexion->query($sql);

                        // Comprobar si se encontraron docentes
                        if ($resultado->num_rows > 0) {
                            // Mostrar un select con los docentes
                            echo '<select required id="docente_asignado" name="docente_asignado" class="form-select">';
                            echo '<option selected>Selecciona un docente...</option>';
                            // Iterar sobre los resultados y mostrar cada docente como una opción en el select
                            while ($row = $resultado->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['nombre_completo'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo "No se encontraron docentes disponibles.";
                        }

                        // Cerrar la conexión
                        ?>


                    </div>
                </div>
            </div>



            <div class="row mt-2">
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Fecha de inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" >

                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Fecha de Finalizacion</label>
                        <input type="date" class="form-control" id="fecha_finalizacion" name="fecha_finalizacion" >

                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Estado</label>
                        <select id="estado" name="estado" class="form-select">
                            <option selected>Selecciona..</option>
                            <option value="Finalizado">Finalizado</option>
                            <option value="Aplazada">Aplazada</option>
                            <option value="En curso">En curso</option>
                        </select>

                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Quinta Columna (Botón de Enviar) -->
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Registrar Modulo</button>
                </div>
            </div>
        </form>
    </div>
</main>
<br>
<br>