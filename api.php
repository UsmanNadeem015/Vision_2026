<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🔑 ADD YOUR KEYS
$googleApiKey = "";
$openaiApiKey = "";

$dish = $_GET['dish'] ?? '';

if (!$dish) {
    echo json_encode(['error' => 'No dish specified']);
    exit;
}

// 📍 Karachi location
$location = "24.8607,67.0011";
$radius = "5000";

// ================= GOOGLE PLACES =================
function callGooglePlaces($dish, $location, $radius, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?" . http_build_query([
        'query' => "best " . $dish,
        'location' => $location,
        'radius' => $radius,
        'type' => 'restaurant',
        'key' => $apiKey
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// ================= OPENAI =================
function callOpenAI($restaurantNames, $dish, $apiKey) {

    $prompt = "For each restaurant below, give ONE short reason why it is a great place to eat $dish. Keep each response under 15 words.\n\n";

    foreach ($restaurantNames as $name) {
        $prompt .= "- $name\n";
    }

    $data = [
        "model" => "gpt-4.1-mini",
        "messages" => [
            ["role" => "system", "content" => "You are a concise food critic."],
            ["role" => "user", "content" => $prompt]
        ],
        "max_tokens" => 200,
        "temperature" => 0.7
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 15
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result['choices'][0]['message']['content'])) {
        return [];
    }

    // Split responses line by line
    $lines = explode("\n", trim($result['choices'][0]['message']['content']));
    return array_values(array_filter($lines));
}

// ================= MAIN =================

$placesData = callGooglePlaces($dish, $location, $radius, $googleApiKey);

if (!isset($placesData['results']) || empty($placesData['results'])) {
    echo json_encode(['error' => 'No restaurants found']);
    exit;
}

$restaurants = array_slice($placesData['results'], 0, 5);

// Extract names
$names = array_map(fn($p) => $p['name'], $restaurants);

// Get AI explanations (ONE call)
$aiResponses = callOpenAI($names, $dish, $openaiApiKey);

$results = [];

foreach ($restaurants as $index => $place) {

    $name = $place['name'] ?? 'Unknown';
    $rating = $place['rating'] ?? 'N/A';
    $address = $place['formatted_address'] ?? 'No address';
    $totalRatings = $place['user_ratings_total'] ?? 0;

    $priceLevel = isset($place['price_level']) 
        ? str_repeat('$', $place['price_level']) 
        : 'N/A';

    $mapUrl = "https://www.google.com/maps/search/?api=1&query=" 
        . urlencode($name . ' ' . $address);

    // Photo
    $photoUrl = '';
    if (isset($place['photos'][0]['photo_reference'])) {
        $photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=" 
            . $place['photos'][0]['photo_reference'] 
            . "&key=" . $googleApiKey;
    }

    $results[] = [
        "name" => $name,
        "rating" => $rating,
        "address" => $address,
        "price" => $priceLevel,
        "map" => $mapUrl,
        "photo" => $photoUrl,
        "total_ratings" => $totalRatings,
        "ai" => $aiResponses[$index] ?? "Popular spot for $dish."
    ];
}

echo json_encode($results);
?>