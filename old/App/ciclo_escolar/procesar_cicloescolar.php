<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Obtener datos del formulario
$nombre_ciclo = $_POST['nombre_ciclo'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$estado = $_POST['estado'];

// Validaciones del servidor
if (!preg_match("/^[A-Za-z0-9\s]+$/", $nombre_ciclo)) {
    die("Nombre del ciclo inválido.");
}


if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_inicio)) {
    die("Fecha de inicio inválida.");
}

if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_fin)) {
    die("Fecha de fin inválida.");
}

if (!in_array($estado, ['activo', 'inactivo'])) {
    die("Estado inválido.");
}

// Iniciar una transacción para asegurarnos de que el insert se ejecute correctamente
$conn->begin_transaction();

try {
    // Insertar los datos en la tabla ciclos_escolares
    $sql_ciclo = "INSERT INTO ciclos_escolares (nombre_ciclo, fecha_inicio, fecha_fin, estado) 
                  VALUES (?, ?, ?, ?)";
    $stmt_ciclo = $conn->prepare($sql_ciclo);
    $stmt_ciclo->bind_param("ssss", $nombre_ciclo, $fecha_inicio, $fecha_fin, $estado);
    $stmt_ciclo->execute();

    // Si el insert fue exitoso, confirmamos la transacción
    $conn->commit();

    echo "<script>
            alert('Registro exitoso');
            window.location.href = 'cicloescolar';
          </script>";
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos la transacción
    $conn->rollback();

    if ($e->getCode() == 1062) {
        // Código de error 1062: Entrada duplicada para clave única
        echo "<script>
                alert('Error: El nombre del ciclo ya existe.');
                window.location.href = 'cicloescolar';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.location.href = 'cicloescolar';
              </script>";
    }
}

$stmt_ciclo->close();
$conn->close();
?>
