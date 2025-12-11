<?php
require 'config.php';
$data = json_decode(file_get_contents("php://input"), true);

$driver_id = $data['driver_id'];
$course_id = $data['course_id'];

$stmt = $pdo->prepare("SELECT status, driver_id FROM coursesdeydem WHERE id=? FOR UPDATE");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    echo json_encode(["success" => false, "message" => "Course introuvable"]);
    exit;
}

if ($course['status'] != 'pending') {
    echo json_encode(["success" => false, "message" => "Course déjà prise"]);
    exit;
}

$update = $pdo->prepare("UPDATE coursesdeydem SET driver_id=?, status='accepted', accepted_at=NOW() WHERE id=?");
$update->execute([$driver_id, $course_id]);

echo json_encode(["success" => true, "message" => "Course acceptée"]);
