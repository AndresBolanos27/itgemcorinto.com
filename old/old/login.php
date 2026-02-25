<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iniciar sesion</title>
    <link rel="shortcut icon" href="./assets/images/Logo Elotes Ilustrado Amarillo y Verde.png" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="stylesheet" href="./assets/fonts/fonst.css">

    

    <link rel="stylesheet" href="./recursos/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <section style="background-color: white !important;" class="mt-1 d-flex justify-content-center text-center cardcustom">
        <div><img width="150" class="mt-5 img-fluid" src="./recursos/img/logo-grande.svg" alt="">
            <p class="text-center mt-4 fw-bolder p-custom">Bienvenido de nuevo, inicia sección para acceder</p>
            <p style="font-size: 14px !important;" class="p-custom mt-3">Por favor ingresa tus credenciales de inicio de sección para poder acceder a la plataforma de gestión de notas APPITGEM</p>

            <form action="./Funciones/funcion_login.php" method="post">
                <div class="m-auto input-group mt-4 inputdiv">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-paper-plane"></i></span>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                </div>
                <div class="m-auto input-group mt-3 inputdiv">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Password" aria-label="Username" aria-describedby="basic-addon1" required>
                </div>

                <div class="d-grid gap-2 inputdiv mt-4">
                    <button type="submit" class="btn btn-custom fw-bolder" type="button">iniciar sección</button>
                </div>
            </form>
        </div>
    </section>

    <script src="./assets/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGEM</title>

 <link href="./recursos/full.min.css" rel="stylesheet" type="text/css" />
    <script src="./recursos/cdn.js"></script>
</head>

<body>

    <div class="hero min-h-screen bg-base-200">
        <font></font>
        <div class="hero-content flex-col lg:flex-row-reverse">
            <font></font>
            <div class="text-center lg:text-left">
                <div class="avatar mb-8">
                    <font></font>
                    <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <font></font>
                        <img src="./recursos/img/logo-grande.svg" />
                        <font></font>
                    </div>
                    <font></font>
                </div>
                <h1 class="text-5xl font-bold">¡Iniciar Sesion!</h1>
                <font></font>
                <p class="py-6">Bienvenido de nuevo.
                    Por favor ingresa tus credenciales de inicio de sección <br> para poder acceder a la plataforma de
                    gestión de notas.</p>

                
            </div>
            <font></font>
            <div class="card shrink-0 w-full max-w-sm shadow-2xl bg-base-100 me-5">
                <font></font>
                <form action="./Funciones/funcion_login.php" method="post" class="card-body">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Usuario</span>
                        </label>
                        <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Usuario" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Contraseña</span>
                        </label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="password" class="input input-bordered" required />

                    </div>
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <font></font>
            </div>
            <font></font>
        </div>
        <font></font>
    </div>


    <footer class="footer footer-center p-4 bg-base-300 text-base-content">
        <font></font>
        <aside>
            <font></font>
            <p>Copyright © 2024 - All right reserved by santandervalleycol</p>
            <font></font>
        </aside>
        <font></font>
    </footer>
</body>

</html>