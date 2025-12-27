<?php
require "db.php";

$driver_id = $_GET['driver_id'];

$stmt = $pdo->prepare("SELECT solde, bonus_solde FROM usersdeydem WHERE id=?");
$stmt->execute([$driver_id]);

$driver = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "solde" => (int)$driver['solde'],
    "bonus_solde" => (int)$driver['bonus_solde']
]);
