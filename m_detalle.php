<?php
// 1. INICIO DE SESIÓN Y PROTECCIÓN DE RUTA
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_config.php';

// Validar que venga el aula por URL
if (!isset($_GET['aula']) || empty($_GET['aula'])) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h3>Error: No se seleccionó ninguna aula.</h3><a href='m_reporte.php'>Regresar</a></div>");
}

$aula_id = $_GET['aula'];


$sql = "SELECT 
            a.folio, 
            a.nombre AS nombre_completo,
            a.asiento,
            a.carrera,
            a.version,
            r.fecha_registro
        FROM aspirante2 a
        LEFT JOIN m_registro_asistencias r ON a.folio = r.folio
        WHERE a.aula = ?
        ORDER BY CAST(a.asiento AS UNSIGNED) ASC";

$stmt = $db->prepare($sql);
$stmt->bind_param("s", $aula_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Calcular totales rápidos
$total_alumnos = 0;
$presentes = 0;
$alumnos = [];

if ($resultado) {
    $total_alumnos = $resultado->num_rows;
    while($row = $resultado->fetch_assoc()) {
        if (!empty($row['fecha_registro'])) {
            $presentes++;
        }
        $alumnos[] = $row;
    }
}
$faltantes = $total_alumnos - $presentes;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Aula <?php echo htmlspecialchars($aula_id); ?> - FIMAZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --azul-marino: #132a42; 
            --fondo-claro: #f4f6f9; 
        }
        body { background-color: var(--fondo-claro); font-family: system-ui, -apple-system, sans-serif; }
        .navbar-custom { background-color: var(--azul-marino) !important; }
        .texto-marino { color: var(--azul-marino) !important; }
        .card-main { border-radius: 24px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        .estado-badge { width: 100px; font-weight: 500; }
        .table-custom-header th { background-color: var(--azul-marino) !important; color: white !important; border-bottom: none; font-weight: 600; letter-spacing: 0.5px;}
        .badge-folio { background-color: white; color: var(--azul-marino); border: 1px solid #dce1e5; }
        /* Badge decorativo opcional para la versión */
        .badge-version { background-color: #e2e8f0; color: #475569; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="m_reporte.php">
                <span class="fw-bold text-white">FIMAZ <span style="color: #90b4ce;">Control M</span></span>
            </a>
            <a href="m_reporte.php" class="btn btn-outline-light btn-sm rounded-pill px-3"><i class="bi bi-arrow-left"></i> Volver a Reportes</a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card card-main mb-4">
            <div class="card-body d-flex justify-content-between align-items-center bg-white p-4" style="border-radius: 24px;">
                <div>
                    <h2 class="mb-0 texto-marino fw-bold">Aula <?php echo htmlspecialchars($aula_id); ?></h2>
                    <p class="text-muted mb-0">Lista nominal de asistencia</p>
                </div>
                <div class="d-flex gap-4 text-center">
                    <div>
                        <h3 class="mb-0 fw-bold texto-marino"><?php echo $total_alumnos; ?></h3>
                        <small class="text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">ASIGNADOS</small>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold text-success"><?php echo $presentes; ?></h3>
                        <small class="text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">PRESENTES</small>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold text-danger"><?php echo $faltantes; ?></h3>
                        <small class="text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">FALTAN</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-main">
            <div class="card-body p-0" style="border-radius: 24px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="table-custom-header">
                            <tr>
                                <th class="text-center py-3" style="width: 80px;">ASIENTO</th>
                                <th style="width: 120px;">FOLIO</th>
                                <th>NOMBRE DEL ASPIRANTE / CARRERA</th>
                                <th class="text-center" style="width: 100px;">VERSIÓN</th>
                                <th class="text-center">HORA LLEGADA</th>
                                <th class="text-center">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php if (count($alumnos) > 0): ?>
                                <?php foreach($alumnos as $alumno): 
                                    $llego = !empty($alumno['fecha_registro']);
                                ?>
                                <tr style="border-bottom: 1px solid #f0f2f5;">
                                    <td class="text-center fw-bold fs-5 text-secondary"><?php echo $alumno['asiento']; ?></td>
                                    <td><span class="badge badge-folio p-2 rounded-3" style="font-size: 0.85rem;"><?php echo htmlspecialchars($alumno['folio']); ?></span></td>
                                    <td>
                                        <div class="fw-bold texto-marino"><?php echo htmlspecialchars($alumno['nombre_completo']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($alumno['carrera']); ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-version p-2 px-3 rounded-3 fs-6">
                                            <?php echo !empty($alumno['version']) ? htmlspecialchars($alumno['version']) : '-'; ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        <?php echo $llego ? date('h:i A', strtotime($alumno['fecha_registro'])) : '--:--'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($llego): ?>
                                            <span class="badge bg-success estado-badge p-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Presente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger estado-badge p-2 rounded-pill"><i class="bi bi-x-circle me-1"></i> Falta</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No hay alumnos asignados a esta aula en el padrón.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 mb-5 text-muted fw-semibold" style="font-size: 0.85rem;">
            Sistema de Monitoreo FIMAZ &copy; <?php echo date('Y'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>