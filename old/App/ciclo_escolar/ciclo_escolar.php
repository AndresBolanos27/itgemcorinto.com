<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
?>


<div class="h-screen w-11/12 mx-auto flex justify-center items-center  md:my-0">

    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>

        <form id="registroForm" action="procesar_cicloescolar" method="post">
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

            <h1 class="mb-8 text-2xl font-semibold">Registro de Ciclo Academico</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm" for="nombre_ciclo">Nombre del Ciclo:</label>
                    <div class="relative flex flex-col  items-center mt-2">
                        <div class="relative flex   items-center">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                                </svg>

                            </span>
                            <input class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="text" id="nombre_ciclo" name="nombre_ciclo" required pattern="[A-Za-z0-9\s]+" oninput="validateField(this)">
                        </div>
                        <div id="error-nombre_ciclo" class="error-message"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm" for="fecha_inicio">Fecha de Inicio:</label>
                    <div class="relative flex items-center mt-2">
                        <span class="absolute">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                            </svg>

                        </span>
                        <input class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="date" id="fecha_inicio" name="fecha_inicio" required oninput="validateField(this)">
                        <div id="error-fecha_inicio" class="error-message"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm" for="fecha_fin">Fecha de Fin:</label>
                    <div class="relative flex items-center mt-2">

                        <input class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="date" id="fecha_fin" name="fecha_fin" required oninput="validateField(this)">
                        <div id="error-fecha_fin" class="error-message"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm" for="estado">Estado:</label>
                    <div class="relative flex items-center mt-2">

                        <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="estado" name="estado" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                        <div id="error-estado" class="error-message"></div>
                    </div>
                </div>
            </div>
            <!-- Botón de Envío -->
            <div class="col-span-full">
                <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
            </div>
        </form>
</div>
<?php else : ?>
    <p>No tienes permisos para registrar ciclos escolares.</p>
<?php endif; ?>
<style>
    .error-message {
        color: red;
        font-size: 0.9em;
    }
</style>

<script>
    document.getElementById('registroForm').addEventListener('submit', function(event) {
        const fields = ['nombre_ciclo', 'fecha_inicio', 'fecha_fin', 'estado'];
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
            if (input.id === 'nombre_ciclo') {
                return 'Solo se permiten letras y espacios.';
            }
        }
        return 'El valor introducido no es válido.';
    }
</script>






<!-- Modal -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box min-w-max	 mx-auto">
        <h3 class="text-lg font-bold">Editar</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>

        <!--  -->
        <?php

        // Generar un token único y almacenarlo en la sesión
        $_SESSION['download_token'] = bin2hex(random_bytes(32));

        // Consultar los datos de la tabla ciclos_escolares
        $sql = "SELECT * FROM ciclos_escolares";
        $result = $conn->query($sql);

        // Verificar si hay registros
        if ($result->num_rows > 0) {
            // Almacenar los datos en un array
            $ciclos = [];
            while ($row = $result->fetch_assoc()) {
                $ciclos[] = $row;
            }
        } else {
            $ciclos = [];
        }

        $conn->close();
        ?>

        <h1>Lista de Ciclos Escolares</h1>

        <?php if (count($ciclos) > 0) : ?>
            <table id="ciclos_escolares">
                <thead>
                    <tr>
                        <th>Nombre del Ciclo</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ciclos as $ciclo) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ciclo['nombre_ciclo']); ?></td>
                            <td><?php echo htmlspecialchars($ciclo['fecha_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($ciclo['fecha_fin']); ?></td>
                            <td><?php echo htmlspecialchars($ciclo['estado']); ?></td>
                            <td class="actions">
                                <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                    <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                        <a href="editar_cicloescolar?id=<?php echo $ciclo['id']; ?>" class="btn edit">Editar</a>
                                        <a href="borrar_cicloescolar?id=<?php echo $ciclo['id']; ?>" class="btn delete" onclick="return confirm('¿Está seguro de que desea eliminar este ciclo escolar?');">Borrar</a>
                                    <?php else : ?>
                                        <a href="editar_cicloescolar?id=<?php echo $ciclo['id']; ?>" class="btn edit">Editar</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No hay ciclos escolares registrados.</p>
        <?php endif; ?>


        <!--  -->
        <div class="modal-action">
            <form method="dialog">
                <!-- El botón dentro del formulario cierra el modal -->
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>


<?php
include_once __DIR__ . '/../footer.php';
?>