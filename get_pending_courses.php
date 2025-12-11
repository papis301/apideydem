<?php
require 'config.php';

$stmt = $pdo->query("SELECT * FROM coursesdeydem WHERE status='pending'");
echo json_encode(["courses" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
