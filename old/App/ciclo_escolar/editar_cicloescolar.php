<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();


// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora' && $_SESSION['usuario_rol'] !== 'secretaria') {
    echo "<script>
              alert('No tienes permiso para acceder a esta página');
              window.location.href = 'cicloescolar';
          </script>";
    exit();
}

$id = $_GET['id'];

// Consultar el registro actual
$sql = "SELECT * FROM ciclos_escolares WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ciclo = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre_ciclo = $_POST['nombre_ciclo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado = $_POST['estado'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar los datos en la tabla ciclos_escolares
        $sql_ciclo = "UPDATE ciclos_escolares SET nombre_ciclo=?, fecha_inicio=?, fecha_fin=?, estado=? WHERE id=?";
        $stmt_ciclo = $conn->prepare($sql_ciclo);
        $stmt_ciclo->bind_param("ssssi", $nombre_ciclo, $fecha_inicio, $fecha_fin, $estado, $id);
        $stmt_ciclo->execute();

        // Si la actualización fue exitosa, confirmamos la transacción
        $conn->commit();

        echo "<script>
                alert('Ciclo escolar actualizado correctamente');
                window.location.href = 'cicloescolar';
              </script>";
    } catch (Exception $e) {
        // Si algo falla, revertimos la transacción
        $conn->rollback();
        echo "<script>
                alert('Error al actualizar el ciclo escolar: " . $e->getMessage() . "');
                window.location.href = 'editar_cicloescolar?id=$id';
              </script>";
    }

    $stmt_ciclo->close();
    $conn->close();
}
?>





<div class="h-screen w-11/12 mx-auto flex justify-center items-center  md:my-0">
    <form action="editar_cicloescolar?id=<?php echo $id; ?>" method="post">

        
        <h1 class="mb-8 text-2xl font-semibold">Editar Ciclo Academico</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

            <div>
                <label class="block text-sm" for="nombre_ciclo">Nombre del Ciclo:</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" clip-rule="evenodd" />
                        </svg>

                    </span>
                    <input value="<?php echo htmlspecialchars($ciclo['nombre_ciclo']); ?>" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="text" id="nombre_ciclo" name="nombre_ciclo" required pattern="[A-Za-z\s]+" oninput="validateField(this)">
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
                    <input value="<?php echo htmlspecialchars($ciclo['fecha_inicio']); ?>" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="date" id="fecha_inicio" name="fecha_inicio" required oninput="validateField(this)">
                    <div id="error-fecha_inicio" class="error-message"></div>
                </div>
            </div>

            <div>
                <label class="block text-sm" for="fecha_fin">Fecha de Fin:</label>
                <div class="relative flex items-center mt-2">

                    <input value="<?php echo htmlspecialchars($ciclo['fecha_fin']); ?>" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" type="date" id="fecha_fin" name="fecha_fin" required oninput="validateField(this)">
                    <div id="error-fecha_fin" class="error-message"></div>
                </div>
            </div>

            <div>
                <label class="block text-sm" for="estado">Estado:</label>
                <div class="relative flex items-center mt-2">

                    <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="estado" name="estado" required>
                    <option value="activo" <?php echo $ciclo['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo $ciclo['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                    <div id="error-estado" class="error-message"></div>
                </div>
            </div>
        </div>
        <!-- Botón de Envío -->
        <div class="col-span-full">
            <input type="submit" value="Actualizar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
        </div>
    </form>
</div>


<?php
include_once __DIR__ . '/../footer.php';
?>