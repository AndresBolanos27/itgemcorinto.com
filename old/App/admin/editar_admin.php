<?php
include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();

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
$sql = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $titulo = $_POST['titulo'];
    $fecha_de_nacimiento = $_POST['fecha_de_nacimiento'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
    $rol = $_POST['rol'];

    // Verificar si la contraseña ha cambiado
    $contrasena = $admin['contrasena'];
    if (!empty($_POST['contrasena'])) {
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar los datos en la tabla admin
        $sql_admin = "UPDATE admin SET nombre=?, apellido=?, cedula=?, correo=?, celular=?, titulo=?, fecha_de_nacimiento=?, direccion=?, contrasena=?, estado=?, rol=? WHERE id=?";
        $stmt_admin = $conn->prepare($sql_admin);
        $stmt_admin->bind_param("sssssssssssi", $nombre, $apellido, $cedula, $correo, $celular, $titulo, $fecha_de_nacimiento, $direccion, $contrasena, $estado, $rol, $id);
        $stmt_admin->execute();

        // Actualizar los datos en la tabla usuarios
        $sql_usuario = "UPDATE usuarios SET nombre=?, correo=?, contrasena=?, rol=? WHERE admin_id=?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("ssssi", $nombre, $correo, $contrasena, $rol, $id);
        $stmt_usuario->execute();

        // Si ambas actualizaciones fueron exitosas, confirmamos la transacción
        $conn->commit();

        echo "<script>
                alert('Administrador actualizado correctamente');
                window.location.href = 'admin';
              </script>";
    } catch (Exception $e) {
        // Si algo falla, revertimos la transacción
        $conn->rollback();
        echo "<script>
                alert('Error al actualizar el administrador: " . $e->getMessage() . "');
                window.location.href = 'editar_admin?id=$id';
              </script>";
    }

    $stmt_admin->close();
    $stmt_usuario->close();
    $conn->close();
}
?>


<!-- 
<form action="editar_admin?id=<?php echo $id; ?>" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($admin['nombre']); ?>" required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($admin['apellido']); ?>" required>

    <label for="cedula">Cédula:</label>
    <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($admin['cedula']); ?>" required>

    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($admin['correo']); ?>" required>

    <label for="celular">Celular:</label>
    <input type="tel" id="celular" name="celular" value="<?php echo htmlspecialchars($admin['celular']); ?>">

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($admin['titulo']); ?>">

    <label for="fecha_de_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" value="<?php echo htmlspecialchars($admin['fecha_de_nacimiento']); ?>">

    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($admin['direccion']); ?>">

    <label for="contrasena">Contraseña (dejar en blanco si no desea cambiar):</label>
    <input type="password" id="contrasena" name="contrasena">

    <label for="estado">Estado:</label>
    <select id="estado" name="estado" required>
        <option value="activo" <?php echo $admin['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
        <option value="inactivo" <?php echo $admin['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
    </select>

    <label for="rol">Rol:</label>
    <select id="rol" name="rol" required>
        <option value="admin" <?php echo $admin['rol'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        <option value="auxiliar" <?php echo $admin['rol'] == 'auxiliar' ? 'selected' : ''; ?>>Auxiliar</option>
        <option value="directora_rectora" <?php echo $admin['rol'] == 'directora_rectora' ? 'selected' : ''; ?>>Directora/Rectora</option>
        <option value="secretaria" <?php echo $admin['rol'] == 'secretaria' ? 'selected' : ''; ?>>Secretaria</option>
    </select>

    <input type="submit" value="Actualizar">
</form> -->



<div class="h-screen flex justify-center items-center  my-64 md:my-0">

    <form action="editar_admin?id=<?php echo $id; ?>" method="post">

        <h1 class=" mb-8 text-2xl font-semibold">Editar Administrador</h1>
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
                    <input value="<?php echo htmlspecialchars($admin['nombre']); ?>" type="text" id="nombre" name="nombre" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo htmlspecialchars($admin['apellido']); ?>" type="text" id="apellido" name="apellido" required pattern="[A-Za-z\s]+" oninput="validateField(this)" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo htmlspecialchars($admin['cedula']); ?>" type="text" id="cedula" name="cedula" required pattern="\d+" oninput="validateField(this)" placeholder="Cédula" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo htmlspecialchars($admin['correo']); ?>" type="email" id="correo" name="correo" required oninput="validateField(this)" placeholder="Correo" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo htmlspecialchars($admin['celular']); ?>" type="tel" id="celular" name="celular" pattern="\d{10}" oninput="validateField(this)" placeholder="Celular" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo htmlspecialchars($admin['titulo']); ?>" type="text" id="titulo" name="titulo" required oninput="validateField(this)" placeholder="Título" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-titulo" class="error-message"></div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_de_nacimiento" class="block text-sm">Fecha de Nacimiento</label>
                <input value="<?php echo htmlspecialchars($admin['fecha_de_nacimiento']); ?>" type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" required oninput="validateField(this)" class="block w-full py-2.5 ps-3 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
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
                    <input value="<?php echo htmlspecialchars($admin['direccion']); ?>" type="text" id="direccion" name="direccion" required oninput="validateField(this)" placeholder="Dirección" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <!-- Contraseña -->
            <div>
                <label for="contrasena" class="block text-sm">(dejar en blanco si no desea cambiar)</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <!-- Icono para Contraseña -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                        </svg>

                    </span>
                    <input type="password" id="contrasena" name="contrasena" required minlength="8" oninput="validateField(this)" placeholder="Contraseña (dejar en blanco si no desea cambiar):" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div id="error-contrasena" class="error-message"></div>
            </div>


            <!-- Estado -->
            <div>
                <label for="rol" class="block text-sm">Estado</label>

                <select class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2" id="estado" name="estado" required>
                    <option value="activo" <?php echo $admin['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo $admin['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <!-- Rol -->
            <div>
                <label for="rol" class="block text-sm">Rol</label>
                <select id="rol" name="rol" required class="block ps-3 w-full py-2.5 border rounded-lg pr-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40 mt-2">
                    <option value="admin" <?php echo $admin['rol'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="auxiliar" <?php echo $admin['rol'] == 'auxiliar' ? 'selected' : ''; ?>>Auxiliar</option>
                    <option value="directora_rectora" <?php echo $admin['rol'] == 'directora_rectora' ? 'selected' : ''; ?>>Directora/Rectora</option>
                    <option value="secretaria" <?php echo $admin['rol'] == 'secretaria' ? 'selected' : ''; ?>>Secretaria</option>
                </select>
                <div id="error-rol" class="error-message"></div>
            </div>

            <!-- Botón de Envío -->
            <div class="col-span-full">
                <input type="submit" value="Actualizar" class="block w-60 mt-6 py-2.5  rounded-lg bg-blue-500 text-white hover:bg-blue-600">
            </div>
        </div>
    </form>
</div>

<?php
include_once __DIR__ . '/../footer.php';
?>