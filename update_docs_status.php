<?php
require_once "db.php";

header('Content-Type: application/json');

$driver_id   = $_POST['driver_id'] ?? 0;
$docs_status = $_POST['docs_status'] ?? '';

$allowed = ['pending', 'approved', 'rejected'];

if (!$driver_id || !in_array($docs_status, $allowed)) {
    echo json_encode([
        "success" => false,
        "message" => "missing_or_invalid_params"
    ]);
    exit;
}

$sql = "
    UPDATE usersdeydem
    SET docs_status = ?,
        docs_sent_at = IF(? = 'pending', NOW(), docs_sent_at)
    WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([
    $docs_status,
    $docs_status,
    $driver_id
]);

echo json_encode([
    "success" => $ok,
    "driver_id" => $driver_id,
    "docs_status" => $docs_status
]);
