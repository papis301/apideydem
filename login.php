<?php
header("Content-Type: application/json");

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

// Récupération des données POST
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

// Vérification si l'utilisateur existe
$stmt = $pdo->prepare("SELECT id, phone, type, password FROM usersdeydem WHERE phone = ? LIMIT 1");
$stmt->execute([$phone]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "Utilisateur introuvable"]);
    exit;
}

// Vérification du mot de passe
if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Mot de passe incorrect"]);
    exit;
}

// Connexion réussie
// On peut renvoyer les infos du user sans le password
$response = [
    "success" => true,
    "user" => [
        "id" => $user['id'],
        "phone" => $user['phone'],
        "type" => $user['type']
    ]
];

echo json_encode($response);
?>
