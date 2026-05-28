<?php
// 1. INICIO DE SESIÓN Y PROTECCIÓN DE RUTA
session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: login");
    exit;
}

require_once 'db_config.php';

/**
 * CONSULTA:
 * 1. Se obtiene el total de aspirantes asignados a cada aula desde la tabla 'aspirante2'.
 * 2. Contamos cuántos de esos aspirantes ya están en 'm_registro_asistencias'.
 */
$query = $db->query("
    SELECT 
        a.aula AS aula_id, 
        COUNT(a.folio) AS capacidad_real, 
        COUNT(r.folio) AS total_registrados
    FROM aspirante2 a
    LEFT JOIN m_registro_asistencias r ON a.folio = r.folio
    GROUP BY a.aula
    ORDER BY CAST(a.aula AS UNSIGNED) ASC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Control M</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --azul-marino: #132a42; 
            --fondo-claro: #f4f6f9; 
            --rojo-salir: #dc3545;
        }
        body { background-color: var(--fondo-claro); font-family: system-ui, -apple-system, sans-serif; }
        .navbar-custom { background-color: var(--azul-marino) !important; }
        
        /* ESTILO PARA EL LOGO  */
        .logo-nav {
            height: 40px;
            width: auto;
            margin-right: 10px;
            border-radius: 5px;
            background: white; 
            padding: 2px;
        }

        .texto-marino { color: var(--azul-marino) !important; }
        .card-main { border-radius: 24px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
        .table-custom-header th { background-color: var(--azul-marino) !important; color: white !important; border-bottom: none; font-weight: 600; letter-spacing: 0.5px;}
        .fila-aula { transition: all 0.2s; background-color: white; border-bottom: 1px solid #f0f2f5;}
        .fila-aula:hover { background-color: #eef2f6 !important; transform: scale(1.005); }
        .btn-detalle { border: 1.5px solid var(--azul-marino); color: var(--azul-marino); font-weight: 600; transition: all 0.2s; }
        .btn-detalle:hover { background-color: var(--azul-marino); color: white; }
        .progress { background-color: #e9ecef; border-radius: 50px; }
        .badge-aula { background-color: var(--azul-marino); color: white; min-width: 45px; }
        
        .btn-salir { 
            background-color: var(--rojo-salir); 
            color: white; 
            border: none; 
            transition: 0.3s;
        }
        .btn-salir:hover { 
            background-color: #a71d2a; 
            color: white; 
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="m_reporte">
                <!-- LOGO FIMAZ -->
                <img src="logo_fimaz.jpg" alt="Logo FIMAZ" class="logo-nav">
                <span class="fw-bold text-white">FIMAZ <span style="color: #90b4ce;">Control Escolar</span></span>
            </a>
            <a href="logout" class="btn btn-salir btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="card card-main overflow-hidden">
            <div class="bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="m-0 fw-bold texto-marino"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Estado General de Aulas</h3>
                        <p class="mb-0 text-muted mt-1">Resumen basado en aspirantes asignados por salón</p>
                    </div>
                    <i class="bi bi-bar-chart-line fs-1" style="color: #dce1e5;"></i>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="table-custom-header">
                            <tr>
                                <th class="text-center py-3" style="width: 100px;">AULA</th>
                                <th>ASIGNADOS</th>
                                <th class="text-center">REGISTRADOS</th>
                                <th class="text-center">FALTAN</th>
                                <th class="text-center" style="width: 250px;">ESTADO</th>
                                <th class="text-center">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php if($query && $query->num_rows > 0): ?>
                                <?php while($row = $query->fetch_assoc()): 
                                    $registrados = $row['total_registrados'];
                                    $capacidad = $row['capacidad_real']; 
                                    $faltan = max(0, $capacidad - $registrados);
                                    $porcentaje = ($capacidad > 0) ? ($registrados / $capacidad) * 100 : 0;
                                    
                                    $progresoVisual = min(100, $porcentaje);

                                    $barColor = "bg-success";
                                    if($porcentaje > 70) $barColor = "bg-warning";
                                    if($porcentaje >= 100) $barColor = "bg-danger";
                                ?>
                                <tr class="fila-aula">
                                    <td class="text-center py-3">
                                        <span class="badge badge-aula fs-5 p-2 shadow-sm rounded-3"><?php echo htmlspecialchars($row['aula_id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-semibold"><?php echo $capacidad; ?> Aspirantes</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-5 text-success"><?php echo $registrados; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-5 text-danger"><?php echo $faltan; ?></span>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 shadow-sm" style="height: 14px;">
                                                <div class="progress-bar <?php echo $barColor; ?> progress-bar-striped progress-bar-animated" 
                                                     role="progressbar" 
                                                     style="width: <?php echo $progresoVisual; ?>%">
                                                </div>
                                            </div>
                                            <span class="ms-3 small fw-bold texto-marino"><?php echo round($porcentaje); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="m_detalle?aula=<?php echo urlencode($row['aula_id']); ?>" class="btn btn-sm btn-detalle rounded-pill px-4">
                                            Detalles <i class="bi bi-chevron-right small"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No hay aspirantes cargados en la base de datos.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 mb-5 text-muted fw-semibold" style="font-size: 0.85rem;">
            Sistema de monitoreo FIMAZ &copy; 2026
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>