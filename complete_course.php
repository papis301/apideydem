<?php
require 'config.php';
$data = json_decode(file_get_contents("php://input"), true);

$course_id = $data['course_id'];

$pdo->prepare("UPDATE coursesdeydem SET status='completed', completed_at=NOW() WHERE id=?")
    ->execute([$course_id]);

// Ajouter au solde du chauffeur
$stmt = $pdo->prepare("SELECT driver_id, price FROM coursesdeydem WHERE id=?");
$stmt->execute([$course_id]);
$c = $stmt->fetch();

$pdo->prepare("UPDATE usersdeydem SET solde = solde + ? WHERE id=?")
    ->execute([$c['price'], $c['driver_id']]);

echo json_encode(["success" => true]);
