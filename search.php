<?php
header('Content-Type: application/json');

$q = urlencode($_GET['q'] ?? '');

if (!$q) {
    echo json_encode(["error" => "missing q"]);
    exit;
}

$url = "https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&q=$q";

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: DeyDemApp/1.0 (contact@example.com)\r\n"
    ]
];

$context = stream_context_create($opts);
echo file_get_contents($url, false, $context);
