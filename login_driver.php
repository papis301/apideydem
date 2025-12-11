<?php
header('Content-Type: application/json');
require 'config.php';

try {
    $pdo = new PDO("mysql:host=$HOST;dbname=$DB;charset=utf8", $USER, $PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur connexion à la base"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$phone = $data['phone'] ?? '';
$password = $data['password'] ?? '';

if (!$phone || !$password) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usersdeydem WHERE phone = ? AND type='driver' LIMIT 1");
$stmt->execute([$phone]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Identifiants invalides"]);
    exit;
}

// Mise à jour connexion
$pdo->prepare("UPDATE usersdeydem SET last_login=NOW(), is_online=1 WHERE id=?")
    ->execute([$user['id']]);

echo json_encode([
    "success" => true,
    "driver" => $user
]);
