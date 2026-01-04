<?php
require_once "db.php";

header('Content-Type: application/json');

$delivery_id = $_POST['delivery_id'] ?? 0;
$driver_id   = $_POST['driver_id'] ?? 0;
$status      = $_POST['status'] ?? '';
$lat         = $_POST['lat'] ?? null;
$lng         = $_POST['lng'] ?? null;

if (!$delivery_id || !$driver_id || !$status || !$lat || !$lng) {
    echo json_encode(["success"=>false,"message"=>"missing_params"]);
    exit;
}

/**
 * 📏 Calcul distance (Haversine)
 */
function distance($lat1, $lon1, $lat2, $lon2) {
    $earth = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    return 2 * $earth * asin(sqrt($a));
}

/**
 * 🔍 Vérification dernière position chauffeur
 */
$q = $pdo->prepare("
    SELECT last_lat, last_lng, last_location_at, bloque_par_admin
    FROM usersdeydem
    WHERE id = ?
");
$q->execute([$driver_id]);
$driver = $q->fetch(PDO::FETCH_ASSOC);

if ($driver && $driver['last_lat']) {

    $dist = distance(
        $driver['last_lat'],
        $driver['last_lng'],
        $lat,
        $lng
    );

    $seconds = time() - strtotime($driver['last_location_at']);

    // 🚨 FRAUDE : déplacement impossible
    if ($dist > 5000 && $seconds < 120) {

        $pdo->prepare("
            UPDATE usersdeydem SET
                bloque_par_admin = 1,
                status = 'blocked',
                security_message = ?
            WHERE id = ?
        ")->execute([
            "Connexion suspecte détectée. Localisation multiple en simultané.",
            $driver_id
        ]);

        echo json_encode([
            "success" => false,
            "blocked" => true,
            "reason"  => "multi_location",
            "message" => "Compte bloqué pour activité suspecte"
        ]);
        exit;
    }
}

/**
 * ✅ Enregistrement tracking (TON CODE ORIGINAL)
 */
$q = $pdo->prepare("
    INSERT INTO delivery_tracking (delivery_id, driver_id, status, lat, lng)
    VALUES (?, ?, ?, ?, ?)
");

$ok = $q->execute([
    $delivery_id,
    $driver_id,
    $status,
    $lat,
    $lng
]);

/**
 * ✅ Mise à jour dernière position chauffeur
 */
$pdo->prepare("
    UPDATE usersdeydem SET
        last_lat = ?,
        last_lng = ?,
        last_location_at = NOW()
    WHERE id = ?
")->execute([$lat, $lng, $driver_id]);

echo json_encode([
    "success" => $ok,
    "blocked" => false
]);

