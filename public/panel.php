<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener planes creados por el usuario
$stmt = $conn->prepare("SELECT * FROM planes WHERE creador_id = ?");
$stmt->execute([$usuario_id]);
$planes_creados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener planes a los que el usuario se ha apuntado
$stmt = $conn->prepare("SELECT p.* FROM planes p JOIN participantes pa ON p.id = pa.plan_id WHERE pa.usuario_id = ?");
$stmt->execute([$usuario_id]);
$planes_apuntados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
</head>
<body>
    <h1>Mis Planes</h1>
    <h2>Planes Creados</h2>
    <ul>
        <?php foreach ($planes_creados as $plan): ?>
            <li>
                <h3><?php echo htmlspecialchars($plan['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                <p>Fecha: <?php echo htmlspecialchars($plan['fecha']); ?></p>
                <p>Lugar: <?php echo htmlspecialchars($plan['lugar']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Planes Apuntados</h2>
    <ul>
        <?php foreach ($planes_apuntados as $plan): ?>
            <li>
                <h3><?php echo htmlspecialchars($plan['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                <p>Fecha: <?php echo htmlspecialchars($plan['fecha']); ?></p>
                <p>Lugar: <?php echo htmlspecialchars($plan['lugar']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="crear_plan.php">Crear Nuevo Plan</a>
</body>
</html>