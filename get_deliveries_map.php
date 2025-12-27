<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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

$q = $pdo->query("
    SELECT id,
        pickup_address,
        pickup_lat,
        pickup_lng,
        dropoff_address,
        dropoff_lat,
        dropoff_lng,
        price,
        client_id
    FROM coursesdeydem 
    WHERE status='pending'
");

echo json_encode($q->fetchAll(PDO::FETCH_ASSOC));
