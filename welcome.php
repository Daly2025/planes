<?php
require_once 'config/db.php';

// Obtener solo los últimos 3 planes
$stmt = $conn->query("SELECT * FROM planes ORDER BY fecha DESC LIMIT 3");
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Planes</title>
    <link rel="stylesheet" href="public/css/style.css">
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
        <div class="welcome-container">
            <h2>Bienvenido a Planes</h2>
            <p class="welcome-text">Descubre y únete a actividades emocionantes en tu área. ¡Regístrate para ver más planes y participar!</p>
        </div>

        <h3>Últimos Planes Disponibles</h3>
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

        <div class="cta-container">
            <h3>¿Quieres ver más planes?</h3>
            <p>Regístrate para ver todos los planes disponibles y poder participar en ellos.</p>
            <div class="cta-buttons">
                <a href="registro.php" class="btn-primary">Registrarse</a>
                <a href="login.php" class="btn-secondary">Iniciar Sesión</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Planes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>