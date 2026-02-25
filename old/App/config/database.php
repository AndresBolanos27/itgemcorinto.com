<?php
$servername = "localhost";
$username = "santand1_santand1";
$password = "AndresBrown11@";
$dbname = "santand1_appcademica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiиоn fallida: " . $conn->connect_error); // Carивcter corregido
}
?>