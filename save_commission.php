<?php
require_once "db.php";

// =======================
// Récupération des données
// =======================
$delivery_id = isset($_POST['delivery_id']) ? intval($_POST['delivery_id']) : 0;
$driver_id   = isset($_POST['driver_id']) ? intval($_POST['driver_id']) : 0;
$amount      = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

// =======================
// Vérification des paramètres
// =======================
if ($delivery_id <= 0 || $driver_id <= 0 || $amount <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "missing_params"
    ]);
    exit;
}

// =======================
// Insertion commission
// =======================
try {

    $q = $pdo->prepare("
        INSERT INTO commissions (delivery_id, driver_id, amount)
        VALUES (?, ?, ?)
    ");

    $ok = $q->execute([$delivery_id, $driver_id, $amount]);

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "commission_saved" : "insert_failed"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "server_error",
        "error" => $e->getMessage()
    ]);
}
