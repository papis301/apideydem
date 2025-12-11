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


try {
    $q = $pdo->query("SELECT id, pickup_address, dropoff_address, price FROM coursesdeydem WHERE status='pending'");
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} 
catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
