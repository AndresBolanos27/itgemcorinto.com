<?php
include '../App/conexion.php';

function insertarGrupo($nombre_grupo, $ano_lectivo, $nivel_educativo, $seccion, $horario, $aula_asignada, $conexion)
{
    $sql = "INSERT INTO grupos (nombre_grupo,ano_lectivo,nivel_educativo,seccion,horario,aula_asignada) 
    VALUES ('$nombre_grupo','$ano_lectivo','$nivel_educativo','$seccion','$horario','$aula_asignada')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Nuevo grupo insertado correctamente.'); window.location.href = '../App/grupos.php';</script>";
    } else {
        echo "Error al insertar el grupo: " . $conexion->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_grupo = $_POST["nombre_grupo"];
    $año_lectivo = $_POST["ano_lectivo"];
    $nivel_educativo = $_POST["nivel_educativo"];
    $seccion = $_POST["seccion"];
    $horario = $_POST["horario"];
    $aula_asignada = $_POST["aula_asignada"];

    insertarGrupo($nombre_grupo, $ano_lectivo, $nivel_educativo, $seccion, $horario, $aula_asignada, $conexion);
}
