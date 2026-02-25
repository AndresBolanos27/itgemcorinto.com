<?php require "sidebar.php"; ?>

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
            <li class="breadcrumb-item"><a href="./docentes.php">Docentes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear docentes</li>
        </ol>
    </nav>
    
    <div style="border-radius: 20px; background-color: white;" id="formulario1" class=" p-4 border p-custom mt-5">
        <h2 class="fw-bolder ms-2">Registro de docente:</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-2 mt-3">Datos personales</span>

        <!-- Inicia Formulario -->
        <form action="../Funciones/funcion_crear_docente.php" method="POST">
            <!-- Grupo 1 -->
            <div class="row mb-3 mt-3">
                <div class="col-md-6 mt-2">
                    <!-- Nombre -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input autocomplete="of" id="nombres" name="nombres" type="text" class="form-control" placeholder="Nombres:" aria-label="Username" aria-describedby="addon-wrapping" required maxlength="25">
                        </div>

                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <!-- Apellido -->
                    <div class="form-group">
                        <div class="input-group flex-nowrap">
                            <i style="font-size: 27px;" class="fa-solid fa-user input-group-text"></i>
                            <input autocomplete="of" id="apellidos" name="apellidos" type="text" class="form-control" placeholder="Apellido:" aria-label="Username" aria-describedby="addon-wrapping" required maxlength="25">
                        </div>
                    </div>
                </div>

            </div>
            <!-- Grupo 1 -->

            <!-- Grupo 3 -->
            <div class="row">

                <div class="col-md-6 mt-2">
                    <div class="input-group mb-3">
                        <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>

                        <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                            <option selected>Selecciona un tipo de documento..</option>
                            <option value="Cedula">Cedula</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-id-card input-group-text"></i>
                        <input autocomplete="of" placeholder="Numero de documento" id="documento_identidad" name="documento_identidad" type="number" class="form-control" aria-describedby="addon-wrapping" required pattern="\d{1,10}">

                    </div>
                </div>
            </div>
            <!-- Grupo 3 -->


            <!-- Grupo 2 -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lugar_nacimiento">Lugar de expedición:</label>
                        <div class="input-group flex-nowrap mt-2">
                            <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                            <input autocomplete="of" id="expedido_en" name="expedido_en" type="text" class="form-control" placeholder="Lugar de expedición:" aria-label="Username" aria-describedby="addon-wrapping" maxlength="40" required>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 2 -->

            <!-- Grupo 2 -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <div class="input-group flex-nowrap mt-2">
                            <i style="font-size: 27px;" class="fa-solid fa-calendar-days input-group-text"></i>
                            <input id="fecha_nacimiento" name="fecha_nacimiento" id="apellido" name="apellido" type="date" class="form-control" aria-describedby="addon-wrapping" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lugar_nacimiento">Lugar de nacimiento:</label>
                        <div class="input-group flex-nowrap mt-2">
                            <i style="font-size: 27px;" class="fa-solid fa-earth-americas input-group-text"></i>
                            <input autocomplete="of" id="lugar_nacimiento" name="lugar_nacimiento" type="text" class="form-control" placeholder="Lugar de nacimiento:" aria-label="Username" aria-describedby="addon-wrapping" maxlength="40" required>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 2 -->

            <!-- Grupo 4 -->
            <div class="col-md-6 mt-2">
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
            <!-- Grupo 4 -->

            <!-- Grupo 5 -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-map-location-dot input-group-text"></i>
                        <input autocomplete="of" id="direccion_residencia" name="direccion_residencia" type="text" class="form-control" placeholder="Direccion:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-map-location-dot input-group-text"></i>
                        <input autocomplete="of" id="direccion_demografica" name="direccion_demografica" type="text" class="form-control" placeholder="Direccion Demografica:" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group flex-nowrap">
                        <i style="font-size: 27px;" class="fa-solid fa-phone input-group-text"></i>
                        <input autocomplete="of" id="n_celular" name="n_celular" type="text" class="form-control" placeholder="Telefono:" aria-label="Username" aria-describedby="addon-wrapping" required>
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
                                <input autocomplete="of" id="perfil" name="perfil" type="text" class="form-control" placeholder="Perfil:" aria-label="Username" aria-describedby="addon-wrapping" required>
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
                                <input autocomplete="of" id="correo_electronico" name="correo_electronico" type="email" class="form-control" placeholder="email:" aria-label="email" aria-describedby="addon-wrapping" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grupo 7 -->
            <button style="background-color: #2970a0; color: white;" type="submit" class="btn mt-4">Registrar</button>
        </form>

    </div>
    <br>

    <BR></BR>

    <footer class="espacecustom mb-4 border p-3">
        <center>
            <p class="mb-0">Santander Valley Col Copyright © 2023. All rights reserved.</p>
        </center>

    </footer>
    </main>

    <br>