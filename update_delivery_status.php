<?php
require_once "db.php";

$delivery_id = $_POST["delivery_id"] ?? 0;
$status = $_POST["status"] ?? "";

if (!$delivery_id || !$status) {
    echo "missing_params";
    exit;
}

$q = $pdo->prepare("UPDATE deliveries SET status=? WHERE id=?");
$ok = $q->execute([$status, $delivery_id]);

echo $ok ? "success" : "fail";
