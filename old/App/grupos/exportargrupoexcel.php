<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include_once __DIR__ . '/../config/database.php';

// Verificar el token
if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['download_token']) {
    die("Acceso no autorizado.");
}

// Consultar los datos de la tabla grupos
$sql = "SELECT * FROM grupos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados de la tabla
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Código');
    $sheet->setCellValue('C1', 'Nombre del Grupo');
    $sheet->setCellValue('D1', 'Fecha de Creación');
    $sheet->setCellValue('E1', 'Estado');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['Codigo']);
        $sheet->setCellValue('C' . $rowNumber, $row['nombre_grupo']);
        $sheet->setCellValue('D' . $rowNumber, $row['fecha_creacion']);
        $sheet->setCellValue('E' . $rowNumber, $row['estado']);
        $rowNumber++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'grupos.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $writer->save('php://output');
} else {
    echo "No hay registros para exportar";
}
$conn->close();
?>
