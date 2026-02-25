<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora' && $_SESSION['usuario_rol'] !== 'secretaria') {
    echo "<script>
              alert('No tienes permiso para acceder a esta página');
              window.location.href = 'admin';
            </script>";
    exit();
}

$id = $_GET['id'];

// Consultar el registro actual
$sql = "SELECT * FROM docentes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$docente = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $sexo = $_POST['sexo'];
    $titulo = $_POST['titulo'];
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
    $direccion = $_POST['direccion'];
    $eps = $_POST['eps'];
    $pension = $_POST['pension'];
    $caja_comp = $_POST['caja_comp'];
    $fecha_registro = $docente['fecha_registro']; // Mantener la fecha de registro original

    // Verificar si la contraseña ha cambiado
    $contrasena = $docente['contrasena'];
    if (!empty($_POST['contrasena'])) {
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar los datos en la tabla docentes
        $sql_docente = "UPDATE docentes SET nombre=?, apellido=?, cedula=?, correo=?, celular=?, sexo=?, titulo=?, fecha_de_nacimiento=?, direccion=?, eps=?, contrasena=?, pension=?, caja_comp=?, fecha_registro=? WHERE id=?";
        $stmt_docente = $conn->prepare($sql_docente);
        $stmt_docente->bind_param("ssssssssssssssi", $nombre, $apellido, $cedula, $correo, $celular, $sexo, $titulo, $fecha_de_nacimiento, $direccion, $eps, $contrasena, $pension, $caja_comp, $fecha_registro, $id);
        $stmt_docente->execute();

        // Actualizar los datos en la tabla usuarios (asegurarse de que exista una relación)
        $sql_usuario = "UPDATE usuarios SET nombre=?, correo=?, contrasena=?, rol='docente' WHERE docente_id=?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("sssi", $nombre, $correo, $contrasena, $id);
        $stmt_usuario->execute();

        // Si ambas actualizaciones fueron exitosas, confirmamos la transacción
        $conn->commit();

        echo "<script>
                alert('Docente actualizado correctamente');
                window.location.href = 'docentes';
              </script>";
    } catch (Exception $e) {
        // Si algo falla, revertimos la transacción
        $conn->rollback();
        echo "<script>
                alert('Error al actualizar el docente: " . $e->getMessage() . "');
                window.location.href = 'editar_docente?id=$id';
              </script>";
    }

    $stmt_docente->close();
    $stmt_usuario->close();
    $conn->close();
}
?>

<div class="h-screen flex justify-center items-center p-6 my-64 md:my-0">
    <form id="editarDocenteForm" action="editar_docentes?id=<?php echo $id; ?>" method="post">
        <h1 class="mb-8 text-2xl font-semibold">Editar Docente</h1>

        <!-- Formulario de Edición -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($docente['nombre']); ?>" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($docente['apellido']); ?>" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($docente['cedula']); ?>" required pattern="\d+" oninput="validateField(this)" placeholder="Cédula" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($docente['correo']); ?>" required oninput="validateField(this)" placeholder="Correo" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="tel" id="celular" name="celular" value="<?php echo htmlspecialchars($docente['celular']); ?>" pattern="\d{10}" oninput="validateField(this)" placeholder="Celular" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-celular" class="error-message"></div>
            </div>

            <!-- Sexo -->
            <div>
                <label for="sexo" class="block text-sm">Sexo</label>
                <select id="sexo" name="sexo" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione Sexo</option>
                    <option value="M" <?php if ($docente['sexo'] == 'M') echo 'selected'; ?>>Masculino</option>
                    <option value="F" <?php if ($docente['sexo'] == 'F') echo 'selected'; ?>>Femenino</option>
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
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($docente['titulo']); ?>" required oninput="validateField(this)" placeholder="Título" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-titulo" class="error-message"></div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_de_nacimiento" class="block text-sm">Fecha de Nacimiento</label>
                <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" value="<?php echo htmlspecialchars($docente['fecha_de_nacimiento']); ?>" required oninput="validateField(this)" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                <div id="error-fecha_de_nacimiento" class="error-message"></div>
            </div>

            <!-- Dirección -->
            <div>
                <label for="direccion" class="block text-sm">Dirección</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Dirección -->
                    </span>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($docente['direccion']); ?>" required oninput="validateField(this)" placeholder="Dirección" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <!-- EPS -->
            <div>
                <label for="eps" class="block text-sm">EPS</label>
                <select id="eps" name="eps" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una EPS</option>
                    <option value="EPS1" <?php if ($docente['eps'] == 'EPS1') echo 'selected'; ?>>EPS 1</option>
                    <option value="EPS2" <?php if ($docente['eps'] == 'EPS2') echo 'selected'; ?>>EPS 2</option>
                    <option value="EPS3" <?php if ($docente['eps'] == 'EPS3') echo 'selected'; ?>>EPS 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-eps" class="error-message"></div>
            </div>

            <!-- Pensión -->
            <div>
                <label for="pension" class="block text-sm">Pensión</label>
                <select id="pension" name="pension" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una Pensión</option>
                    <option value="Pension1" <?php if ($docente['pension'] == 'Pension1') echo 'selected'; ?>>Pensión 1</option>
                    <option value="Pension2" <?php if ($docente['pension'] == 'Pension2') echo 'selected'; ?>>Pensión 2</option>
                    <option value="Pension3" <?php if ($docente['pension'] == 'Pension3') echo 'selected'; ?>>Pensión 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-pension" class="error-message"></div>
            </div>

            <!-- Caja Compensación -->
            <div>
                <label for="caja_comp" class="block text-sm">Caja Compensación</label>
                <select id="caja_comp" name="caja_comp" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una Caja de Compensación</option>
                    <option value="Caja1" <?php if ($docente['caja_comp'] == 'Caja1') echo 'selected'; ?>>Caja 1</option>
                    <option value="Caja2" <?php if ($docente['caja_comp'] == 'Caja2') echo 'selected'; ?>>Caja 2</option>
                    <option value="Caja3" <?php if ($docente['caja_comp'] == 'Caja3') echo 'selected'; ?>>Caja 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-caja_comp" class="error-message"></div>
            </div>

            <!-- Contraseña -->
            <div>
                <label for="contrasena" class="block text-sm">Contraseña (dejar en blanco si no desea cambiar)</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Contraseña -->
                    </span>
                    <input type="password" id="contrasena" name="contrasena" minlength="8" oninput="validateField(this)" placeholder="Contraseña" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-contrasena" class="error-message"></div>
            </div>

            <!-- Botón de Envío -->
            <div class="col-span-full">
                <input type="submit" value="Actualizar" class="block w-60 mt-6 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
            </div>
        </div>
    </form>
</div>

<!-- Validación de Formularios con JavaScript -->
<script>
    document.getElementById('editarDocenteForm').addEventListener('submit', function(event) {
        const fields = ['nombre', 'apellido', 'cedula', 'correo', 'celular', 'sexo', 'titulo', 'fecha_de_nacimiento', 'direccion', 'eps', 'pension', 'caja_comp'];
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

        // Validar contraseña si se ingresa
        const contrasenaInput = document.getElementById('contrasena');
        const contrasenaErrorDiv = document.getElementById('error-contrasena');
        if (contrasenaInput.value !== '' && contrasenaInput.value.length < 8) {
            contrasenaErrorDiv.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            isValid = false;
        } else {
            contrasenaErrorDiv.textContent = '';
        }

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

<?php
include_once __DIR__ . '/../footer.php';
?>
