<?php
session_start();
require_once 'config/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $lugar = $_POST['lugar'];
    $capacidad = $_POST['capacidad'];
    $creador_id = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($descripcion) || empty($fecha) || empty($lugar) || empty($capacidad)) {
        $error = "Todos los campos son obligatorios";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO planes (titulo, descripcion, fecha, lugar, capacidad_maxima, creador_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $descripcion, $fecha, $lugar, $capacidad, $creador_id]);
            $success = "Plan creado exitosamente";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error al crear el plan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Plan</title>
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
        <div class="form-container">
            <h2>Crear Nuevo Plan</h2>
            
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <form method="POST" class="create-plan-form">
                <div>
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div>
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>

                <div>
                    <label for="fecha">Fecha y Hora:</label>
                    <input type="datetime-local" id="fecha" name="fecha" required>
                </div>

                <div>
                    <label for="lugar">Lugar:</label>
                    <input type="text" id="lugar" name="lugar" required>
                </div>

                <div>
                    <label for="capacidad">Capacidad Máxima:</label>
                    <input type="number" id="capacidad" name="capacidad" min="1" required>
                </div>

                <button type="submit" class="btn-crear">Crear Plan</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Planes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>