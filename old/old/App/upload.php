<?php
include '../App/conexion.php';

$target_dir = "uploads\\";  // Directorio donde se guardarán los archivos, usa doble barra invertida en Windows
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Verificar si el archivo es un PDF o un Word
if($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
    echo "Lo sentimos, solo se permiten archivos PDF, DOC y DOCX.";
    $uploadOk = 0;
}

// Verificar si $uploadOk es 0 por algún error
if ($uploadOk == 0) {
    echo "Lo sentimos, tu archivo no fue subido.";
// Si todo está bien, intenta subir el archivo
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "El archivo ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " ha sido subido.";

        // Preparar la sentencia SQL para insertar la ruta en la base de datos
        $stmt = $conexion->prepare("INSERT INTO estudiantes (ruta_pdf) VALUES (?)");
        $stmt->bind_param("s", $target_file);

        // Ejecutar la sentencia y verificar si se insertó correctamente
        if ($stmt->execute()) {
            echo "La ruta del archivo ha sido guardada en la base de datos.";
        } else {
            echo "Error al guardar la ruta en la base de datos: " . $conexion->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Lo sentimos, hubo un error al subir tu archivo.";
    }
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
