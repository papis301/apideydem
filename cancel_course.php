<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "config.php";

// Vérifie si id envoyé
if (!isset($_POST['course_id'])) {
    echo json_encode(["success" => false, "message" => "ID manquant"]);
    exit;
}

$course_id = intval($_POST['course_id']);

try {
    $pdo = new PDO("mysql:host=$HOST;dbname=$DB;charset=utf8", $USER, $PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $sql = "UPDATE coursesdeydem 
            SET status = 'cancelled', cancelled_at = NOW() 
            WHERE id = ? AND status = 'pending'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$course_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Course annulée"]);
    } else {
        echo json_encode(["success" => false, "message" => "Impossible d'annuler cette course"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
