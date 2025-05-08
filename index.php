<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: welcome.php");
    exit;
}

$stmt = $conn->query("SELECT p.*, u.nombre as creador_nombre, 
                     (SELECT COUNT(*) FROM participantes WHERE plan_id = p.id) as participantes_actuales 
                     FROM planes p 
                     JOIN usuarios u ON p.creador_id = u.id 
                     ORDER BY p.fecha DESC");
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planes - Panel Principal</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1>Planes</h1>
            <div class="nav-links">
                <a href="crear_plan.php" class="btn-crear">Crear Plan</a>
                <a href="mis_planes.php">Mis Planes</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="welcome-section">
            <h2>Panel de Planes</h2>
            <p class="welcome-text">Bienvenido a tu panel personal. Aquí puedes ver todos los planes disponibles y participar en ellos.</p>
        </div>

        <div class="planes-container">
            <h3>Planes Disponibles</h3>
            <div class="planes-grid">
                <?php if (!empty($planes)): ?>
                    <?php foreach ($planes as $plan): ?>
                        <div class="plan-card">
                            <div class="plan-header">
                                <h3><?php echo htmlspecialchars($plan['titulo']); ?></h3>
                                <span class="creador">Por: <?php echo htmlspecialchars($plan['creador_nombre']); ?></span>
                            </div>
                            <div class="plan-content">
                                <p class="descripcion"><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                                <p class="fecha"><i class="far fa-calendar"></i> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($plan['fecha']))); ?></p>
                                <p class="lugar"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($plan['lugar']); ?></p>
                                <div class="capacidad-badge">
                                    <span>Plazas: <?php echo $plan['participantes_actuales']; ?>/<?php echo htmlspecialchars($plan['capacidad_maxima']); ?></span>
                                </div>
                            </div>
                            <a href="ver_plan.php?id=<?php echo $plan['id']; ?>" class="btn-participar">
                                Ver detalles e inscribirse
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-planes">No hay planes disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Planes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>