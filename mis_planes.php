<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener los planes en los que el usuario está inscrito
$stmt = $conn->prepare("
    SELECT p.*, u.nombre as creador_nombre,
           (SELECT COUNT(*) FROM participantes WHERE plan_id = p.id) as participantes_actuales
    FROM planes p
    JOIN participantes pa ON p.id = pa.plan_id
    JOIN usuarios u ON p.creador_id = u.id
    WHERE pa.usuario_id = ?
    ORDER BY p.fecha DESC
");
$stmt->execute([$_SESSION['usuario_id']]);
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para truncar texto
function truncateText($text, $length = 100) {
    if (strlen($text) > $length) {
        return substr($text, 0, strrpos(substr($text, 0, $length), ' ')) . '...';
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Planes</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1><a href="index.php" style="color: white; text-decoration: none;">Planes</a></h1>
            <div class="nav-links">
                <a href="index.php">Todos los Planes</a>
                <a href="crear_plan.php" class="btn-crear">Crear Plan</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="welcome-section">
            <h2>Mis Planes Inscritos</h2>
            <p class="welcome-text">Aquí puedes ver todos los planes en los que te has inscrito.</p>
        </div>

        <div class="planes-container">
            <div class="planes-grid">
                <?php if (!empty($planes)): ?>
                    <?php foreach ($planes as $plan): ?>
                        <div class="plan-card">
                            <div class="plan-header">
                                <h3><?php echo htmlspecialchars($plan['titulo']); ?></h3>
                                <span class="creador">Por: <?php echo htmlspecialchars($plan['creador_nombre']); ?></span>
                            </div>
                            <div class="plan-content">
                                <p class="descripcion"><?php echo htmlspecialchars(truncateText($plan['descripcion'])); ?></p>
                                <p class="fecha">Fecha: <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($plan['fecha']))); ?></p>
                                <p class="lugar">Lugar: <?php echo htmlspecialchars($plan['lugar']); ?></p>
                                <div class="capacidad-badge">
                                    <span>Plazas: <?php echo $plan['participantes_actuales']; ?>/<?php echo htmlspecialchars($plan['capacidad_maxima']); ?></span>
                                </div>
                            </div>
                            <a href="ver_plan.php?id=<?php echo $plan['id']; ?>" class="btn-participar">
                                Ver detalles completos
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-planes">
                        <p>No estás inscrito en ningún plan todavía.</p>
                        <a href="index.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Ver planes disponibles</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Planes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>