<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['plan_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$plan_id = $_POST['plan_id'];

try {
    // Verificar si ya está inscrito
    $stmt = $conn->prepare("SELECT * FROM participantes WHERE usuario_id = ? AND plan_id = ?");
    $stmt->execute([$usuario_id, $plan_id]);
    if ($stmt->fetch()) {
        header("Location: ver_plan.php?id=" . $plan_id);
        exit;
    }

    // Verificar capacidad del plan
    $stmt = $conn->prepare("SELECT p.capacidad_maxima, COUNT(pa.id) as total_participantes 
                           FROM planes p 
                           LEFT JOIN participantes pa ON p.id = pa.plan_id 
                           WHERE p.id = ? 
                           GROUP BY p.id, p.capacidad_maxima");
    $stmt->execute([$plan_id]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plan['total_participantes'] < $plan['capacidad_maxima']) {
        // Inscribir al usuario
        $stmt = $conn->prepare("INSERT INTO participantes (usuario_id, plan_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $plan_id]);
    }

    header("Location: ver_plan.php?id=" . $plan_id);
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}