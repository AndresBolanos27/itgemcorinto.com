<?php
// Habilitar reporte de errores solo en desarrollo (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir dependencias
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/verificar_sesion.php';

try {
    // Verificar sesión y permisos
    verificar_sesion();

    // Verificar conexión a la base de datos
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Procesar solo solicitudes POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método de solicitud no permitido.");
    }

    // Validar datos requeridos
    if (empty($_POST['grupo_id'])) {
        throw new Exception("El parámetro 'grupo_id' es requerido.");
    }

    // Obtener y validar el grupo seleccionado
    $grupo_seleccionado = (int)$_POST['grupo_id'];
    if ($grupo_seleccionado <= 0) {
        throw new Exception("El 'grupo_id' proporcionado no es válido.");
    }

    // Obtener el año académico actual
    $anio_academico = date('Y'); // Año actual sin tilde en la variable

    // Validar estructura de datos de las notas
    if (empty($_POST['notas']) || !is_array($_POST['notas'])) {
        throw new Exception("Los datos de notas no son válidos o están vacíos.");
    }

    // Iniciar transacción para garantizar la integridad de los datos
    $conn->begin_transaction();

    // Preparar la consulta para insertar o actualizar notas
    $stmt = $conn->prepare("
        INSERT INTO notas_estudiantes (
            estudiante_id, 
            materia_id, 
            grupo_id, 
            nota, 
            observacion, 
            año_académico
        ) VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nota = VALUES(nota),
            observacion = VALUES(observacion),
            año_académico = VALUES(año_académico)
    ");

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    // Vincular parámetros
    $stmt->bind_param(
        "iiidss", // Tipos de datos: i (entero), d (double), s (string)
        $estudiante_id,
        $materia_id,
        $grupo_seleccionado,
        $nota,
        $observacion,
        $anio_academico
    );

    // Procesar cada nota
    foreach ($_POST['notas'] as $estudiante_id => $materias) {
        foreach ($materias as $materia_id => $data) {
            // Validar y obtener la nota
            $nota = isset($data['nota']) ? (float)$data['nota'] : null;
            if ($nota === null || $nota < 1 || $nota > 5) {
                throw new Exception("La nota para el estudiante $estudiante_id, materia $materia_id no es válida. Debe estar entre 1 y 5.");
            }

            // Validar y obtener la observación
            $observacion = isset($data['observacion']) ? trim($data['observacion']) : '';
            if (strlen($observacion) > 255) {
                throw new Exception("La observación para el estudiante $estudiante_id, materia $materia_id es demasiado larga.");
            }

            // Ejecutar la consulta
            $estudiante_id = (int)$estudiante_id;
            $materia_id = (int)$materia_id;

            if (!$stmt->execute()) {
                throw new Exception("Error al guardar la nota para el estudiante $estudiante_id, materia $materia_id: " . $stmt->error);
            }
        }
    }

    // Confirmar la transacción si todo está correcto
    $conn->commit();

    // Redirección segura según el rol del usuario
    $location = match ($_SESSION['usuario_rol']) {
        'docente' => "notas_docentes?grupo_id=$grupo_seleccionado",
        'admin' => "calificaciones?grupo_id=$grupo_seleccionado",
        default => 'dashboard'
    };

    header("Location: $location");
    exit;

} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if (isset($conn) && $conn->begin_transaction()) {
        $conn->rollback();
    }

    // Registrar el error en el log del servidor
    error_log($e->getMessage());

    // Mostrar mensaje de error al usuario
    $_SESSION['error'] = "Error al procesar la solicitud: " . $e->getMessage();

    // Redirigir a una página de error
    header("Location: error_page");
    exit;

} finally {
    // Cerrar la conexión a la base de datos
    if (isset($conn)) {
        $conn->close();
    }
}