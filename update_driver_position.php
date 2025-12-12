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


$driver_id = $_POST["driver_id"] ?? null;
$lat = $_POST["lat"] ?? null;
$lng = $_POST["lng"] ?? null;

if (!$driver_id || !$lat || !$lng) {
    echo json_encode(["success" => false, "message" => "Paramètres manquants"]);
    exit;
}

$sql = $pdo->prepare("
    UPDATE usersdeydem 
    SET 
        last_lat = ?, 
        last_lng = ?, 
        is_online = 1,
        last_login = NOW()
    WHERE id = ?
");
$sql->execute([$lat, $lng, $driver_id]);

echo json_encode(["success" => true]);
?>
