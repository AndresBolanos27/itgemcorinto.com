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

// Consultar los datos de la tabla admin
$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados de la tabla
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Apellido');
    $sheet->setCellValue('D1', 'Cédula');
    $sheet->setCellValue('E1', 'Correo');
    $sheet->setCellValue('F1', 'Celular');
    $sheet->setCellValue('G1', 'Título');
    $sheet->setCellValue('H1', 'Fecha de Nacimiento');
    $sheet->setCellValue('I1', 'Dirección');
    $sheet->setCellValue('J1', 'Rol');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['nombre']);
        $sheet->setCellValue('C' . $rowNumber, $row['apellido']);
        $sheet->setCellValue('D' . $rowNumber, $row['cedula']);
        $sheet->setCellValue('E' . $rowNumber, $row['correo']);
        $sheet->setCellValue('F' . $rowNumber, $row['celular']);
        $sheet->setCellValue('G' . $rowNumber, $row['titulo']);
        $sheet->setCellValue('H' . $rowNumber, $row['fecha_de_nacimiento']);
        $sheet->setCellValue('I' . $rowNumber, $row['direccion']);
        $sheet->setCellValue('J' . $rowNumber, $row['rol']);
        $rowNumber++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'admins.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    $writer->save('php://output');
} else {
    echo "No hay registros para exportar";
}
$conn->close();
?>
