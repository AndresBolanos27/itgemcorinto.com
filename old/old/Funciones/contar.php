<?php
$servername = "localhost";
$username = "santand1_santand1";
$password = "AndresBrown11@";
$dbname = "santand1_itgem_academico";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener el número de registros en el campo "id" de la tabla "estudiantes"
$sqlEstudiantes = "SELECT COUNT(id) as totalEstudiantes FROM estudiantes";
$resultEstudiantes = $conn->query($sqlEstudiantes);

if ($resultEstudiantes->num_rows > 0) {
    $rowEstudiantes = $resultEstudiantes->fetch_assoc();
    $totalRegistrosEstudiantes = $rowEstudiantes["totalEstudiantes"];
} else {
    $totalRegistrosEstudiantes = 0;
}

// Consulta SQL para obtener el número de registros en el campo "id" de la tabla "docentes"
$sqlDocentes = "SELECT COUNT(id) as totalDocentes FROM docentes";
$resultDocentes = $conn->query($sqlDocentes);

if ($resultDocentes->num_rows > 0) {
    $rowDocentes = $resultDocentes->fetch_assoc();
    $totalRegistrosDocentes = $rowDocentes["totalDocentes"];
} else {
    $totalRegistrosDocentes = 0;
}

// Consulta SQL para obtener el número de registros en el campo "id" de la tabla "grupos"
$sqlGrupos = "SELECT COUNT(id) as totalGrupos FROM grupos";
$resultGrupos = $conn->query($sqlGrupos);

if ($resultGrupos->num_rows > 0) {
    $rowGrupos = $resultGrupos->fetch_assoc();
    $totalRegistrosGrupos = $rowGrupos["totalGrupos"];
} else {
    $totalRegistrosGrupos = 0;
}
?>
