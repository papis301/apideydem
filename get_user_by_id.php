<?php
header('Content-Type: application/json');
require_once "db.php";

$user_id = $_POST['user_id'] ?? 0;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "missing_user_id"
    ]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        *
    FROM usersdeydem
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "user_not_found"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "user" => $user
]);
