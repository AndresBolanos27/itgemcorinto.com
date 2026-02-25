<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

// Generar un token único y almacenarlo en la sesión
$_SESSION['download_token'] = bin2hex(random_bytes(32));

// Conectar a la base de datos y obtener los grupos activos
$sql_grupos = "SELECT id, nombre_grupo FROM grupos WHERE estado = 'activo'";
$result_grupos = $conn->query($sql_grupos);

// Inicializar el array de grupos
$grupos = [];

if ($result_grupos->num_rows > 0) {
    while ($row = $result_grupos->fetch_assoc()) {
        $grupos[] = $row;
    }
}
?>

<div class="h-screen flex justify-center items-center p-6 my-64 md:my-0">
    <form id="registroEstudianteForm" action="procesar_estudiante" method="post" enctype="multipart/form-data">
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
                            <a href="exportarestudiantesexcel.php?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-green-500">
                                <!-- Icono -->
                                Excel
                            </a>
                        </span>

                        <!-- Botón para Exportar a PDF -->
                        <span class="ml-3">
                            <a href="exportarestudiantespdf.php?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-red-400">
                                <!-- Icono -->
                                PDF
                            </a>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h1 class="mb-8 text-2xl font-semibold">Registro de Estudiantes</h1>

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

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_de_nacimiento" class="block text-sm">Fecha de Nacimiento</label>
                <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento"  oninput="validateField(this)" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
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

            <!-- Grupo -->
            <div>
                <label for="grupo" class="block text-sm">Grupo</label>
                <select id="grupo" name="grupo" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione un grupo</option>
                    <?php foreach ($grupos as $grupo) : ?>
                        <option value="<?php echo htmlspecialchars($grupo['id']); ?>">
                            <?php echo htmlspecialchars($grupo['nombre_grupo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="error-grupo" class="error-message"></div>
            </div>

            <!-- Grupo Étnico -->
            <div>
                <label for="grupo_etnico" class="block text-sm">Grupo Étnico</label>
                <select id="grupo_etnico" name="grupo_etnico" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione un grupo étnico</option>
                    <option value="GrupoEtnico1">Grupo Étnico 1</option>
                    <option value="GrupoEtnico2">Grupo Étnico 2</option>
                    <option value="GrupoEtnico3">Grupo Étnico 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-grupo_etnico" class="error-message"></div>
            </div>

            <!-- Acudiente -->
            <div>
                <label for="acudiente" class="block text-sm">Acudiente</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="text" id="acudiente" name="acudiente"  oninput="validateField(this)" placeholder="Acudiente" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-acudiente" class="error-message"></div>
            </div>

            <!-- Número de Acudiente -->
            <div>
                <label for="numero_acudiente" class="block text-sm">Número de Acudiente</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="tel" id="numero_acudiente" name="numero_acudiente" pattern="\d{10}" oninput="validateField(this)" placeholder="Número de Acudiente" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-numero_acudiente" class="error-message"></div>
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
    document.getElementById('registroEstudianteForm').addEventListener('submit', function(event) {
        const fields = ['nombre', 'apellido', 'cedula', 'correo', 'celular', 'sexo', 'fecha_de_nacimiento', 'direccion', 'eps', 'contrasena', 'grupo', 'grupo_etnico', 'acudiente', 'numero_acudiente'];
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
                return 'Por favor, introduce una dirección de correo electrónico válida.';
            }
        }
        if (input.validity.patternMismatch) {
            if (input.id === 'nombre' || input.id === 'apellido' || input.id === 'acudiente') {
                return 'Solo se permiten letras y espacios.';
            }
            if (input.id === 'cedula') {
                return 'Solo se permiten números.';
            }
            if (input.id === 'celular' || input.id === 'numero_acudiente') {
                return 'El número debe tener 10 dígitos.';
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
        <h3 class="text-lg font-bold">Ver/Editar Estudiantes</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>
        
        <?php
        // Consultar los datos de la tabla estudiantes
        $sql = "SELECT * FROM estudiantes";
        $result = $conn->query($sql);

        // Verificar si hay registros
        $estudiantes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estudiantes[] = $row;
            }
        }

        $conn->close();
        ?>

        <!-- Input de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar..." class="input input-bordered w-full max-w-xs my-4">

        <?php if (count($estudiantes) > 0) : ?>
            <div class="overflow-x-auto">
                <table id="estudiantes-table" class="table table-xs">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Celular</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                            <th>Grupo Étnico</th>
                            <th>Acudiente</th>
                            <th>Número Acudiente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="estudiantes-table-body">
                        <?php foreach ($estudiantes as $estudiante) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($estudiante['id']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['correo']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['celular']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['fecha_de_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['grupo_etnico']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['acudiente']); ?></td>
                                <td><?php echo htmlspecialchars($estudiante['numero_acudiente']); ?></td>
                                <td class="actions">
                                    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                        <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                            <a href="editar_estudiante?id=<?php echo $estudiante['id']; ?>" class="btn hover:underline edit me-2 mb-2">Editar</a>
                                            <a href="borrar_estudiantes?id=<?php echo $estudiante['id']; ?>" class="btn text-red-500 hover:underline me-2 mb-2 delete" onclick="return confirm('¿Está seguro de que desea eliminar este estudiante?');">Borrar</a>
                                        <?php else : ?>
                                            <a href="editar_estudiante?id=<?php echo $estudiante['id']; ?>" class="btn edit">Editar</a>
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
            <p>No hay estudiantes registrados.</p>
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
        const tableBody = document.getElementById('estudiantes-table-body');
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