<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================= 🔑 API KEYS =================
$mapboxToken = "";
$openaiApiKey = "";

$dish = isset($_GET['dish']) ? trim($_GET['dish']) : '';

if (!$dish) {
    echo json_encode(['error' => 'No dish specified']);
    exit;
}

// ================= STEP 1: Get real restaurant names from OpenAI =================
function getRestaurantsFromAI($dish, $apiKey) {
    $prompt = "You are a Karachi food expert. List 5 REAL, ACTUAL restaurants in Karachi, Pakistan that are well-known for serving excellent $dish. 
    
    For each restaurant, provide:
    1. Restaurant name (ONLY the real name, no descriptions)
    2. Street address or area in Karachi (be specific)
    3. One short appetizing reason (15 words max) why it's great for $dish
    
    Format your response EXACTLY like this JSON array (no other text):
    [
      {\"name\": \"Restaurant Name\", \"address\": \"Area, Street, Karachi\", \"reason\": \"Appetizing reason here\"},
      {\"name\": \"Restaurant Name\", \"address\": \"Area, Street, Karachi\", \"reason\": \"Appetizing reason here\"}
    ]
    
    IMPORTANT: Only list REAL restaurants that actually exist in Karachi. Do not make up names.";

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => "You are a Karachi food expert. Only provide real, existing restaurants. Respond ONLY with valid JSON array."],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.3,
        "max_tokens" => 500
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 25
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log("OpenAI Error: $response");
        return null;
    }

    $json = json_decode($response, true);
    
    if (!isset($json['choices'][0]['message']['content'])) {
        return null;
    }

    $content = $json['choices'][0]['message']['content'];
    error_log("OpenAI Restaurant Response: " . $content);
    
    // Clean the response to extract JSON
    $content = trim($content);
    // Remove markdown code blocks if present
    $content = preg_replace('/^```json\s*|\s*```$/', '', $content);
    $content = preg_replace('/^```\s*|\s*```$/', '', $content);
    
    $restaurants = json_decode($content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg());
        error_log("Content: " . $content);
        return null;
    }
    
    return $restaurants;
}

// ================= STEP 2: Geocode restaurants with Mapbox =================
function geocodeRestaurant($name, $address, $apiKey) {
    $searchQuery = $name . " " . $address . " Karachi Pakistan";
    $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . 
           urlencode($searchQuery) . ".json?" . http_build_query([
        'limit' => 1,
        'country' => 'pk',
        'access_token' => trim($apiKey)
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    
    if (isset($data['features'][0])) {
        $feature = $data['features'][0];
        $coords = $feature['geometry']['coordinates'];
        return [
            'lat' => $coords[1],
            'lng' => $coords[0],
            'address' => $feature['place_name']
        ];
    }
    
    return null;
}

// ================= MAIN EXECUTION =================
// Get restaurants from OpenAI
$restaurants = getRestaurantsFromAI($dish, $openaiApiKey);

if (!$restaurants || empty($restaurants)) {
    echo json_encode(['error' => 'Could not find restaurants for "' . htmlspecialchars($dish) . '". Please try a different dish.']);
    exit;
}

$output = [];

foreach ($restaurants as $restaurant) {
    $name = $restaurant['name'] ?? 'Unknown';
    $address = $restaurant['address'] ?? 'Karachi, Pakistan';
    $reason = $restaurant['reason'] ?? "Great place for $dish";
    
    // Add Karachi to address if not present
    if (stripos($address, 'Karachi') === false) {
        $address .= ', Karachi, Pakistan';
    }
    
    // Try to geocode, use approximate coordinates if fails
    $geocoded = geocodeRestaurant($name, $address, $mapboxToken);
    
    if ($geocoded) {
        $lat = $geocoded['lat'];
        $lng = $geocoded['lng'];
        $fullAddress = $geocoded['address'];
    } else {
        // Use Karachi center with small random offset
        $lat = 24.8607 + (rand(-30, 30) / 1000);
        $lng = 67.0011 + (rand(-30, 30) / 1000);
        $fullAddress = $address;
    }
    
    // Generate Google Maps link
    $mapLink = "https://www.google.com/maps/search/" . urlencode($name . " " . $address);
    
    $output[] = [
        "name" => $name,
        "address" => $fullAddress,
        "map" => $mapLink,
        "ai" => $reason
    ];
}

// Return results
echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
