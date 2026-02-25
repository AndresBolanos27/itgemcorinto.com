<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
?>

<div class="h-screen w-11/12 mx-auto flex justify-center items-center  md:my-0">
    <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>

        <form id="registroForm" action="procesar_categorias" method="post">

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

            <h1 class="mb-8 text-2xl font-semibold">Registro de Categorias</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm" for="categoria">Nombre de la Categoría:</label>
                    <div class="relative flex items-center mt-2">
                        <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="categoria" name="categoria" required>
                         <option value="Norma">Norma</option>
<option value="Recuperatorio">Recuperatorio</option>
<option value="Diplomado">Diplomado</option>
<option value="Seminario">Seminario</option>
<option value="Supletorio">Supletorio</option>
<option value="Transversales">Transversales</option>
<option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div id="error-categoria" class="error-message"></div>
                </div>

                <div class="col-span-full">
                    <input type="submit" value="Registrar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                </div>
            </div>
        </form>
</div>


<!-- Modal -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box min-w-max	 mx-auto">
        <h3 class="text-lg font-bold">Editar</h3>
        <p class="py-4">Presiona la tecla ESC o haz clic en el botón de abajo para cerrar</p>



    <?php else : ?>
        <p>No tienes permisos para registrar categorías.</p>
    <?php endif; ?>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>

  

    <?php

    // Generar un token único y almacenarlo en la sesión
    $_SESSION['download_token'] = bin2hex(random_bytes(32));

    // Consultar los datos de la tabla categorias
    $sql = "SELECT * FROM categorias";
    $result = $conn->query($sql);

    // Verificar si hay registros
    if ($result->num_rows > 0) {
        // Almacenar los datos en un array
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    } else {
        $categorias = [];
    }

    $conn->close();
    ?>


    <?php if (count($categorias) > 0) : ?>
        <table id="categorias">
            <thead>
                <tr>
                    <th>Nombre de la Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria['categoria']); ?></td>
                        <td class="actions">
                            <?php if ($_SESSION['usuario_rol'] !== 'auxiliar') : ?>
                                <?php if ($_SESSION['usuario_rol'] !== 'secretaria') : ?>
                                  
                                    <a href="borrar_categorias?id=<?php echo $categoria['id']; ?>" class="btn delete" onclick="return confirm('¿Está seguro de que desea eliminar esta categoría?');">Borrar</a>
                                <?php else : ?>
                                    
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No hay categorías registradas.</p>
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