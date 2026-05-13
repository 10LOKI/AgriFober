<?php
// Quick API test script for Agriforb
$baseUrl = 'http://127.0.0.1:8001/api';

echo "=== Agriforb API Test ===\n\n";

// Test 1: Register farmer
echo "1. Registering farmer...\n";
$registerData = [
    'username' => 'jean_dupont',
    'name' => 'Jean Dupont',
    'email' => 'jean@example.com',
    'password' => 'SecurePass123!',
    'password_confirmation' => 'SecurePass123!',
    'region' => 'Dakar',
    'experience_level' => 'intermediaire'
];

$ch = curl_init("$baseUrl/register");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($registerData),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_FOLLOWLOCATION => false
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP $httpCode\n";
$data = json_decode($response, true);
if (isset($data['token'])) {
    echo "Token: " . substr($data['token'], 0, 20) . "...\n";
    echo "User ID: " . ($data['user']['id'] ?? 'N/A') . "\n\n";
    $token = $data['token'];
    
    // Test 2: Get profile
    echo "2. Getting farmer profile...\n";
    $ch = curl_init("$baseUrl/farmer/profile");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP $httpCode\n";
    $profile = json_decode($response, true);
    echo "Statistics: " . json_encode($profile['data']['statistics'] ?? [], JSON_PRETTY_PRINT) . "\n\n";
    
    // Test 3: List cultures (catalogue)
    echo "3. Listing cultures catalogue...\n";
    $ch = curl_init("$baseUrl/cultures");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP $httpCode\n";
    $cultures = json_decode($response, true);
    echo "Available cultures: " . count($cultures['data'] ?? []) . "\n";
    if (isset($cultures['data'][0])) {
        echo "Sample: " . $cultures['data'][0]['nom_commun'] . "\n\n";
    }
    
    // Test 4: List products
    echo "4. Listing products catalogue...\n";
    $ch = curl_init("$baseUrl/products");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP $httpCode\n";
    $products = json_decode($response, true);
    echo "Available products: " . count($products['data'] ?? []) . "\n\n";
    
    // Test 5: Create parcel
    echo "5. Creating a new parcel...\n";
    $parcelData = [
        'nom' => 'Parcelle Nord',
        'surface' => 3.5,
        'status' => 'grow',
        'latitude' => 14.7167,
        'longitude' => -17.4678
    ];
    $ch = curl_init("$baseUrl/parcels");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($parcelData),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ]
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP $httpCode\n";
    $parcel = json_decode($response, true);
    if (isset($parcel['data']['id'])) {
        echo "Parcel ID: " . $parcel['data']['id'] . "\n";
        echo "Parcel Name: " . $parcel['data']['nom'] . "\n\n";
        $parcelId = $parcel['data']['id'];
        
        // Test 6: Get parcel weather
        echo "6. Getting parcel weather...\n";
        $ch = curl_init("$baseUrl/parcels/$parcelId/weather");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "HTTP $httpCode\n";
        $weather = json_decode($response, true);
        echo "Weather: " . json_encode($weather['data']['weather'] ?? [], JSON_PRETTY_PRINT) . "\n\n";
        
        // Test 7: AI Chat
        echo "7. Testing AI chat...\n";
        $chatData = ['message' => 'Quel engrais pour la tomate ?'];
        $ch = curl_init("$baseUrl/ai/chat");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($chatData),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ]
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "HTTP $httpCode\n";
        $chat = json_decode($response, true);
        echo "AI Response: " . ($chat['data']['response'] ?? 'N/A') . "\n\n";
        
        // Test 8: Logout
        echo "8. Logging out...\n";
        $ch = curl_init("$baseUrl/logout");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "HTTP $httpCode\n";
        echo "Message: " . json_decode($response, true)['message'] . "\n";
    }
} else {
    echo "Registration failed: $response\n";
}

echo "\n=== Test Complete ===\n";
