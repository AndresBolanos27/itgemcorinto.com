<?php require "sidebar.php"; ?>

<!--  -->
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

    <!--  -->
    <!--  -->
    <div style="background-color: white; border-radius: 20px !important;" class="row row-cols-1 row-cols-md-2 row-cols-xl-2 mt-4 p-3">
        <div class="col">
            <h1 class="ms-2 fw-bolder mb-3 mt-4">Crea un nuevo Modulo!</h1>
            <p style="width: 90%;" class="ms-2 fs-5">Sistema academico ITGEM.</p>
            <div style="background: none !important; margin-top: 40px !important;" class=" mt-4 rounded ">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
                    <div class="col mb-3">
                        <div style="background-color: #fef08a;  border-radius: 20px;" class="card radius-10 p-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p style="color: #141e2c; font-size: 20px; font-weight: 800;" href="" class="txt-card-custom mb-0">Crear Modulo</p>
                                        <a style="color: #141e2c !important;" href="./crear_modulos.php" class="text-blue-500 hover:underline">Click
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
                                        <p style="color: #141e2c; font-size: 20px; font-weight: 800;" href="" class="txt-card-custom mb-0">Ver Modulo</p>
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
            <p class="mt-4 ms-2" style="width: 80%;">Dale click en el boton correspondiente para crear o editar un modulo</p>
        </div>
        <div class="col">
            <div class="col mb-4 d-flex justify-content-center">
                <img width="430" class="img" src="../recursos/img/img1.svg" alt="">
            </div>
        </div>
    </div>



    <div id="formulario1" style="display: none;" class="espacecustom p-4 border p-custom">
        <h2 class="fw-bolder ms-2">Registro de Modulos:</h2>

        <form action="./procesar/procesar_registro_grupo.php" method="POST">
            <div class="row">
                <!-- Primera Columna -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre_grupo">Nombre del Grupo:</label>
                        <input type="text" class="form-control" id="nombre_grupo" name="nombre_grupo" required>
                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="col-md-6">
                    <div class="input-group mt-4">
                        <label class="input-group-text" for="grupo">Grupo</label>
                        <select id="grupo" name="grupo" class="form-select">

                        </select>
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



    <div class="mt-5">
    <h2 class="fw-bolder ms-3 mt-5">Tabla de modulos</h2>
    <span style="font-size: 15px;" class="badge text-bg-info ms-3 mb-4">Datos</span>
    <div class="container mt-4">
        <form method="POST" id="filtrosForm" class="d-flex flex-wrap gap-3">
            <div class="mb-3">
                <label for="filtroEstado" class="form-label">Filtrar por estado:</label>
                <select class="form-select" name="filtroEstado" id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <?php
                    // Consulta para obtener los estados únicos
                    $sqlEstados = "SELECT DISTINCT estado FROM materias";
                    $resultadoEstados = $conexion->query($sqlEstados);
                    if ($resultadoEstados->num_rows > 0) {
                        while ($estado = $resultadoEstados->fetch_assoc()) {
                            echo '<option value="' . $estado['estado'] . '">' . $estado['estado'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="filtroDocente" class="form-label">Filtrar por docente:</label>
                <select class="form-select" name="filtroDocente" id="filtroDocente">
                    <option value="">Todos los docentes</option>
                    <?php
                    // Consulta para obtener los docentes únicos
                    $sqlDocentes = "SELECT DISTINCT d.id, d.nombres, d.apellidos FROM docentes d
                                    INNER JOIN materias m ON m.docente_asignado = d.id";
                    $resultadoDocentes = $conexion->query($sqlDocentes);
                    if ($resultadoDocentes->num_rows > 0) {
                        while ($docente = $resultadoDocentes->fetch_assoc()) {
                            echo '<option value="' . $docente['id'] . '">' . $docente['nombres'] . ' ' . $docente['apellidos'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="filtroGrupo" class="form-label">Filtrar por grupo:</label>
                <select class="form-select" name="filtroGrupo" id="filtroGrupo">
                    <option value="">Todos los grupos</option>
                    <?php
                    // Consulta para obtener los grupos únicos
                    $sqlGrupos = "SELECT DISTINCT g.id, g.nombre_grupo, g.seccion FROM grupos g
                                  INNER JOIN materias m ON m.grupo_asignado = g.id";
                    $resultadoGrupos = $conexion->query($sqlGrupos);
                    if ($resultadoGrupos->num_rows > 0) {
                        while ($grupo = $resultadoGrupos->fetch_assoc()) {
                            echo '<option value="' . $grupo['id'] . '">' . $grupo['nombre_grupo'] . ' ' . $grupo['seccion'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </form>
    </div>
    <div style="background-color: white; border-radius: 20px !important;" class="table-responsive p-4">
        <table id="tabla_administradores" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th> <!-- Agregamos una columna para la numeración -->
                    <th>nombre_materia</th>
                    <th>docente</th>
                    <th>grupo</th>
                    <th>fecha_inicio</th>
                    <th>fecha_finalizacion</th>
                    <th>estado</th> <!-- Agregamos una columna para las acciones -->
                    <th>Acciones</th> <!-- Agregamos una columna para las acciones -->
                </tr>
            </thead>
            <tbody id="tablaCuerpo">
                <?php
                // Incluir el archivo de conexión a la base de datos
                include '../App/conexion.php';

                // Construir la consulta SQL base para seleccionar todos los módulos
                $sql = "SELECT m.*, d.nombres AS nombre_docente, d.apellidos AS apellido_docente, g.nombre_grupo, g.seccion
                        FROM materias m
                        INNER JOIN docentes d ON m.docente_asignado = d.id
                        INNER JOIN grupos g ON m.grupo_asignado = g.id";

                // Verificar si se envió el formulario con los filtros de estado, docente y grupo
                $conditions = array();
                if (isset($_POST['filtroEstado']) && $_POST['filtroEstado'] !== '') {
                    $filtroEstado = $_POST['filtroEstado'];
                    $conditions[] = "m.estado = '$filtroEstado'";
                }
                if (isset($_POST['filtroDocente']) && $_POST['filtroDocente'] !== '') {
                    $filtroDocente = $_POST['filtroDocente'];
                    $conditions[] = "d.id = '$filtroDocente'";
                }
                if (isset($_POST['filtroGrupo']) && $_POST['filtroGrupo'] !== '') {
                    $filtroGrupo = $_POST['filtroGrupo'];
                    $conditions[] = "g.id = '$filtroGrupo'";
                }

                if (count($conditions) > 0) {
                    $sql .= " WHERE " . implode(' AND ', $conditions);
                }

                $resultado = $conexion->query($sql);

                // Variable para la numeración de la tabla
                $contador = 1;

                // Verificar si se encontraron resultados
                if ($resultado->num_rows > 0) {
                    // Iterar sobre los resultados y generar las filas de la tabla
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $contador . "</td>";
                        echo "<td>" . $fila['nombre_materia'] . "</td>";
                        echo "<td>" . $fila['nombre_docente'] . " " . $fila['apellido_docente'] . "</td>"; // Mostrar nombres y apellidos del docente
                        echo "<td>" . $fila['nombre_grupo'] . ' '  . $fila['seccion'] . "</td>"; // Mostrar el nombre del grupo
                        echo "<td>" . $fila['fecha_inicio'] . "</td>";
                        echo "<td>" . $fila['fecha_finalizacion'] . "</td>";
                        echo "<td>" . $fila['estado'] . "</td>";
                        // Agregamos los botones de acciones (editar y eliminar)
                        echo "<td>";
                        echo "<a href='editar_modulo.php?id=" . $fila['id'] . "' class='btn btn-primary mb-2' style='width: 100px;'>Editar</a>";
                        echo "</td>";
                        echo "</tr>";
                        $contador++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No se encontraron módulos</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<br>
<br>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabla_administradores').DataTable({
            responsive: false,
            searching: true, // Habilitar la función de búsqueda
            rowReorder: {
                selector: 'td:first-child'
            },
            columnDefs: [{
                orderable: false,
                targets: 0
            }]
        });

        // Función para actualizar la tabla automáticamente
        function actualizarTabla() {
            $.ajax({
                type: "POST",
                url: "", // La URL actual, ya que estamos en el mismo archivo
                data: $("#filtrosForm").serialize(),
                success: function(response) {
                    var newHtml = $(response).find("#tablaCuerpo").html();
                    $("#tablaCuerpo").html(newHtml);
                }
            });
        }

        // Llamar a la función actualizarTabla() cuando cambien los filtros
        $("#filtroEstado, #filtroDocente, #filtroGrupo").on("change", function() {
            actualizarTabla();
        });
    });
</script>





    <footer class="espacecustom mb-4 border p-3">
        <center>
            <p class="mb-0">Santander Valley Col Copyright © 2023. All rights reserved.</p>
        </center>

    </footer>

</main>