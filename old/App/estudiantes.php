<?php
include_once __DIR__ . '/header.php';
include_once __DIR__ . '/config/verificar_sesion.php';
verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'estudiante') {
    echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'logout'; // Redirige a la página principal o donde desees
          </script>";
    exit();
}
?>





<?php
include_once __DIR__ . '/footer.php';
?>