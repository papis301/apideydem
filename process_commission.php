<?php
require_once "db.php";

$delivery_id = intval($_POST['delivery_id'] ?? 0);
$driver_id   = intval($_POST['driver_id'] ?? 0);
$commission  = floatval($_POST['commission'] ?? 0);

if ($delivery_id <= 0 || $driver_id <= 0 || $commission <= 0) {
    echo json_encode(["success"=>false,"message"=>"missing_params"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1️⃣ Enregistrer la commission
    $q1 = $pdo->prepare("
        INSERT INTO commissions (delivery_id, driver_id, amount)
        VALUES (?, ?, ?)
    ");
    $q1->execute([$delivery_id, $driver_id, $commission]);

    // 2️⃣ Retirer la commission du solde chauffeur
    $q2 = $pdo->prepare("
        UPDATE usersdeydem
        SET solde = solde - ?
        WHERE id = ?
    ");
    $q2->execute([$commission, $driver_id]);

    $pdo->commit();

    echo json_encode(["success"=>true]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        "success"=>false,
        "message"=>"transaction_failed",
        "error"=>$e->getMessage()
    ]);
}
