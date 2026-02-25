<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Consultar los grupos y materias desde la base de datos
$sql_grupos = "SELECT id, nombre_grupo FROM grupos";
$result_grupos = $conn->query($sql_grupos);

$sql_materias = "SELECT id, materia FROM materias";
$result_materias = $conn->query($sql_materias);
?>

<div class="h-screen flex justify-center items-center my-64 md:my-0">
    <div class="w-full max-w-3xl">
        <h1 class="mb-8 text-2xl font-semibold text-center">Asignar Materias a Grupos</h1>

        <!-- Formulario de Asignación -->
        <form id="asignarMateriasForm" method="post" action="procesar_asignacion_materias">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Grupo -->
                <div>
                    <label for="grupo_id" class="block text-sm">Grupo</label>
                    <select id="grupo_id" name="grupo_id" required class="block w-full mt-2 py-2.5 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <option value="">Selecciona un grupo</option>
                        <?php while ($row_grupo = $result_grupos->fetch_assoc()) : ?>
                            <option value="<?php echo $row_grupo['id']; ?>"><?php echo $row_grupo['nombre_grupo']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-grupo_id" class="error-message text-red-500 text-sm mt-1"></div>
                </div>

                <!-- Materia -->
                <div>
                    <label for="materia_id" class="block text-sm">Materia</label>
                    <select id="materia_id" name="materia_id" required class="block w-full mt-2 py-2.5 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <option value="">Selecciona una materia</option>
                        <?php while ($row_materia = $result_materias->fetch_assoc()) : ?>
                            <option value="<?php echo $row_materia['id']; ?>"><?php echo $row_materia['materia']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-materia_id" class="error-message text-red-500 text-sm mt-1"></div>
                </div>
            </div>

            <!-- Botón de Envío -->
            <div class="flex justify-center mt-6">
                <button type="submit" class="w-60 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600">Asignar Materia</button>
            </div>
        </form>

        <!-- Tabla de Materias Asignadas -->
        <h2 class="mt-10 mb-4 text-xl font-semibold text-center">Materias Asignadas</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Materia</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="materias_asignadas">
                    <tr>
                        <td colspan="2" class="text-center py-4">Selecciona un grupo primero</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#grupo_id').change(function() {
            var grupoId = $(this).val();
            if (grupoId) {
                $.ajax({
                    type: 'POST',
                    url: 'obtener_materias',
                    data: {
                        grupo_id: grupoId
                    },
                    success: function(response) {
                        $('#materias_asignadas').html(response);
                    },
                    error: function() {
                        $('#materias_asignadas').html('<tr><td colspan="2" class="text-center py-4">Error al obtener las materias</td></tr>');
                    }
                });
            } else {
                $('#materias_asignadas').html('<tr><td colspan="2" class="text-center py-4">Selecciona un grupo primero</td></tr>');
            }
        });

        $(document).on('click', '.borrar-materia', function() {
            var materiaId = $(this).data('id');
            var grupoId = $('#grupo_id').val();
            if (confirm('¿Estás seguro de que deseas eliminar esta materia del grupo?')) {
                $.ajax({
                    type: 'POST',
                    url: 'borrar_asig_materias',
                    data: {
                        materia_id: materiaId,
                        grupo_id: grupoId
                    },
                    success: function(response) {
                        $('#grupo_id').trigger('change');
                    },
                    error: function() {
                        alert('Error al eliminar la materia.');
                    }
                });
            }
        });

        // Validación del formulario
        $('#asignarMateriasForm').submit(function(event) {
            var isValid = true;

            if ($('#grupo_id').val() === '') {
                $('#error-grupo_id').text('Por favor, selecciona un grupo.');
                isValid = false;
            } else {
                $('#error-grupo_id').text('');
            }

            if ($('#materia_id').val() === '') {
                $('#error-materia_id').text('Por favor, selecciona una materia.');
                isValid = false;
            } else {
                $('#error-materia_id').text('');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>

<?php
include_once __DIR__ . '/../footer.php';
?>
