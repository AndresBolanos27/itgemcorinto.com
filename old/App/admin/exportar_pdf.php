<?php
session_start();
require 'vendor/autoload.php';
include_once __DIR__ . '/../config/database.php';

// Verificar el token
if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['download_token']) {
    die("Acceso no autorizado.");
}

// Consultar los datos de la tabla admin
$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF();

// Establecer la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Lista de Admins');
$pdf->SetSubject('Lista de Admins');
$pdf->SetKeywords('TCPDF, PDF, admin, list');

// Añadir una página
$pdf->AddPage();

// Establecer la fuente
$pdf->SetFont('helvetica', '', 12);

// Establecer un color de fondo
$pdf->SetFillColor(240, 240, 240);

// Título del documento
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Lista de Admins', 0, 1, 'C', 1);
$pdf->Ln(10);

if ($result->num_rows > 0) {
    // Crear la tabla de encabezados
    $tbl = '<style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #4CAF50;
                    color: white;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
            </style>
            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Título</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Dirección</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>';

    // Agregar los datos
    while ($row = $result->fetch_assoc()) {
        $tbl .= '<tr>
            <td>' . htmlspecialchars($row['id']) . '</td>
            <td>' . htmlspecialchars($row['nombre']) . '</td>
            <td>' . htmlspecialchars($row['apellido']) . '</td>
            <td>' . htmlspecialchars($row['cedula']) . '</td>
            <td>' . htmlspecialchars($row['correo']) . '</td>
            <td>' . htmlspecialchars($row['celular']) . '</td>
            <td>' . htmlspecialchars($row['titulo']) . '</td>
            <td>' . htmlspecialchars($row['fecha_de_nacimiento']) . '</td>
            <td>' . htmlspecialchars($row['direccion']) . '</td>
            <td>' . htmlspecialchars($row['rol']) . '</td>
        </tr>';
    }

    $tbl .= '</tbody></table>';

    // Escribir la tabla en el PDF
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // Salida del PDF
    $pdf->Output('admins.pdf', 'D');
} else {
    echo "No hay registros para exportar";
}
$conn->close();
?>
