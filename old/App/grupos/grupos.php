<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();



// Consultar los niveles educativos para el menú desplegable
$sql_niveles = "SELECT id, nombre_nivel FROM niveles WHERE estado = 'activo'";
$result_niveles = $conn->query($sql_niveles);

// Consultar los ciclos escolares para el menú desplegable
$sql_ciclos = "SELECT id, nombre_ciclo, fecha_inicio, fecha_fin FROM ciclos_escolares WHERE estado = 'activo'";
$result_ciclos = $conn->query($sql_ciclos);

?>


<div class="h-screen w-11/12 mx-auto flex justify-center items-center  md:my-0">
    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
        <form id="registroForm" action="procesar_registro_grupo" method="post">
            <!--  -->
            <div class="lg:flex lg:items-center lg:justify-between">

                <div class=" flex mb-5  lg:mt-0">
                    <!-- Botón personalizado que abre el modal -->
                    <span>
                        <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-inset ring-gray-300 hover:bg-gray-50" onclick="my_modal_5.showModal()">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                            </svg>
                            Ver/Editar Grupo
                        </button>
                    </span>
                </div>
            </div>
            <!--  -->

            <h1 class="mb-8 text-2xl font-semibold">Registro de Grupos</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>
                    <label for="Codigo" class="block text-sm">Codigo</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input type="text" id="Codigo" name="Codigo" required pattern="[A-Za-z0-9]+" oninput="validateField(this)" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <div id="error-Codigo" class="error-message"></div>
                    </div>
                </div>



                <div>
                    <label class="block text-sm" for="nombre_grupo">Nombre del Grupo:</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input type="text" id="nombre_grupo" name="nombre_grupo" required pattern="[A-Za-z0-9\s]+" oninput="validateField(this)" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <div id="error-nombre_grupo" class="error-message"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm" for="estado">Estado:</label>

                    <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="estado" name="estado" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                    <div id="error-estado" class="error-message"></div>
                </div>

                <div>
                    <label class="block text-sm" for="nivel_id">Nivel Educativo:</label>
                    <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="nivel_id" name="nivel_id" required>
                        <?php while ($row = $result_niveles->fetch_assoc()) : ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre_nivel']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-nivel_id" class="error-message"></div>
                </div>

                <div>
                    <label class="block text-sm" for="ciclo_id">Ciclo Escolar:</label>
                    <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="ciclo_id" name="ciclo_id" required>
                        <?php while ($row = $result_ciclos->fetch_assoc()) : ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nombre_ciclo']) . ' (' . htmlspecialchars($row['fecha_inicio']) . ' - ' . htmlspecialchars($row['fecha_fin']) . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>


                    <div id="error-ciclo_id" class="error-message"></div>
                </div>

            </div>
            <!-- Botón de Envío -->
            <div class="col-span-full">
                <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
            </div>
        </form>


    <?php else : ?>
        <p>No tienes permisos para registrar grupos.</p>
    <?php endif; ?>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
</div>

<script>
    document.getElementById('registroForm').addEventListener('submit', function(event) {
        const fields = ['Codigo', 'nombre_grupo', 'estado', 'nivel_id'];
        let isValid = true;

        fields.forEach(function(field) {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById('error-' + field);
            if (!input.checkValidity()) {
                errorDiv.textContent = getErrorMessage(input);
                isValid = false;
            } else {
                errorDiv.textContent = '';
            }
        });

        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateField(input) {
        const errorDiv = document.getElementById('error-' + input.id);
        if (!input.checkValidity()) {
            errorDiv.textContent = getErrorMessage(input);
        } else {
            errorDiv.textContent = '';
        }
    }

    function getErrorMessage(input) {
        if (input.validity.valueMissing) {
            return 'Este campo es obligatorio.';
        }
        if (input.validity.patternMismatch) {
            if (input.id === 'Codigo') {
                return 'Solo se permiten letras y números.';
            }
            if (input.id === 'nombre_grupo') {
                return 'Solo se permiten letras y espacios.';
            }
        }
        return 'El valor introducido no es válido.';
    }
</script>




<!-- Modal -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box min-w-max mx-auto">
        <h3 class="text-lg font-bold">Editar</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>

        <?php
        // Generar un token único y almacenarlo en la sesión
        $_SESSION['download_token'] = bin2hex(random_bytes(32));

        // Consultar los datos de la tabla grupos junto con los niveles educativos y ciclos escolares
        $sql = "SELECT grupos.*, niveles.nombre_nivel, ciclos_escolares.nombre_ciclo, ciclos_escolares.fecha_inicio, ciclos_escolares.fecha_fin 
                FROM grupos 
                LEFT JOIN niveles ON grupos.nivel_id = niveles.id 
                LEFT JOIN ciclos_escolares ON grupos.ciclo_id = ciclos_escolares.id";
        $result = $conn->query($sql);

        // Verificar si hay registros
        if ($result->num_rows > 0) {
            // Almacenar los datos en un array
            $grupos = [];
            while ($row = $result->fetch_assoc()) {
                $grupos[] = $row;
            }
        } else {
            $grupos = [];
        }

        $conn->close();
        ?>

        <!-- Input de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar..." class="input input-bordered w-full max-w-xs my-4">

        <?php if (count($grupos) > 0) : ?>
            <div class="overflow-x-auto">
                <div class="flex justify-center items-center">
                    <table class="table table-xs" id="grupos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre del Grupo</th>
                                <th>Nivel Educativo</th>
                                <th>Ciclo Escolar</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="grupos-table-body">
                            <?php foreach ($grupos as $grupo) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($grupo['Codigo']); ?></td>
                                    <td><?php echo htmlspecialchars($grupo['nombre_grupo']); ?></td>
                                    <td><?php echo htmlspecialchars($grupo['nombre_nivel']); ?></td>
                                    <td><?php echo htmlspecialchars($grupo['nombre_ciclo']) . ' (' . htmlspecialchars($grupo['fecha_inicio']) . ' - ' . htmlspecialchars($grupo['fecha_fin']) . ')'; ?></td>
                                    <td><?php echo htmlspecialchars($grupo['estado']); ?></td>
                                    <td class="actions">
                                        <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                            <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                                <a class="btn hover:underline edit me-2 mb-2" href="editar_grupo?id=<?php echo $grupo['id']; ?>">Editar</a>
                                                <a class="btn text-red-500 hover:underline me-2 mb-2 delete" href="borrar_grupo?id=<?php echo $grupo['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este grupo?');">Borrar</a>
                                            <?php else : ?>
                                                <a href="editar_grupo?id=<?php echo $grupo['id']; ?>" class="btn edit">Editar</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <div id="pagination" class="mt-4">
                <button id="prevPage" class="btn">Anterior</button>
                <span id="pageInfo" class="mx-4"></span>
                <button id="nextPage" class="btn">Siguiente</button>
            </div>
        <?php else : ?>
            <p>No hay grupos registrados.</p>
        <?php endif; ?>

        <!-- Botón para cerrar el modal -->
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Cerrar</button>
            </form>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 5; // Número de filas por página
        let currentPage = 1; // Página actual
        const tableBody = document.getElementById('grupos-table-body');
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const pageInfo = document.getElementById('pageInfo');
        const searchInput = document.getElementById('searchInput');

        // Función para actualizar la tabla
        function updateTable() {
            const filter = searchInput.value.toLowerCase();
            const filteredRows = rows.filter(row => {
                const cells = row.getElementsByTagName('td');
                return Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
            });

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            // Limpiar el cuerpo de la tabla
            tableBody.innerHTML = '';
            paginatedRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));

            // Actualizar la información de la página
            pageInfo.textContent = `Página ${currentPage} de ${Math.ceil(filteredRows.length / rowsPerPage)}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === Math.ceil(filteredRows.length / rowsPerPage);
        }

        // Evento para el botón "Anterior"
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        // Evento para el botón "Siguiente"
        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage < Math.ceil(rows.length / rowsPerPage)) {
                currentPage++;
                updateTable();
            }
        });

        // Evento para el campo de búsqueda
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            updateTable();
        });

        // Inicializar la tabla
        updateTable();
    });
</script>

<?php
include_once __DIR__ . '/../footer.php';
?>