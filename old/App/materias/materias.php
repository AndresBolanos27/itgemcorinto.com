<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
// Consultar las categorías desde la base de datos
$sql_categorias = "SELECT id, categoria FROM categorias";
$result_categorias = $conn->query($sql_categorias);
?>

<div class="w-11/12 h-full mx-auto flex  justify-center items-center  md:my-10">
    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>

        <form method="post" action="procesar_materias">

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
                </div>
            </div>
            <!--  -->


            <h1 class="mb-8 text-2xl font-semibold">Registro de Materias </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm" for="materia">Materia:</label>
                    <input  class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"  type="text" id="materia" name="materia" required>
                </div>

                <div>
                    <label class="block text-sm" for="categoria_id">Categoría:</label>
                    <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2"  id="categoria_id" name="categoria_id" required>
                        <?php
                        // Obtener las categorías desde la base de datos
                        $sql_categorias = "SELECT id, categoria FROM categorias";
                        $result_categorias = $conn->query($sql_categorias);

                        while ($row = $result_categorias->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['categoria'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-span-full">
                    <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                </div>
        </form>

    <?php else : ?>
        <p>No tienes permisos para registrar materias.</p>
    <?php endif; ?>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>

    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            const fields = ['materia', 'categoria'];
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
                if (input.id === 'materia') {
                    return 'Solo se permiten letras y espacios.';
                }
            }
            return 'El valor introducido no es válido.';
        }
    </script>
    <?php
    // Incluir el archivo de conexión a la base de datos
    include_once __DIR__ . '/../config/database.php';
    // Generar un token único y almacenarlo en la sesión
    $_SESSION['download_token'] = bin2hex(random_bytes(32));
    // Consultar los datos de la tabla materias
    $sql = "SELECT materias.materia, categorias.categoria, materias.id 
        FROM materias 
        JOIN categorias ON materias.categoria_id = categorias.id";
    $result = $conn->query($sql);
    // Verificar si hay registros
    if ($result->num_rows > 0) {
        // Almacenar los datos en un array
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }
    } else {
        $materias = [];
    }

    $conn->close();
    ?>

</div>
</div>



<!-- Modal -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box min-w-max	 mx-auto">
        <h3 class="text-lg font-bold">Editar</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>


        <?php if (count($materias) > 0) : ?>
            <table id="materias">
                <thead>
                    <tr>
                        <th>Nombre de la Materia</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $materia) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($materia['materia']); ?></td>
                            <td><?php echo htmlspecialchars($materia['categoria']); ?></td>
                            <td class="actions">
                                <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                    <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                        <a href="editar_materias?id=<?php echo $materia['id']; ?>" class="btn edit">Editar</a>
                                        <a href="borrar_materias?id=<?php echo $materia['id']; ?>" class="btn delete" onclick="return confirm('¿Está seguro de que desea eliminar esta materia?');">Borrar</a>
                                    <?php else : ?>
                                        <a href="editar_ma  terias?id=<?php echo $materia['id']; ?>" class="btn edit">Editar</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No hay materias registradas.</p>
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