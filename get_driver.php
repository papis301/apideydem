<?php
header('Content-Type: application/json');

// Inclure la connexion PDO
require 'db.php';

// Vérifier que driver_id est fourni
if (!isset($_GET['driver_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'driver_id manquant'
    ]);
    exit;
}

$driver_id = (int) $_GET['driver_id'];

try {
    // Préparer la requête pour récupérer tous les champs
    $stmt = $pdo->prepare("SELECT * FROM usersdeydem WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $driver_id]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($driver) {
        echo json_encode([
            'success' => true,
            'driver' => $driver
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Chauffeur introuvable'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
