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

$phone = $_POST["phone"] ?? "";
$password = $_POST["password"] ?? "";
$type = $_POST["type"] ?? "driver";

if (empty($phone) || empty($password)) {
    echo "Champs manquants";
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

// Vérifier doublon
$check = $pdo->prepare("SELECT id FROM usersdeydem WHERE phone=?");
$check->execute([$phone]);

if ($check->rowCount() > 0) {
    echo "Téléphone déjà utilisé";
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO usersdeydem (phone, password, type, status)
    VALUES (?, ?, ?, 'blocked')
");
$stmt->execute([$phone, $hash, $type]);

echo "OK";
