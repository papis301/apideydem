<?php
header('Content-Type: application/json');
require 'db.php';

if (!isset($_GET['driver_id'])) {
    echo json_encode(["success"=>false]);
    exit;
}

$driver_id = intval($_GET['driver_id']);

$q = $pdo->prepare("
    SELECT docs_status
    FROM usersdeydem
    WHERE id = ?
");
$q->execute([$driver_id]);

$user = $q->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode([
        "success" => true,
        "docs_status" => $user['docs_status']
    ]);
} else {
    echo json_encode(["success"=>false]);
}
