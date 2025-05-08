<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $lugar = $_POST['lugar'];
    $capacidad_maxima = $_POST['capacidad_maxima'];
    $creador_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("INSERT INTO planes (titulo, descripcion, fecha, lugar, capacidad_maxima, creador_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $descripcion, $fecha, $lugar, $capacidad_maxima, $creador_id]);

    header("Location: panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Plan</title>
</head>
<body>
    <h1>Crear Nuevo Plan</h1>
    <form method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br>

        <label for="fecha">Fecha:</label>
        <input type="datetime-local" id="fecha" name="fecha" required><br>

        <label for="lugar">Lugar:</label>
        <input type="text" id="lugar" name="lugar" required><br>

        <label for="capacidad_maxima">Capacidad Máxima:</label>
        <input type="number" id="capacidad_maxima" name="capacidad_maxima" required><br>

        <button type="submit">Crear Plan</button>
    </form>
</body>
</html>