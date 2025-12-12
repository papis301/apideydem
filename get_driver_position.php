<?php
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

$id = $_GET["driver_id"];

$sql = $pdo->prepare("SELECT last_lat, last_lng FROM usersdeydem WHERE id = ?");
$sql->execute([$id]);

echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
?>
