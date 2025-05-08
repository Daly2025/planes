<?php
require_once 'config/db.php';

$stmt = $conn->query("SELECT * FROM planes ORDER BY fecha DESC LIMIT 10");
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1>Planes</h1>
            <div class="nav-links">
                <a href="login.php">Iniciar Sesión</a>
                <a href="registro.php">Registrarse</a>
            </div>
        </nav>
    </header>

    <main>
        <h2>Últimos Planes</h2>
        <div class="planes-grid">
            <?php if (!empty($planes)): ?>
                <?php foreach ($planes as $plan): ?>
                    <div class="plan-card">
                        <h3><?php echo htmlspecialchars($plan['titulo']); ?></h3>
                        <p class="descripcion"><?php echo htmlspecialchars($plan['descripcion']); ?></p>
                        <p class="fecha">Fecha: <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($plan['fecha']))); ?></p>
                        <p class="lugar">Lugar: <?php echo htmlspecialchars($plan['lugar']); ?></p>
                        <p class="capacidad">Capacidad: <?php echo htmlspecialchars($plan['capacidad_maxima']); ?> personas</p>
                        <a href="login.php" class="btn-participar">Iniciar sesión para participar</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay planes disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Planes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>