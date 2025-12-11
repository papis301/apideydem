<?php
require 'config.php';
$data = json_decode(file_get_contents("php://input"), true);

$pdo->prepare("UPDATE coursesdeydem SET status='ongoing' WHERE id=?")
    ->execute([$data['course_id']]);

echo json_encode(["success" => true]);
