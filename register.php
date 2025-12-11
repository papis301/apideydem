<?php
header("Content-Type: application/json");

// ⚠️ Connexion à la base de données via PDO
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
$role = $_POST['role'] ?? 'client'; // valeur par défaut

if (empty($phone) || empty($password) || empty($role)) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

// Vérification si le téléphone existe déjà
$stmt = $pdo->prepare("SELECT id FROM usersdeydem WHERE phone = ? LIMIT 1");
$stmt->execute([$phone]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "message" => "Téléphone déjà utilisé"]);
    exit;
}

// Hash du mot de passe
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insertion dans la base
$stmt = $pdo->prepare("INSERT INTO usersdeydem (phone, password, type) VALUES (?, ?, ?)");
try {
    $stmt->execute([$phone, $passwordHash, $role]);
    echo json_encode(["success" => true, "message" => "Inscription réussie"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription"]);
}
?>
