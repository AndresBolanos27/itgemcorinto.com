<?php
include_once __DIR__ . '/../config/database.php';

if (isset($_POST['grupo_id'])) {
    $grupo_id = $_POST['grupo_id'];

    // Consulta para obtener las materias asignadas a este grupo
    $sql_materias = "SELECT m.id, m.materia 
                     FROM materias m
                     JOIN grupo_materias gm ON m.id = gm.materia_id
                     WHERE gm.grupo_id = ?";
    $stmt = $conn->prepare($sql_materias);
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['materia'] . '</td>
                    <td><button class="borrar-materia" data-id="' . $row['id'] . '">Borrar</button></td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="2">No hay materias asignadas</td></tr>';
    }
    $stmt->close();
} else {
    echo '<tr><td colspan="2">Error al obtener las materias</td></tr>';
}
$conn->close();
?>
