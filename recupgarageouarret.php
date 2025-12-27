<?php
header("Content-Type: application/json");
require_once "db.php";

$sql = "SELECT id, latitude, longitude, type_lieu, description, datetime
        FROM locations
        ORDER BY datetime DESC";

$stmt = $pdo->query($sql);
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $locations
]);
