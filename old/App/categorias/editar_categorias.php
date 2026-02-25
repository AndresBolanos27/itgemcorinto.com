<?php
include_once __DIR__ . '/../config/verificar_sesion.php';
verificar_sesion();
include_once __DIR__ . '/../config/database.php';

// Verificar el rol del usuario
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'directora_rectora') {
    echo "<script>
              alert('No tienes permiso para acceder a esta página');
              window.location.href = 'categorias';
          </script>";
    exit();
}

$id = $_GET['id'];

// Consultar el registro actual
$sql = "SELECT categoria FROM categorias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$categoria = $result->fetch_assoc();

if (!$categoria) {
    echo "<script>
              alert('Categoría no encontrada');
              window.location.href = 'categorias';
          </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre_categoria = $_POST['categoria'];

    // Validaciones del servidor
    if (!in_array($nombre_categoria, ['Matemáticas', 'Naturales', 'Sociales', 'Lenguaje', 'Artes'])) {
        echo "<script>
                  alert('Categoría inválida.');
                  window.location.href = 'editar_categorias?id=$id';
              </script>";
        exit();
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar los datos en la tabla categorías
        $sql_categoria = "UPDATE categorias SET categoria=? WHERE id=?";
        $stmt_categoria = $conn->prepare($sql_categoria);
        $stmt_categoria->bind_param("si", $nombre_categoria, $id);
        $stmt_categoria->execute();

        // Si la actualización fue exitosa, confirmamos la transacción
        $conn->commit();

        echo "<script>
                alert('Categoría actualizada correctamente');
                window.location.href = 'categorias';
              </script>";
    } catch (Exception $e) {
        // Si algo falla, revertimos la transacción
        $conn->rollback();
        echo "<script>
                alert('Error al actualizar la categoría: " . $e->getMessage() . "');
                window.location.href = 'editar_categorias?id=$id';
              </script>";
    }

    $stmt_categoria->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
</head>
<body>

<h1>Editar Categoría</h1>

<form action="editar_categorias?id=<?php echo $id; ?>" method="post">
    <label for="categoria">Nombre de la Categoría:</label>
    <select id="categoria" name="categoria" required>
       <option value="Norma" <?php echo $categoria['categoria'] == 'Norma' ? 'selected' : ''; ?>>Norma</option>
<option value="Recuperatorio" <?php echo $categoria['categoria'] == 'Recuperatorio' ? 'selected' : ''; ?>>Recuperatorio</option>
<option value="Diplomado" <?php echo $categoria['categoria'] == 'Diplomado' ? 'selected' : ''; ?>>Diplomado</option>
<option value="Seminario" <?php echo $categoria['categoria'] == 'Seminario' ? 'selected' : ''; ?>>Seminario</option>
<option value="Supletorio" <?php echo $categoria['categoria'] == 'Supletorio' ? 'selected' : ''; ?>>Supletorio</option>
<option value="Transversales" <?php echo $categoria['categoria'] == 'Transversales' ? 'selected' : ''; ?>>Transversales</option>
<option value="Otro" <?php echo $categoria['categoria'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
    </select>
    <input type="submit" value="Actualizar">
</form>

</body>
</html>
