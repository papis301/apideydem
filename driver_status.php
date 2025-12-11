<?php
require 'config.php';
$data = json_decode(file_get_contents("php://input"), true);

$driver_id = $data['driver_id'];
$status = $data['is_online']; // 1 ou 0

$pdo->prepare("UPDATE usersdeydem SET is_online=? WHERE id=? AND type='driver'")
    ->execute([$status, $driver_id]);

echo json_encode(["success" => true]);
