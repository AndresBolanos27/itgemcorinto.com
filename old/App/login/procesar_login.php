<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


include_once __DIR__ . '/../header.php';
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Evitar inyecciones SQL
$correo = $conn->real_escape_string($correo);
$contrasena = $conn->real_escape_string($contrasena);

// Función para verificar el login
function verificar_usuario($conn, $correo, $contrasena, $tabla)
{
    $sql = "SELECT * FROM $tabla WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar la contraseña (asumiendo que las contraseñas están hasheadas)
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Verificar si el usuario está activo
            if ($usuario['estado'] == 'activo') {
                // Inicio de sesión exitoso
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_apellido'] = $usuario['apellido'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                return true;
            } else {
                // Usuario inactivo
                echo "<script>
                        alert('Usuario inactivo. Contacte al administrador.');
                        window.location.href = 'login';
                      </script>";
                return false;
            }
        } else {
            // Contraseña incorrecta
            echo "<script>
                    alert('Contraseña incorrecta.');
                    window.location.href = 'login';
                  </script>";
            return false;
        }
    }
    return false;
}

// Verificar login en tabla admin
if (verificar_usuario($conn, $correo, $contrasena, 'admin')) {
    header("Location: dashboard");
    exit();
}

// Verificar login en tabla docentes
if (verificar_usuario($conn, $correo, $contrasena, 'docentes')) {
    header("Location: dashboard_docente");
    exit();
}

// **Agregar esta sección para verificar en la tabla estudiantes**
// Verificar login en tabla estudiantes
if (verificar_usuario($conn, $correo, $contrasena, 'estudiantes')) {
    header("Location: dashboard_estudiante");
    exit();
}

// Usuario no encontrado en ninguna tabla
echo "<script>
        alert('Correo no encontrado.');
        window.location.href = 'login';
      </script>";

$conn->close();
?>
