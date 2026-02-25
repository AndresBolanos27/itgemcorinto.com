<?php
// Incluir el archivo de conexión a la base de datos
include '../App/conexion.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las notas ingresadas y actualizarlas o insertarlas en la base de datos
    foreach ($_POST as $key => $value) {
        // Verificar si el campo es una nota (tiene el formato nota_<id_estudiante>_<id_materia>)
        if (substr($key, 0, 5) == "nota_") {
            // Obtener el ID del estudiante y de la materia desde el nombre del campo
            $parts = explode("_", $key);
            $id_estudiante = $parts[1];
            $id_materia = $parts[2];
            $nota = $value;

            // Verificar si la nota ya existe en la base de datos
            $sql_verificar_nota = "SELECT * FROM notas WHERE id_estudiante = $id_estudiante AND id_materia = $id_materia";
            $resultado_verificacion = $conexion->query($sql_verificar_nota);

            if ($resultado_verificacion->num_rows > 0) {
                // La nota ya existe, actualizarla
                $sql_actualizar_nota = "UPDATE notas SET nota = $nota WHERE id_estudiante = $id_estudiante AND id_materia = $id_materia";
                $conexion->query($sql_actualizar_nota);
            } else {
                // La nota no existe, insertarla
                $sql_insertar_nota = "INSERT INTO notas (id_estudiante, id_materia, nota) VALUES ($id_estudiante, $id_materia, $nota)";
                $conexion->query($sql_insertar_nota);
            }
        }
    }
    
    // Redirigir a la página de notas con un mensaje de éxito
    header("Location: ../App/notas.php?mensaje=notas_actualizadas");
    exit();
}
?>
