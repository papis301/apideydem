<?php
require 'config.php';
$data = json_decode(file_get_contents("php://input"), true);

$driver_id = $data['driver_id'];
$lat = $data['lat'];
$lng = $data['lng'];

$stmt = $pdo->prepare("UPDATE usersdeydem SET last_lat=?, last_lng=? WHERE id=?");
$stmt->execute([$lat, $lng, $driver_id]);

echo json_encode(["success" => true]);
