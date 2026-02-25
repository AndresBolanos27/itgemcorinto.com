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
$sql = "SELECT * FROM estudiantes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$estudiante = $result->fetch_assoc();

// Consultar todos los grupos activos
$sql_grupos = "SELECT id, nombre_grupo FROM grupos WHERE estado = 'activo'";
$result_grupos = $conn->query($sql_grupos);
$grupos = [];
if ($result_grupos->num_rows > 0) {
    while ($row = $result_grupos->fetch_assoc()) {
        $grupos[] = $row;
    }
}

// Consultar el grupo actual del estudiante
$sql_grupo_actual = "SELECT grupo_id FROM estudiante_grupo WHERE estudiante_id = ?";
$stmt_grupo_actual = $conn->prepare($sql_grupo_actual);
$stmt_grupo_actual->bind_param("i", $id);
$stmt_grupo_actual->execute();
$result_grupo_actual = $stmt_grupo_actual->get_result();
$grupo_actual = $result_grupo_actual->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $sexo = $_POST['sexo'];
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
    $direccion = $_POST['direccion'];
    $eps = $_POST['eps'];
    $grupo = $_POST['grupo']; // Obtener el grupo seleccionado
    $grupo_etnico = $_POST['grupo_etnico'];
    $acudiente = $_POST['acudiente'];
    $numero_acudiente = $_POST['numero_acudiente'];
    $documentos = $estudiante['documentos']; // Mantener el nombre del documento original

    // Verificar si la contraseña ha cambiado
    $contrasena = $estudiante['contrasena'];
    if (!empty($_POST['contrasena'])) {
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar los datos en la tabla estudiantes
        $sql_estudiante = "UPDATE estudiantes SET nombre=?, apellido=?, cedula=?, correo=?, celular=?, sexo=?, fecha_de_nacimiento=?, direccion=?, eps=?, contrasena=?, grupo_etnico=?, acudiente=?, numero_acudiente=? WHERE id=?";
        $stmt_estudiante = $conn->prepare($sql_estudiante);
        $stmt_estudiante->bind_param("sssssssssssssi", $nombre, $apellido, $cedula, $correo, $celular, $sexo, $fecha_de_nacimiento, $direccion, $eps, $contrasena, $grupo_etnico, $acudiente, $numero_acudiente, $id);
        $stmt_estudiante->execute();

        // Actualizar el grupo del estudiante en la tabla estudiante_grupo
        $sql_grupo_update = "UPDATE estudiante_grupo SET grupo_id=? WHERE estudiante_id=?";
        $stmt_grupo_update = $conn->prepare($sql_grupo_update);
        $stmt_grupo_update->bind_param("ii", $grupo, $id);
        $stmt_grupo_update->execute();

        // Actualizar los datos en la tabla usuarios
        $sql_usuario = "UPDATE usuarios SET nombre=?, correo=?, contrasena=? WHERE estudiante_id=?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("sssi", $nombre, $correo, $contrasena, $id);
        $stmt_usuario->execute();

        // Confirmar transacción
        $conn->commit();

        echo "<script>
                alert('Estudiante actualizado correctamente');
                window.location.href = 'estudiantes';
              </script>";
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollback();
        echo "<script>
                alert('Error al actualizar el estudiante: " . $e->getMessage() . "');
                window.location.href = 'editar_estudiante?id=$id';
              </script>";
    }

    $stmt_estudiante->close();
    $stmt_grupo_update->close();
    $stmt_usuario->close();
    $conn->close();
}
?>

<div class="h-screen flex justify-center items-center p-6 my-64 md:my-0">
    <form id="editarEstudianteForm" action="editar_estudiante?id=<?php echo $id; ?>" method="post">
        <h1 class="mb-8 text-2xl font-semibold">Editar Estudiante</h1>

        <!-- Formulario de Edición -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono -->
                    </span>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($estudiante['apellido']); ?>" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($estudiante['cedula']); ?>" required pattern="\d+" oninput="validateField(this)" placeholder="Cédula" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($estudiante['correo']); ?>" required oninput="validateField(this)" placeholder="Correo" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="tel" id="celular" name="celular" value="<?php echo htmlspecialchars($estudiante['celular']); ?>" pattern="\d{10}" oninput="validateField(this)" placeholder="Celular" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-celular" class="error-message"></div>
            </div>

            <!-- Sexo -->
            <div>
                <label for="sexo" class="block text-sm">Sexo</label>
                <select id="sexo" name="sexo" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione Sexo</option>
                    <option value="M" <?php if ($estudiante['sexo'] == 'M') echo 'selected'; ?>>Masculino</option>
                    <option value="F" <?php if ($estudiante['sexo'] == 'F') echo 'selected'; ?>>Femenino</option>
                </select>
                <div id="error-sexo" class="error-message"></div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_de_nacimiento" class="block text-sm">Fecha de Nacimiento</label>
                <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" value="<?php echo htmlspecialchars($estudiante['fecha_de_nacimiento']); ?>" required oninput="validateField(this)" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                <div id="error-fecha_de_nacimiento" class="error-message"></div>
            </div>

            <!-- Dirección -->
            <div>
                <label for="direccion" class="block text-sm">Dirección</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Dirección -->
                    </span>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($estudiante['direccion']); ?>" required oninput="validateField(this)" placeholder="Dirección" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <!-- EPS -->
            <div>
                <label for="eps" class="block text-sm">EPS</label>
                <select id="eps" name="eps" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione una EPS</option>
                    <option value="EPS1" <?php if ($estudiante['eps'] == 'EPS1') echo 'selected'; ?>>EPS 1</option>
                    <option value="EPS2" <?php if ($estudiante['eps'] == 'EPS2') echo 'selected'; ?>>EPS 2</option>
                    <option value="EPS3" <?php if ($estudiante['eps'] == 'EPS3') echo 'selected'; ?>>EPS 3</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
                <div id="error-eps" class="error-message"></div>
            </div>

            <!-- Grupo -->
            <div>
                <label for="grupo" class="block text-sm">Grupo</label>
                <select id="grupo" name="grupo" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="">Seleccione un grupo</option>
                    <?php foreach ($grupos as $grupo) : ?>
                        <option value="<?php echo htmlspecialchars($grupo['id']); ?>" <?php if ($grupo['id'] == $grupo_actual['grupo_id']) echo 'selected'; ?>>
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
                    <option value="GrupoEtnico1" <?php if ($estudiante['grupo_etnico'] == 'GrupoEtnico1') echo 'selected'; ?>>Grupo Étnico 1</option>
                    <option value="GrupoEtnico2" <?php if ($estudiante['grupo_etnico'] == 'GrupoEtnico2') echo 'selected'; ?>>Grupo Étnico 2</option>
                    <option value="GrupoEtnico3" <?php if ($estudiante['grupo_etnico'] == 'GrupoEtnico3') echo 'selected'; ?>>Grupo Étnico 3</option>
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
                    <input type="text" id="acudiente" name="acudiente" value="<?php echo htmlspecialchars($estudiante['acudiente']); ?>" required oninput="validateField(this)" placeholder="Acudiente" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input type="tel" id="numero_acudiente" name="numero_acudiente" value="<?php echo htmlspecialchars($estudiante['numero_acudiente']); ?>" pattern="\d{10}" oninput="validateField(this)" placeholder="Número de Acudiente" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-numero_acudiente" class="error-message"></div>
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
    document.getElementById('editarEstudianteForm').addEventListener('submit', function(event) {
        const fields = ['nombre', 'apellido', 'cedula', 'correo', 'celular', 'sexo', 'fecha_de_nacimiento', 'direccion', 'eps', 'grupo', 'grupo_etnico', 'acudiente', 'numero_acudiente'];
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

<?php
include_once __DIR__ . '/../footer.php';
?>
