<?php
include_once __DIR__ . '/header.php';
include_once __DIR__ . '/config/verificar_sesion.php';

verificar_sesion();

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin') {
  echo "<script>
            alert('No tienes permiso para acceder a esta página');
            window.location.href = 'login'; // Redirige a la página principal o donde desees
          </script>";
  exit();
}
?>

<?php
include_once __DIR__ . '/config/database.php';

// Consulta para contar estudiantes con estado 'activo'
$sql = "SELECT COUNT(*) AS total_activos FROM estudiantes WHERE estado = 'activo'";
$result = $conn->query($sql);
$total_activos = 0;

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $total_activos = $row['total_activos'];
}


// Obtener la fecha actual
$fecha_actual = date("Y-m-d");

// Consulta para obtener el ciclo escolar activo
$sql = "SELECT nombre_ciclo, fecha_inicio, fecha_fin FROM ciclos_escolares WHERE estado = 'activo' ORDER BY fecha_fin DESC LIMIT 1";
$result = $conn->query($sql);

// Inicializar las variables que contendrán los mensajes
$mensaje = "";
$mensaje_fecha_inicio = "";
$mensaje_fecha_fin = "";

if ($result->num_rows > 0) {
  // Obtener las fechas de inicio y fin
  $row = $result->fetch_assoc();
  $nombre_ciclo = $row['nombre_ciclo'];
  $fecha_inicio = $row['fecha_inicio'];
  $fecha_fin = $row['fecha_fin'];

  // Crear objetos DateTime
  $datetime_actual = new DateTime($fecha_actual);
  $datetime_inicio = new DateTime($fecha_inicio);
  $datetime_fin = new DateTime($fecha_fin);

  // Formatear las fechas para mostrar en el mensaje (opcional)
  $fecha_inicio_formateada = $datetime_inicio->format('d-m-Y');
  $fecha_fin_formateada = $datetime_fin->format('d-m-Y');

  // Crear los mensajes de las fechas
  $mensaje_fecha_inicio = "Fecha de inicio: <span class='badge badge-warning'> $fecha_inicio_formateada </span>";
  $mensaje_fecha_fin = "Fecha de finalización: <span class='badge badge-warning'> $fecha_fin_formateada </span>";

  if ($datetime_actual < $datetime_inicio) {
    // El ciclo aún no ha comenzado
    $interval_previo = $datetime_actual->diff($datetime_inicio);
    $dias_para_inicio = $interval_previo->days;
    $mensaje = "El ciclo escolar aún no ha comenzado. Faltan $dias_para_inicio días para el inicio.";
  } elseif ($datetime_actual > $datetime_fin) {
    // El ciclo ha finalizado
    $mensaje = "El ciclo escolar ha finalizado.";
  } else {
    // Calcular los días restantes
    $interval_restante = $datetime_actual->diff($datetime_fin);
    $dias_restantes = $interval_restante->days;

    // Verificar si es el último día
    if ($dias_restantes == 0) {
      $mensaje = "Hoy es el último día del periodo.";
    } else {
      // Preparar el mensaje con los días restantes
      $mensaje = "Faltan <span class='badge badge-warning'>$dias_restantes</span> días para terminar el periodo.";
    }
  }
} else {
  // No hay ciclos activos
  $mensaje = "No hay ciclos escolares activos.";
  $mensaje_fecha_inicio = "";
  $mensaje_fecha_fin = "";
}

// Cerrar la conexión
$conn->close();
?>




<div class="py-24 sm:py-8">
  <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
    <h2 class="mx-auto mt-2  text-pretty text-center text-4xl font-bold tracking-tight sm:text-5xl">
      Bienvenido <?php echo htmlspecialchars($_SESSION['usuario_nombre'] . ' ' . $_SESSION['usuario_apellido']); ?>
    </h2>
    <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
      <div class="relative lg:row-span-2">
        <div class="absolute inset-px rounded-lg bg-base-300 lg:rounded-l-[2rem]"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
          <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
            <p class="mt-2 text-2xl font-bold tracking-tight  max-lg:text-center">Nuestros Estudiantes</p>
            <p class="mb-5 max-w-lg text-lg font-light	  max-lg:text-center">Estudiantes activos:</p>
            <p class="mt-3 max-w-lg text-3xl  max-lg:text-center"><span class="bg-yellow-400 text-gray-800  p-2 rounded-full"><?php echo $total_activos; ?></span></p>

          </div>
          <div class="relative min-h-[30rem] w-full grow [container-type:inline-size] max-lg:mx-auto max-lg:max-w-sm">
            <div class="absolute inset-x-10 bottom-0 top-10 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-800 bg-base-100 shadow-2xl">
              <img class="size-full object-cover object-top" src="https://itgemcorinto.com/Recursos/Images/estudiante.png" alt="">
            </div>
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 lg:rounded-l-[2rem]"></div>
      </div>
      <div class="relative max-lg:row-start-1">
        <div class="absolute inset-px rounded-lg bg-base-300 max-lg:rounded-t-[2rem]"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
          <div class="px-8 pt-8 sm:px-10 sm:pt-10">
            <p class="mt-2 text-2xl font-bold tracking-tight  max-lg:text-center">Ciclos academicos</p>
            <p class="max-w-lg text-lg font-light	mb-5  max-lg:text-center">Tiempo restante:</p>
            <h2 class="max-w-lg text-lg font-light	  max-lg:text-center"><?php echo $mensaje; ?></h2>
          </div>
          <div class="flex flex-col items-center justify-center px-8 max-lg:pb-12 max-lg:pt-10 sm:px-10 lg:pb-2 mt-5">
            <img class="w-full max-lg:max-w-xs" src="https://itgemcorinto.com/Recursos/Images/ciclos.png" alt="">
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-t-[2rem]"></div>
      </div>
      <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
        <div class="absolute inset-px rounded-lg bg-base-300"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
          <div class="px-8 pt-8 sm:px-10 sm:pt-10">
            <p class="mt-2 text-2xl font-bold tracking-tight mb-4 max-lg:text-center">Mas información</p>
            <!-- Mostrar la fecha de inicio -->
            <?php if (!empty($mensaje_fecha_inicio)) { ?>
              <h2><?php echo $mensaje_fecha_inicio; ?></h2>
            <?php } ?>

            <!-- Mostrar la fecha de fin -->
            <?php if (!empty($mensaje_fecha_fin)) { ?>
              <h2><?php echo $mensaje_fecha_fin; ?></h2>
            <?php } ?>
          </div>
          <div class="flex flex-1 items-center [container-type:inline-size] max-lg:py-6 lg:pb-2">
            <img class="h-[min(152px,40cqw)] object-cover object-center" src=https://itgemcorinto.com/Recursos/Images/info.png" alt="">
          </div>
        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5"></div>
      </div>
      <div class="relative lg:row-span-2">
        <div class="absolute inset-px rounded-lg bg-base-300 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]"></div>
        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
          <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
          <p class="mt-2 text-2xl font-bold tracking-tight max-lg:text-center">Estadisticas</p>
          <p class="max-w-lg text-sm/6  max-lg:text-center">Proximamente.</p>
          </div>

        </div>
        <div class="pointer-events-none absolute inset-px rounded-lg shadow ring-1 ring-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]"></div>
      </div>
    </div>
  </div>
</div>


<?php
include_once __DIR__ . '/footer.php';
?>