<?php
require_once "db.php";

$delivery_id = $_POST['delivery_id'] ?? 0;
$driver_id   = $_POST['driver_id'] ?? 0;
$status      = $_POST['status'] ?? '';
$lat         = $_POST['lat'] ?? null;
$lng         = $_POST['lng'] ?? null;

if (!$delivery_id || !$driver_id || !$status || !$lat || !$lng) {
    echo json_encode(["success"=>false,"message"=>"missing_params"]);
    exit;
}

$q = $pdo->prepare("
    INSERT INTO delivery_tracking (delivery_id, driver_id, status, lat, lng)
    VALUES (?, ?, ?, ?, ?)
");

$ok = $q->execute([$delivery_id, $driver_id, $status, $lat, $lng]);

echo json_encode(["success"=>$ok]);
