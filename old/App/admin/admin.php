    <?php
    include_once __DIR__ . '/../header.php';
    include_once __DIR__ . '/../config/database.php';
    include_once __DIR__ . '/../config/verificar_sesion.php';
    verificar_sesion();

    // Generar un token único y almacenarlo en la sesión
    $_SESSION['download_token'] = bin2hex(random_bytes(32));
    ?>



    <div class="h-screen flex justify-center items-center  my-64 md:my-0">

        <form id="registroForm" action="procesar_registro_admin" method="post">
            <!--  -->
            <div class="lg:flex lg:items-center lg:justify-between">

                <div class=" flex mb-5  lg:mt-0">
                    <!-- Botón personalizado que abre el modal -->
                    <span>
                        <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-inset ring-gray-300 hover:bg-gray-50" onclick="my_modal_5.showModal()">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                            </svg>
                            Ver/Editar
                        </button>
                    </span>



                    <div class="lg:flex lg:items-center lg:justify-between">
                        <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>

                            <div class="flex mb-5 lg:mt-0">
                                <!-- Botón para Exportar a Excel -->
                                <span class="ml-3">
                                    <a href="exportaradminexel?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-green-500">
                                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
                                            <path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
                                        </svg>
                                        Excel
                                    </a>
                                </span>

                                <!-- Botón para Exportar a PDF -->
                                <span class="ml-3">
                                    <a href="exportaradminpdf?token=<?php echo $_SESSION['download_token']; ?>" class="inline-flex items-center rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset ring-gray-300 hover:bg-red-400">
                                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
                                            <path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
                                        </svg>
                                        PDF
                                    </a>
                                </span>
                            </div>
                    </div>
                <?php endif; ?>


                </div>
            </div>

            <!--  -->
            <h1 class="mb-8 text-2xl font-semibold">Registro de Administradores</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm">Nombre</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                            </svg>

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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V6.75a3 3 0 0 0-3-3h-15Zm4.125 3a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Zm-3.873 8.703a4.126 4.126 0 0 1 7.746 0 .75.75 0 0 1-.351.92 7.47 7.47 0 0 1-3.522.877 7.47 7.47 0 0 1-3.522-.877.75.75 0 0 1-.351-.92ZM15 8.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15ZM14.25 12a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3.75a.75.75 0 0 0 0-1.5H15Z" clip-rule="evenodd" />
                            </svg>

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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm-1.4 4.25L12 13 5.4 8.25V7l6.6 4.15L18.6 7v1.25z" />
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path d="M10.5 18.75a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" />
                                <path fill-rule="evenodd" d="M8.625.75A3.375 3.375 0 0 0 5.25 4.125v15.75a3.375 3.375 0 0 0 3.375 3.375h6.75a3.375 3.375 0 0 0 3.375-3.375V4.125A3.375 3.375 0 0 0 15.375.75h-6.75ZM7.5 4.125C7.5 3.504 8.004 3 8.625 3H9.75v.375c0 .621.504 1.125 1.125 1.125h2.25c.621 0 1.125-.504 1.125-1.125V3h1.125c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-6.75A1.125 1.125 0 0 1 7.5 19.875V4.125Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input type="tel" id="celular" name="celular" pattern="\d{10}" oninput="validateField(this)" placeholder="Celular" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    </div>
                    <div id="error-celular" class="error-message"></div>
                </div>

                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm">Título</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <!-- Icono para Título -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path d="M5 7h14V5H5v2zm0 4h14v-2H5v2zm0 4h14v-2H5v2zm0 4h14v-2H5v2z" />
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path d="M19.006 3.705a.75.75 0 1 0-.512-1.41L6 6.838V3a.75.75 0 0 0-.75-.75h-1.5A.75.75 0 0 0 3 3v4.93l-1.006.365a.75.75 0 0 0 .512 1.41l16.5-6Z" />
                                <path fill-rule="evenodd" d="M3.019 11.114 18 5.667v3.421l4.006 1.457a.75.75 0 1 1-.512 1.41l-.494-.18v8.475h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3v-9.129l.019-.007ZM18 20.25v-9.566l1.5.546v9.02H18Zm-9-6a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75H9Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input type="text" id="direccion" name="direccion" required oninput="validateField(this)" placeholder="Dirección" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    </div>
                    <div id="error-direccion" class="error-message"></div>
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="contrasena" class="block text-sm">Contraseña</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <!-- Icono para Contraseña -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input type="password" id="contrasena" name="contrasena" required minlength="8" oninput="validateField(this)" placeholder="Contraseña" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    </div>
                    <div id="error-contrasena" class="error-message"></div>
                </div>

                <!-- Rol -->
                <div>
                    <label for="rol" class="block text-sm">Rol</label>
                    <select id="rol" name="rol" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                        <option value="admin">Admin</option>
                        <option value="auxiliar">Auxiliar</option>
                        <option value="directora_rectora">Directora/Rectora</option>
                        <option value="secretaria">Secretaria</option>
                    </select>
                    <div id="error-rol" class="error-message"></div>
                </div>

                <!-- Botón de Envío -->
                <div class="col-span-full">
                    <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                </div>
            </div>
        </form>
    </div>




    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            const fields = ['nombre', 'apellido', 'cedula', 'correo', 'celular', 'titulo', 'fecha_de_nacimiento', 'direccion', 'contrasena'];
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
        <h3 class="text-lg font-bold">Editar</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>
        
        <?php
        // Consultar los datos de la tabla admin
        $sql = "SELECT * FROM admin";
        $result = $conn->query($sql);

        // Verificar si hay registros
        if ($result->num_rows > 0) {
            // Almacenar los datos en un array
            $admins = [];
            while ($row = $result->fetch_assoc()) {
                $admins[] = $row;
            }
        } else {
            $admins = [];
        }

        $conn->close();
        ?>

        <!-- Input de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar..." class="input input-bordered w-full max-w-xs my-4">

        <?php if (count($admins) > 0) : ?>
            <div class="overflow-x-auto">
                <table id="admin-table" class="table table-xs">
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
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="admin-table-body">
                        <?php foreach ($admins as $admin) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                <td><?php echo htmlspecialchars($admin['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($admin['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($admin['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($admin['correo']); ?></td>
                                <td><?php echo htmlspecialchars($admin['celular']); ?></td>
                                <td><?php echo htmlspecialchars($admin['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($admin['fecha_de_nacimiento']); ?></td>
                                <td><?php echo htmlspecialchars($admin['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($admin['rol']); ?></td>
                                <td class="actions">
                                    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                        <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                            <a href="editar_admin?id=<?php echo $admin['id']; ?>" class="btn hover:underline edit me-2 mb-2">Editar</a>
                                            <a href="borrar_admin?id=<?php echo $admin['id']; ?>" class="btn text-red-500 hover:underline me-2 mb-2 delete" onclick="return confirm('¿Está seguro de que desea eliminar este administrador?');">Borrar</a>
                                        <?php else : ?>
                                            <a href="editar_admin?id=<?php echo $admin['id']; ?>" class="btn edit">Editar</a>
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
            <p>No hay administradores registrados.</p>
        <?php endif; ?>

        <div class="modal-action">
            <form method="dialog">
                <!-- El botón dentro del formulario cierra el modal -->
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 5;
        let currentPage = 1;
        const tableBody = document.getElementById('admin-table-body');
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const pageInfo = document.getElementById('pageInfo');
        const searchInput = document.getElementById('searchInput');

        function updateTable() {
            const filter = searchInput.value.toLowerCase();
            const filteredRows = rows.filter(row => {
                const cells = row.getElementsByTagName('td');
                return Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
            });

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedRows = filteredRows.slice(start, end);

            tableBody.innerHTML = '';
            paginatedRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));

            pageInfo.textContent = `Página ${currentPage} de ${Math.ceil(filteredRows.length / rowsPerPage)}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === Math.ceil(filteredRows.length / rowsPerPage);
        }

        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage < Math.ceil(rows.length / rowsPerPage)) {
                currentPage++;
                updateTable();
            }
        });

        searchInput.addEventListener('input', () => {
            currentPage = 1;
            updateTable();
        });

        updateTable();
    });
</script>

    <?php
    include_once __DIR__ . '/../footer.php';
    ?>