<?php
header("Content-Type: application/json");
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Méthode invalide"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$latitude    = $data['latitude'] ?? null;
$longitude   = $data['longitude'] ?? null;
$type_lieu   = $data['type_lieu'] ?? null;
$description = $data['description'] ?? '';

if (!$latitude || !$longitude || !$type_lieu) {
    echo json_encode(["status" => "error", "message" => "Champs manquants"]);
    exit;
}

$sql = "INSERT INTO locations (latitude, longitude, type_lieu, description, datetime)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([$latitude, $longitude, $type_lieu, $description]);

echo json_encode([
    "status" => "success",
    "message" => "Emplacement enregistré"
]);
