<?php

session_start();

// Inicializar variables de error
$error_correo = isset($_SESSION['error_correo']) ? $_SESSION['error_correo'] : '';
$error_contrasena = isset($_SESSION['error_contrasena']) ? $_SESSION['error_contrasena'] : '';

// Limpiar los mensajes de error después de mostrarlos
unset($_SESSION['error_correo']);
unset($_SESSION['error_contrasena']);

// Obtener el mensaje de error de los parámetros GET
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
?>


<script>
    // Mostrar el mensaje de error si existe
    window.onload = function() {
        var mensaje = "<?php echo $mensaje; ?>";
        if (mensaje) {
            alert(mensaje);
        }
    };
</script>

<?php
$base_url = './';
?>


<!DOCTYPE html>
<html data-theme="light" lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appcademica</title>
    <link rel="shortcut icon" href="https://itgemcorinto.com/Recursos/Images/logo2.png" type="image/x-icon">


    <link rel="stylesheet" href="<?php echo $base_url; ?>/Recursos/CSS/full.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/Recursos/CSS/styles.css">
    <script src="<?php echo $base_url; ?>/Recursos/JS/tailwindcss.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body>

    <!-- <h1>Iniciar Sesión</h1>
    <form action="procesar_login" method="post">
        <label for="correo">Correo:</label><br>
        <input autocomplete="off" type="email" id="correo" name="correo" required><br>
        <?php if (!empty($error_correo)) echo "<p style='color:red;'>$error_correo</p>"; ?><br>

        <label for="contrasena">Contraseña:</label><br>
        <input autocomplete="off" type="password" id="contrasena" name="contrasena" required><br>
        <?php if (!empty($error_contrasena)) echo "<p style='color:red;'>$error_contrasena</p>"; ?><br>

        <input type="submit" value="Iniciar Sesión">
    </form> -->


    <section class="">
        <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
            <form action="procesar_login" method="post">

                <img  id="logoImage" class="w-auto h-20 sm:h-20" src="https://itgemcorinto.com/Recursos/Images/logo.png" alt="">

                <h1 class="mt-3 text-2xl font-semibold capitalize sm:text-3xl">Iniciar Sección</h1>


                <div class="relative flex items-center mt-8">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <input type="email" id="correo" name="correo" class="block w-full py-3  border rounded-lg px-11   focus:border-blue-400  focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Correo Electronico">
                </div>

                <div class="relative flex items-center mt-4">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>

                    <input type="password" id="contrasena" name="contrasena" class="block w-full px-10 py-3 border rounded-lg focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" placeholder="Contraseña">
                </div>

                <div class="mt-6">
                    <button class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        Iniciar Sección
                    </button>


                </div>
            </form>
        </div>

    </section>


    <label class="flex items-center cursor-pointer gap-2 fixed bottom-4 right-4 p-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5" />
            <path d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
        </svg>
        <input type="checkbox" id="themeToggle" class="toggle theme-controller" />
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
    </label>

 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');
        const htmlTag = document.querySelector('html');
        const logoImage = document.getElementById('logoImage'); // Selecciona la imagen del logo

        // Verifica si hay una preferencia de tema almacenada en localStorage
        const currentTheme = localStorage.getItem('theme');

        // Si hay una preferencia de tema almacenada, aplica ese tema al cargar la página
        if (currentTheme) {
            htmlTag.setAttribute('data-theme', currentTheme);
            if (currentTheme === 'night') {
                themeToggle.checked = true;
                logoImage.src = 'https://itgemcorinto.com/Recursos/Images/logo2.png'; // Cambia a logo2.png si el tema es oscuro
            }
        }

        // Agrega un event listener para detectar cambios en el toggle de tema
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                htmlTag.setAttribute('data-theme', 'night');
                localStorage.setItem('theme', 'night'); // Almacena el tema seleccionado en localStorage
                logoImage.src = 'https://itgemcorinto.com/Recursos/Images/logo2.png'; // Cambia a logo2.png en modo oscuro
            } else {
                htmlTag.setAttribute('data-theme', 'cupcake');
                localStorage.setItem('theme', 'cupcake'); // Almacena el tema seleccionado en localStorage
                logoImage.src = 'https://itgemcorinto.com/Recursos/Images/logo.png'; // Cambia a logo.png en modo claro
            }
        });
    });
</script>

</body>

</html>