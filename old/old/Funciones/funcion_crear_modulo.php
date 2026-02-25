<?php
include '../App/conexion.php';

function insertarModulo($nombre_materia, $docente_asignado, $grupo_asignado, $fecha_inicio, $fecha_finalizacion, $estado, $conexion)
{
    $sql = "INSERT INTO materias (nombre_materia,docente_asignado,grupo_asignado,fecha_inicio,fecha_finalizacion,estado) 
    VALUES ('$nombre_materia','$docente_asignado','$grupo_asignado','$fecha_inicio','$fecha_finalizacion','$estado')";
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Nuevo modulo insertado correctamente.'); window.location.href = '../App/modulos.php';</script>";
    } else {
        echo "Error al insertar el modulo: " . $conexion->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_materia = $_POST["nombre_materia"];
    $docente_asignado = $_POST["docente_asignado"];
    $grupo_asignado = $_POST["grupo_asignado"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_finalizacion = $_POST["fecha_finalizacion"];
    $estado = $_POST["estado"];

    insertarModulo($nombre_materia, $docente_asignado, $grupo_asignado, $fecha_inicio, $fecha_finalizacion, $estado, $conexion);
}
