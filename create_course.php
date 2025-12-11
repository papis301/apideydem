<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "config.php";

try {
    $pdo = new PDO(
        "mysql:host=$HOST;dbname=$DB;charset=utf8",
        $USER,
        $PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur PDO"]);
    exit;
}

$client_id    = intval($_POST['client_id']);
$pickup       = $_POST['pickup'];
$dropoff      = $_POST['dropoff'];
$pickup_lat   = floatval($_POST['pickup_lat']);
$pickup_lng   = floatval($_POST['pickup_lng']);
$drop_lat     = floatval($_POST['drop_lat']);
$drop_lng     = floatval($_POST['drop_lng']);
$vehicle_type = $_POST['vehicle_type'];

$distance_km = floatval(str_replace(",", ".", $_POST['distance_km']));
$price = intval(preg_replace('/\D/', '', $_POST['price']));

if (
    empty($client_id) || empty($pickup) || empty($dropoff) ||
    empty($pickup_lat) || empty($pickup_lng) ||
    empty($drop_lat) || empty($drop_lng)
) {
    echo json_encode(["success" => false, "message" => "Champs manquants"]);
    exit;
}

file_put_contents("debug.txt", print_r($_POST, true));

try {
    $stmt = $pdo->prepare("
        INSERT INTO coursesdeydem(
            client_id,
            pickup_address,
            pickup_lat,
            pickup_lng,
            dropoff_address,
            dropoff_lat,
            dropoff_lng,
            distance_km,
            vehicle_type,
            price
        ) VALUES (?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $client_id,
        $pickup,
        $pickup_lat,
        $pickup_lng,
        $dropoff,
        $drop_lat,
        $drop_lng,
        $distance_km,
        $vehicle_type,
        $price
    ]);

    echo json_encode(["success" => true, "message" => "Course enregistrée"]);

} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur SQL", "error" => $e->getMessage()]);
}
