<?php
require_once "db.php";

$driver_id = $_POST['driver_id'] ?? 0;
$action = $_POST['action'] ?? '';

if (!$driver_id || !in_array($action, ['approve','reject'])) {
    echo json_encode(["success"=>false]);
    exit;
}

if ($action == "approve") {
    $docs = "approved";
    $status = "active";
} else {
    $docs = "rejected";
    $status = "blocked";
}

$q = $pdo->prepare("
    UPDATE usersdeydem
    SET docs_status=?, status=?
    WHERE id=?
");

$ok = $q->execute([$docs, $status, $driver_id]);

echo json_encode(["success"=>$ok]);
