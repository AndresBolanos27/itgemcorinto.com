<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Generar un token único y almacenarlo en la sesión
$_SESSION['download_token'] = bin2hex(random_bytes(32));
?>

<div class="h-screen flex justify-center items-center p-6 my-64 md:my-0">
    <form id="registroDocenteForm" action="procesar_docentes" method="post" enctype="multipart/form-data">
        <!-- Botones y título -->
        <div class="lg:flex lg:items-center lg:justify-between">
            <div class="flex mb-5 lg:mt-0">
                <!-- Botón para abrir el modal -->
                <span>
                    <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-inset ring-gray-300 hover:bg-gray-50" onclick="my_modal_5.showModal()">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                        </svg>
                        Ver/Editar
                    </button>
                </span>

                <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                    <div class="flex mb-5 lg:mt-0">
                        <!-- Botón para Exportar a Excel -->
                        <span class="ml-3">
                            <a href="exportardocentesexcel.php?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-green-500">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <!-- Icono -->
                                </svg>
                                Excel
                            </a>
                        </span>

                        <!-- Botón para Exportar a PDF -->
                        <span class="ml-3">
                            <a href="exportardocentespdf.php?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-red-400">
                                <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <!-- Icono -->
                                </svg>
                                PDF
                            </a>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h1 class="mb-8 text-2xl font-semibold">Registro de Docentes</h1>

        <!-- Formulario de Registro -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="text" id="nombre" name="nombre" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-nombre" class="error-message"></div>
            </div>

            <!-- Apellido -->
            <div>
                <label for="apellido" class="block text-sm">Apellido</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="text" id="apellido" name="apellido" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-apellido" class="error-message"></div>
            </div>

            <!-- Cédula -->
            <div>
                <label for="cedula" class="block text-sm">Cédula</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Cédula -->
                    </span>
                    <input type="text" id="cedula" name="cedula" required pattern="\d+" oninput="validateField(this)" placeholder="Cédula" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-cedula" class="error-message"></div>
            </div>

            <!-- Correo -->
            <div>
                <label for="correo" class="block text-sm">Correo</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Correo -->
                    </span>
                    <input type="email" id="correo" name="correo" required oninput="validateField(this)" placeholder="Correo" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-correo" class="error-message"></div>
            </div>

            <!-- Celular -->
            <div>
                <label for="celular" class="block text-sm">Celular</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Celular -->
                    </span>
                    <input type="tel" id="celular" name="celular" pattern="\d{10}" oninput="validateField(this)" placeholder="Celular" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-celular" class="error-message"></div>
            </div>

            <!-- Sexo -->
            <div>
                <label for="sexo" class="block text-sm">Sexo</label>
                <select id="sexo" name="sexo" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione Sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
                <div id="error-sexo" class="error-message"></div>
            </div>

            <!-- Título -->
            <div>
                <label for="titulo" class="block text-sm">Título</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Título -->
                    </span>
                    <input type="text" id="titulo" name="titulo" required oninput="validateField(this)" placeholder="Título" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-titulo" class="error-message"></div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_de_nacimiento" class="block text-sm">Fecha de Nacimiento</label>
                <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" required oninput="validateField(this)" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                <div id="error-fecha_de_nacimiento" class="error-message"></div>
            </div>

            <!-- Dirección -->
            <div>
                <label for="direccion" class="block text-sm">Dirección</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Dirección -->
                    </span>
                    <input type="text" id="direccion" name="direccion" required oninput="validateField(this)" placeholder="Dirección" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <!-- EPS -->
            <div>
                <label for="eps" class="block text-sm">EPS</label>
                <select id="eps" name="eps" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una EPS</option>
                    <option value="EPS1">EPS 1</option>
                    <option value="EPS2">EPS 2</option>
                    <option value="EPS3">EPS 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-eps" class="error-message"></div>
            </div>

            <!-- Contraseña -->
            <div>
                <label for="contrasena" class="block text-sm">Contraseña</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Contraseña -->
                    </span>
                    <input type="password" id="contrasena" name="contrasena" required minlength="8" oninput="validateField(this)" placeholder="Contraseña" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-contrasena" class="error-message"></div>
            </div>

            <!-- Pensión -->
            <div>
                <label for="pension" class="block text-sm">Pensión</label>
                <select id="pension" name="pension" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una Pensión</option>
                    <option value="Pension1">Pensión 1</option>
                    <option value="Pension2">Pensión 2</option>
                    <option value="Pension3">Pensión 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-pension" class="error-message"></div>
            </div>

            <!-- Caja Comp -->
            <div>
                <label for="caja_comp" class="block text-sm">Caja Compensación</label>
                <select id="caja_comp" name="caja_comp" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una Caja de Compensación</option>
                    <option value="Caja1">Caja 1</option>
                    <option value="Caja2">Caja 2</option>
                    <option value="Caja3">Caja 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-caja_comp" class="error-message"></div>
            </div>

            <!-- Documentos -->
            <div>
                <label for="documentos" class="block text-sm">Documentos</label>
                <input type="file" id="documentos" name="documentos" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                <div id="error-documentos" class="error-message"></div>
            </div>

            <!-- Botón de Envío -->
            <div class="col-span-full">
                <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
            </div>
        </div>
    </form>
</div>

<!-- Validación de Formularios con JavaScript -->
<script>
    document.getElementById('registroDocenteForm').addEventListener('submit', function(event) {
        const fields = ['nombre', 'apellido', 'cedula', 'correo', 'celular', 'sexo', 'titulo', 'fecha_de_nacimiento', 'direccion', 'eps', 'contrasena', 'pension', 'caja_comp'];
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
        if (input.validity.typeMismatch) {
            if (input.type === 'email') {
                return 'introduce un correo electrónico válido.';
            }
        }
        if (input.validity.patternMismatch) {
            if (input.id === 'nombre' || input.id === 'apellido') {
                return 'Solo se permiten letras y espacios.';
            }
            if (input.id === 'cedula') {
                return 'Solo se permiten números.';
            }
            if (input.id === 'celular') {
                return 'El número de celular debe tener 10 dígitos.';
            }
        }
        if (input.validity.tooShort) {
            if (input.id === 'contrasena') {
                return 'La contraseña debe tener al menos 8 caracteres.';
            }
        }
        return 'El valor introducido no es válido.';
    }
</script>

<!-- Modal -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box min-w-max">
        <h3 class="text-lg font-bold">Ver/Editar Docentes</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>
        
        <?php
        // Consultar los datos de la tabla docentes
        $sql = "SELECT * FROM docentes";
        $result = $conn->query($sql);

        // Verificar si hay registros
        $docentes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $docentes[] = $row;
            }
        }

        $conn->close();
        ?>

        <!-- Input de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar..." class="input input-bordered w-full max-w-xs my-4">

        <?php if (count($docentes) > 0) : ?>
            <div class="overflow-x-auto">
                <table id="docentes-table" class="table table-xs">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Celular</th>
                            <th>Título</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                            <th>EPS</th>
                            <th>Pensión</th>
                            <th>Caja Compensación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="docentes-table-body">
                        <?php foreach ($docentes as $docente) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($docente['id']); ?></td>
                                <td><?php echo htmlspecialchars($docente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($docente['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($docente['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($docente['correo']); ?></td>
                                <td><?php echo htmlspecialchars($docente['celular']); ?></td>
                                <td><?php echo htmlspecialchars($docente['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($docente['fecha_de_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($docente['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($docente['eps']); ?></td>
                                <td><?php echo htmlspecialchars($docente['pension']); ?></td>
                                <td><?php echo htmlspecialchars($docente['caja_comp']); ?></td>
                                <td class="actions">
                                    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                        <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                            <a href="editar_docentes?id=<?php echo $docente['id']; ?>" class="btn hover:underline edit me-2 mb-2">Editar</a>
                                            <a href="borrar_docentes?id=<?php echo $docente['id']; ?>" class="btn text-red-500 hover:underline me-2 mb-2 delete" onclick="return confirm('¿Está seguro de que desea eliminar este docente?');">Borrar</a>
                                        <?php else : ?>
                                            <a href="editar_docentes?id=<?php echo $docente['id']; ?>" class="btn edit">Editar</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div id="pagination" class="mt-4">
                <button id="prevPage" class="btn">Anterior</button>
                <span id="pageInfo" class="mx-4"></span>
                <button id="nextPage" class="btn">Siguiente</button>
            </div>
        <?php else : ?>
            <p>No hay docentes registrados.</p>
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
        const tableBody = document.getElementById('docentes-table-body');
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
