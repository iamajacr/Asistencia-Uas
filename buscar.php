<?php
header('Content-Type: application/json');
require_once 'db_config.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$valor = isset($_GET['valor']) ? strtoupper(trim($_GET['valor'])) : '';
$apellidoP = isset($_GET['apellido_paterno']) ? strtoupper(trim($_GET['apellido_paterno'])) : '';
$apellidoM = isset($_GET['apellido_materno']) ? strtoupper(trim($_GET['apellido_materno'])) : '';
$nombreInput = isset($_GET['nombre']) ? strtoupper(trim($_GET['nombre'])) : '';

$tabla = "aspirante2"; 

if ($tipo === 'folio') {
    if (empty($valor)) {
        echo json_encode(['success' => false, 'message' => 'Falta el folio']);
        exit;
    }

    $stmt = $db->prepare("SELECT folio, nombre, aula, carrera, asiento FROM $tabla WHERE folio = ?");
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // AQUI SE ENCUENTRA O NO AL ALUMNO
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Folio no encontrado']);
    }
    $stmt->close();

} elseif ($tipo === 'apellidos') {
    if (empty($apellidoP) || empty($nombreInput)) {
        echo json_encode(['success' => false, 'message' => 'El Apellido Paterno y el Nombre son obligatorios.']);
        exit;
    }
    
    $nombreCompletoBusqueda = "%$apellidoP%$apellidoM%$nombreInput%";
    $sql = "SELECT folio, nombre, aula, carrera, asiento FROM $tabla WHERE nombre LIKE ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $nombreCompletoBusqueda);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 1) {
        echo json_encode(['success' => false, 'message' => 'Se encontraron varios alumnos. Por favor use el FOLIO.']);
    } elseif ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró al alumno.']);
    }
    $stmt->close();
}
$db->close();
?>