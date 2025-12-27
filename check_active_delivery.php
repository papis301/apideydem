<?php
require_once "db.php";

$driver_id = $_GET['driver_id'] ?? null;

if (!$driver_id) {
    echo json_encode(["success" => false, "message" => "driver_id manquant"]);
    exit;
}

$sql = "
SELECT *
FROM coursesdeydem
WHERE driver_id = ?
AND status IN ('accepted', 'ongoing')
LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$driver_id]);
$delivery = $stmt->fetch(PDO::FETCH_ASSOC);

if ($delivery) {
    echo json_encode([
        "success" => true,
        "has_delivery" => true,
        "delivery" => $delivery
    ]);
} else {
    echo json_encode([
        "success" => true,
        "has_delivery" => false
    ]);
}
