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
            <li class="breadcrumb-item"><a href="./grupos.php">Grupos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear grupo</li>
        </ol>
    </nav>

    <div style="background-color: white !important;  border-radius: 20px;" id="formulario1" class=" p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Registro de grupos:</h2>

        <form action="../Funciones/funcion_crear_grupo.php" method="POST">
            <div class="row">
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Nombre de Grupo</label>
                        <input type="text" class="form-control" id="nombre_grupo" name="nombre_grupo" required>

                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Año lectivo</label>
                        <input type="text" class="form-control" id="año_lectivo" name="año_lectivo" required>

                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Nivel educativo</label>
                        <select id="nivel_educativo" name="nivel_educativo" class="form-select">
                            <option selected>Selecciona un nivel educativo..</option>
                            <option value="tecnicos">Técnico Laboral Por Competencias</option>
                            <option value="seminario">Seminario</option>
                            <option value="curso">Curso Corto</option>
                        </select>
                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Grupo</label>
                        <select id="seccion" name="seccion" class="form-select">
                            <option selected>Selecciona...</option>
                            <?php
                            for ($i = 1; $i <= 25; $i++) {
                                echo '<option value="' . $i . '">Grupo ' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Primera Columna -->
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


                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Salon Asignado</label>
                        <input type="text" class="form-control" id="aula_asignada" name="aula_asignada">

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