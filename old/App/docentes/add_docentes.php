<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
include_once __DIR__ . '/../config/database.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout'; // Redirige a la página de inicio de sesión o a donde desees
          </script>";
    exit();
}

// Obtener la lista de docentes
$docentes_sql = "SELECT id, nombre, apellido FROM docentes";
$docentes_result = $conn->query($docentes_sql);

// Obtener la lista de grupos
$grupos_sql = "SELECT id, nombre_grupo FROM grupos";
$grupos_result = $conn->query($grupos_sql);

// Obtener la lista de materias
$materias_sql = "SELECT id, materia FROM materias";
$materias_result = $conn->query($materias_sql);
?>

<div class="h-screen flex justify-center items-center my-64 md:my-0">
    <div class="w-full max-w-3xl">
        <h1 class="mb-8 text-2xl font-semibold text-center">Asignar Docentes a Grupos y Materias</h1>

        <form id="asignarDocentesForm" method="POST" action="guardar_asignacion">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Docente -->
                <div>
                    <label for="docente_id" class="block text-sm">Seleccione un docente:</label>
                    <select name="docente_id" id="docente_id" required class="block w-full mt-2 py-2.5 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <option value="">-- Seleccione un docente --</option>
                        <?php while ($docente = $docentes_result->fetch_assoc()): ?>
                            <option value="<?php echo $docente['id']; ?>"><?php echo $docente['nombre'] . ' ' . $docente['apellido']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-docente_id" class="error-message text-red-500 text-sm mt-1"></div>
                </div>

                <!-- Grupo -->
                <div>
                    <label for="grupo_id" class="block text-sm">Seleccione un grupo:</label>
                    <select name="grupo_id" id="grupo_id" required class="block w-full mt-2 py-2.5 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <option value="">-- Seleccione un grupo --</option>
                        <?php while ($grupo = $grupos_result->fetch_assoc()): ?>
                            <option value="<?php echo $grupo['id']; ?>"><?php echo $grupo['nombre_grupo']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-grupo_id" class="error-message text-red-500 text-sm mt-1"></div>
                </div>

                <!-- Materias -->
                <div class="col-span-full">
                    <label for="materia_id" class="block text-sm">Seleccione una o más materias:</label>
                    <select name="materia_id[]" id="materia_id" multiple required class="block w-full mt-2 py-2.5 border rounded-lg focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <?php while ($materia = $materias_result->fetch_assoc()): ?>
                            <option value="<?php echo $materia['id']; ?>"><?php echo $materia['materia']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div id="error-materia_id" class="error-message text-red-500 text-sm mt-1"></div>
                </div>
            </div>

            <!-- Botón de Envío -->
            <div class="flex justify-center mt-6">
                <button type="submit" class="w-60 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600">Guardar Asignación</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluye Select2 para mejorar la selección múltiple -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#materia_id').select2({
            placeholder: "Seleccione una o más materias",
            width: '100%'
        });

        // Validación del formulario
        $('#asignarDocentesForm').submit(function(event) {
            var isValid = true;

            if ($('#docente_id').val() === '') {
                $('#error-docente_id').text('Por favor, selecciona un docente.');
                isValid = false;
            } else {
                $('#error-docente_id').text('');
            }

            if ($('#grupo_id').val() === '') {
                $('#error-grupo_id').text('Por favor, selecciona un grupo.');
                isValid = false;
            } else {
                $('#error-grupo_id').text('');
            }

            if ($('#materia_id').val() === null || $('#materia_id').val().length === 0) {
                $('#error-materia_id').text('Por favor, selecciona al menos una materia.');
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
// Cerrar la conexión
$conn->close();
include_once __DIR__ . '/../footer.php';
?>
