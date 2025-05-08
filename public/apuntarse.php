<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verificar si el usuario ya está apuntado al plan
    $stmt = $conn->prepare("SELECT * FROM participantes WHERE usuario_id = ? AND plan_id = ?");
    $stmt->execute([$usuario_id, $plan_id]);
    $participante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$participante) {
        // Verificar si el plan está lleno
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM participantes WHERE plan_id = ?");
        $stmt->execute([$plan_id]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        $stmt = $conn->prepare("SELECT capacidad_maxima FROM planes WHERE id = ?");
        $stmt->execute([$plan_id]);
        $capacidad_maxima = $stmt->fetch(PDO::FETCH_ASSOC)['capacidad_maxima'];

        if ($count < $capacidad_maxima) {
            $stmt = $conn->prepare("INSERT INTO participantes (usuario_id, plan_id) VALUES (?, ?)");
            $stmt->execute([$usuario_id, $plan_id]);
        }
    }

    header("Location: panel.php");
    exit;
}
?>