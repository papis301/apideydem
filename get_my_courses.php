<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "db.php"; // connexion PDO

// 🔒 Vérification
if (!isset($_POST['client_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "client_id manquant"
    ]);
    exit;
}

$client_id = intval($_POST['client_id']);

try {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            client_id,
            driver_id,
            pickup_address,
            pickup_lat,
            pickup_lng,
            dropoff_address,
            dropoff_lat,
            dropoff_lng,
            distance_km,
            vehicle_type,
            price,
            status,
            created_at,
            accepted_at,
            completed_at,
            cancelled_at
        FROM coursesdeydem
        WHERE client_id = ?
        ORDER BY id DESC
    ");

    $stmt->execute([$client_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($courses);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
