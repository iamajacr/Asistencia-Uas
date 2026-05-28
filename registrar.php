<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Recibir datos por POST
$data = json_decode(file_get_contents('php://input'), true);
$folio = isset($data['folio']) ? $data['folio'] : '';
$aula = isset($data['aula']) ? $data['aula'] : '';

if (empty($folio) || empty($aula)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Verificar duplicado
$check_stmt = $db->prepare("SELECT id FROM m_registro_asistencias WHERE folio = ?");
$check_stmt->bind_param("s", $folio);
$check_stmt->execute();
$check_res = $check_stmt->get_result();

if ($check_res->num_rows == 0) {
    $stmt_m = $db->prepare("INSERT INTO m_registro_asistencias (folio, m_aula) VALUES (?, ?)");
    $stmt_m->bind_param("ss", $folio, $aula);
    if ($stmt_m->execute()) {
        echo json_encode(['success' => true, 'message' => 'Asistencia registrada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar']);
    }
    $stmt_m->close();
} else {
    echo json_encode(['success' => false, 'message' => 'El alumno ya estaba registrado']);
}

$check_stmt->close();
$db->close();
?>