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

    <div style="background-color: white; border-radius: 20px !important;" class="row row-cols-1 row-cols-md-2 row-cols-xl-2 mt-4 p-3">
        <div class="col">
            <h1 class="ms-2 fw-bolder mb-3 mt-4">Crea un nuevo adminstrador!</h1>
            <p style="width: 90%;" class="ms-2 fs-5">Crea un nuevo adminstrador que te ayude a gestionar el area academica de tu instituto.</p>
            <div style="background: none !important; margin-top: 40px !important;" class=" mt-4 rounded ">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
                    <div class="col mb-3">
                        <div style="background-color: #fef08a;  border-radius: 20px;" class="card radius-10 p-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p style="color: #141e2c; font-size: 20px; font-weight: 800;" href="" class="txt-card-custom mb-0">Crear Admin</p>
                                        <a style="color: #141e2c !important;" href="./crear_admin.php" class="text-blue-500 hover:underline">Click
                                            aqui</a>
                                        <br>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <div style="background-color: #e4e4e7;  border-radius: 20px;" class="card radius-10 p-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p style="color: #141e2c; font-size: 20px; font-weight: 800;" href="" class="txt-card-custom mb-0">Ver Admin</p>
                                        <a style="color: #141e2c !important;" href="#tabla_administradores" class="text-blue-500 hover:underline">Click
                                            aqui</a>
                                        <br>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="mt-4 ms-2" style="width: 80%;">Dale click en el boton correspondiente para crear o editar un administrador</p>
        </div>
        <div class="col">
            <div class="col mb-4 d-flex justify-content-center">
                <img width="430" class="img" src="../recursos/img/img2.svg" alt="">
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="fw-bolder ms-3 mt-5">Tabla de Administradores</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-3 mb-4 ">Datos personales</span>

        <div style="background-color: white; border-radius: 20px !important;" class="table-responsive  p-4">
            <table id="tabla_administradores" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Documento</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>EPS</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../App/conexion.php';

                    $sql = "SELECT * FROM administradores";
                    $resultado = $conexion->query($sql);

                    $contador = 1;

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $contador . "</td>";
                            echo "<td>" . $fila['nombre'] . "</td>";
                            echo "<td>" . $fila['apellido'] . "</td>";
                            echo "<td>" . $fila['documento_identidad'] . "</td>";
                            echo "<td>" . $fila['direccion'] . "</td>";
                            echo "<td>" . $fila['telefono'] . "</td>";
                            echo "<td>" . $fila['eps'] . "</td>";
                            echo "<td>" . $fila['titulo'] . "</td>";
                            echo "<td>" . $fila['email'] . "</td>";
                            // Agregamos los botones de acciones (editar y eliminar)
                            echo "<td>";
                            echo "<a href='editar_admin.php?id=" . $fila['id'] . "' class='btn btn-primary mb-2' style='width: 100px;'>Editar</a>";
                            echo "</td>";
                            echo "</tr>";
                            $contador++;
                        }
                    } else {
                        echo "<tr><td colspan='13'>No se encontraron administradores</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <br>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla_administradores').DataTable({
                responsive: false,
                rowReorder: {
                    selector: 'td:first-child'
                },
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }]
            });
        });
    </script>

</main>