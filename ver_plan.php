<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$plan_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

// Obtener detalles del plan
$stmt = $conn->prepare("SELECT p.*, u.nombre as creador_nombre FROM planes p 
                       JOIN usuarios u ON p.creador_id = u.id 
                       WHERE p.id = ?");
$stmt->execute([$plan_id]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    header("Location: index.php");
    exit;
}

// Verificar si el usuario ya está inscrito
$stmt = $conn->prepare("SELECT * FROM participantes WHERE usuario_id = ? AND plan_id = ?");
$stmt->execute([$usuario_id, $plan_id]);
$ya_inscrito = $stmt->fetch() ? true : false;

// Obtener lista de participantes
$stmt = $conn->prepare("SELECT u.nombre FROM participantes p 
                       JOIN usuarios u ON p.usuario_id = u.id 
                       WHERE p.plan_id = ?");
$stmt->execute([$plan_id]);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar participantes actuales
$total_participantes = count($participantes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Plan - <?php echo htmlspecialchars($plan['titulo']); ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1><a href="index.php" style="color: white; text-decoration: none;">Planes</a></h1>
            <div class="nav-links">
                <a href="index.php">Volver</a>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="plan-detalle">
            <h2><?php echo htmlspecialchars($plan['titulo']); ?></h2>
            <div class="plan-info">
                <p class="creador">Creado por: <?php echo htmlspecialchars($plan['creador_nombre']); ?></p>
                <p class="descripcion"><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                <p class="fecha">Fecha: <?php echo date('d/m/Y H:i', strtotime($plan['fecha'])); ?></p>
                <p class="lugar">Lugar: <?php echo htmlspecialchars($plan['lugar']); ?></p>
                <p class="capacidad">Capacidad: <?php echo $total_participantes; ?>/<?php echo $plan['capacidad_maxima']; ?> participantes</p>
            </div>

            <div class="participantes">
                <h3>Participantes:</h3>
                <ul>
                    <?php foreach ($participantes as $participante): ?>
                        <li><?php echo htmlspecialchars($participante['nombre']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (!$ya_inscrito && $total_participantes < $plan['capacidad_maxima']): ?>
                <form action="inscribirse.php" method="POST" class="inscripcion-form">
                    <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
                    <button type="submit" class="btn-inscribirse">Inscribirse en este plan</button>
                </form>
            <?php elseif ($ya_inscrito): ?>
                <p class="ya-inscrito">Ya estás inscrito en este plan</p>
            <?php else: ?>
                <p class="plan-lleno">Este plan está completo</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>