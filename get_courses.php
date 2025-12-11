<?php
header("Content-Type: application/json");

// DB connexion
// Connexion à la base via PDO
require_once "config.php";

try {
    $pdo = new PDO("mysql:host=$HOST;dbname=$DB;charset=utf8", $USER, $PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur connexion à la base"]);
    exit;
}

// Récupérer toutes les courses
$sql = "SELECT id, client_id, driver_id, pickup_address, dropoff_address, price, status, created_at 
        FROM coursesdeydem
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($courses);
?>
