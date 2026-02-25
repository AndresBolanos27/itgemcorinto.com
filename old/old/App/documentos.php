<?php require "sidebar.php"; ?>


<main>
    <div class="mt-5">
        <h2 class="fw-bolder ms-3 mt-5">Tabla de Estudiantes</h2>
        <span style="font-size: 15px;" class="badge text-bg-info ms-3 mb-4 ">Datos personales</span>
        <div style="background-color: white; border-radius: 20px !important;" class="table-responsive  p-4">
            <table id="tabla_estudiantes" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th> <!-- Agregamos una columna para la numeración -->
                        <th>Nombre</th> <!-- Agregamos una columna para el nombre del estudiante -->
                        <th>Grupo</th> <!-- Agregamos una columna para el nombre del grupo -->
                        <th>Acciones</th> <!-- Agregamos una columna para las acciones -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Incluir el archivo de conexión a la base de datos
                    include '../App/conexion.php';

                    // Consulta SQL para seleccionar todos los estudiantes con el nombre de su grupo y sección
                    $sql = "SELECT estudiantes.id AS id_estudiante, estudiantes.nombre AS nombre_estudiante, CONCAT(grupos.nombre_grupo, ' ', grupos.seccion) AS nombre_grupo_seccion
        FROM estudiantes
        INNER JOIN grupos ON estudiantes.grupo_id = grupos.id";
                    $resultado = $conexion->query($sql);
                    $contador = 1;

                    // Verificar si se encontraron resultados
                    if ($resultado->num_rows > 0) {
                        // Iterar sobre los resultados y generar las filas de la tabla
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $contador . "</td>";
                            echo "<td>" . $fila['nombre_estudiante'] . "</td>";
                            echo "<td>" . $fila['nombre_grupo_seccion'] . "</td>";
                            // Agregamos un botón de acción (por ejemplo, para editar)
                            echo "<td><a href='subir_documentos.php?id=" . $fila['id_estudiante'] . "' class='btn btn-primary mb-2' style='width: 200px;'>Subir Documentos</a></td>";

                            echo "</tr>";
                            $contador++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron estudiantes</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <br>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
    <script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#tabla_estudiantes').DataTable({
                responsive: true,
                searching: true, // Habilitar la función de búsqueda
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