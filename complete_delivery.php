<?php
require "db.php";

$delivery_id = $_POST['delivery_id'];
$driver_id   = $_POST['driver_id'];

$COMMISSION_RATE = 0.20;

// 1️⃣ Récupérer la course
$stmt = $pdo->prepare("SELECT price FROM deliveries WHERE id=?");
$stmt->execute([$delivery_id]);
$delivery = $stmt->fetch(PDO::FETCH_ASSOC);

$price = (int)$delivery['price'];

// 2️⃣ Calculs
$commission = round($price * $COMMISSION_RATE);
$driver_gain = $price - $commission;

// 3️⃣ Mise à jour livraison
$pdo->prepare("
    UPDATE deliveries 
    SET status='completed',
        commission=?,
        driver_gain=?
    WHERE id=?
")->execute([$commission, $driver_gain, $delivery_id]);

// 4️⃣ Mise à jour chauffeur
$pdo->prepare("
    UPDATE drivers 
    SET 
        solde = solde - ?, 
        total_courses = total_courses + 1,
        courses_terminees = courses_terminees + 1,
        courses_en_cours = courses_en_cours - 1
    WHERE id=?
")->execute([$commission, $driver_id]);

// 5️⃣ Historique commission
$pdo->prepare("
    INSERT INTO commissions (delivery_id, driver_id, amount)
    VALUES (?, ?, ?)
")->execute([$delivery_id, $driver_id, $commission]);

// 6️⃣ Vérifier blocage
$stmt = $pdo->prepare("SELECT solde FROM drivers WHERE id=?");
$stmt->execute([$driver_id]);
$driver = $stmt->fetch(PDO::FETCH_ASSOC);

if ($driver['solde'] < 0) {
    $pdo->prepare("UPDATE drivers SET status='blocked' WHERE id=?")
        ->execute([$driver_id]);
}

echo json_encode([
    "success" => true,
    "price" => $price,
    "commission" => $commission,
    "gain_driver" => $driver_gain
]);
