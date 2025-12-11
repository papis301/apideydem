<?php
header("Access-Control-Allow-Origin: *");
require "config.php";

try {
    $pdo = new PDO("mysql:host=$HOST;dbname=$DB;charset=utf8", $USER, $PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur connexion à la base"]);
    exit;
}

$delivery = $_POST["delivery_id"] ?? "";
$driver = $_POST["driver_id"] ?? "";

if (!$delivery || !$driver) {
    echo "Données manquantes";
    exit;
}

$stmt = $pdo->prepare("UPDATE coursesdeydem SET driver_id=?, status='accepted' WHERE id=?");
$stmt->execute([$driver, $delivery]);

echo "Course acceptée";
