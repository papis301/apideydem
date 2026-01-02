<?php
header('Content-Type: application/json');
require 'db.php';

if (!isset($_POST['driver_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "driver_id manquant"
    ]);
    exit;
}

$driver_id = intval($_POST['driver_id']);

$sql = "
    UPDATE usersdeydem
    SET docs_status = 'send',
        docs_sent_at = NOW()
    WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([$driver_id]);

echo json_encode([
    "success" => $ok
]);
