<?php include_once './sidebar.php'; ?>

<main>
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

    ?>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content page-content-custom"><br>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 d-flex ms-2 me-2 mt-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-4">ITGEM - CORINTO</h1>
                    <h2><?php echo '<span class="mb-2 badge bg-' . $badge_color . '">' . ucfirst($nombre_administrador) . " " . ucfirst($apellido_administrador) .  '</span>'; ?> </h2>
                    <p style="width: 85%; font-size: 18px;">Maximiza la eficiencia de tu gestión académica con nuestra plataforma. Simplifica la administración de Grupos, notas y datos de estudiantes de manera integrada y eficaz.</p>
                    <div>
                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-tag me-2 rounded-circle p-2" style="font-size: 28px;"></i>
                            <h5 class="fw-bold mb-0">Gestión academica.</h5>
                        </span>

                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-user me-2 rounded-circle p-2" style="font-size: 25px;"></i>
                            <h5 class="fw-bold mb-0">Gestión de estudiantes</h5>
                        </span>


                        <span class="mb-3" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-clipboard me-2 rounded-circle p-2" style="font-size: 25px;"></i>
                            <h5 class="fw-bold mb-0">Calificación de Notas</h5>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class=" mb-4 d-flex justify-content-center img-custom">

                    </div>
                </div>
            </div>


            <br>
            <div style="padding: 12px;">

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 d-flex">
                    <!-- Usuarios -->
                    <div class="col mb-3">
                        <div class="radius-10 custom-card p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #172a54;" class="fw-bold">Docentes</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-circle-user"></i>
                            </div>
                            <hr style="border-color: #1c2e4f 2px;">
                            <h5 style="color: #1c2e4f;">Total de Docentes:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosDocentes; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/docentes.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Docentes</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Usuarios -->

                    <!-- Usuarios -->
                    <div class="col mb-3">
                        <div class="radius-10 custom-card2 p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #1c2e4f;" class="fw-bold">Estudiantes</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-person"></i>
                            </div>
                            <hr>
                            <h5 style="color: #1c2e4f;">Total de Estudiantes:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosEstudiantes; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/estudiantes.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Estudiantes</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col mb-3">
                        <div class="radius-10 custom-card3 p-3">
                            <div class="mb-2" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="color: #1c2e4f;" class="fw-bold">Grupos</h3>
                                <i style="font-size: 50px; color: #1c2e4f;" class="fa-solid fa-tag"></i>
                            </div>
                            <hr>
                            <h5 style="color: #1c2e4f;">Total de Grupos:</h5>

                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h1 style="color: #1c2e4f;" class="my-2 fw-bold"><?php echo $totalRegistrosGrupos; ?></h1>
                                </div>
                                <div>
                                    <a href="./App/grupos.php"><button style="background-color: #1c2e4f; color: white;" type="button" class="btn">Ver Grupos</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>

                <footer class="espacecustom mb-4 border p-3">
                    <center>
                        <p class="mb-0">Santander Valley Col Copyright © 2024. All rights reserved.</p>
                    </center>

                </footer>
                <br><br>
</main>