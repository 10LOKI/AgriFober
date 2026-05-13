<?php
// Quick API test script
$baseUrl = 'http://localhost:8000/api';

// Test 1: Register
$registerData = [
    'username' => 'farmer1',
    'name' => 'Jean Dupont',
    'email' => 'farmer1@test.com',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!'
];

$ch = curl_init("$baseUrl/register");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Register Response ($httpCode):\n";
echo $response . "\n\n";

// Extract token
$data = json_decode($response, true);
if (isset($data['token'])) {
    $token = $data['token'];
    
    // Test 2: Get user profile
    $ch = curl_init("$baseUrl/user");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "User Profile ($httpCode):\n";
    echo $response . "\n\n";
    
    // Test 3: Get cultures catalogue
    $ch = curl_init("$baseUrl/cultures");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Cultures List ($httpCode):\n";
    echo $response . "\n\n";
    
    // Test 4: Create a parcel
    $parcelData = [
        'nom' => 'Parcelle Test',
        'surface' => 2.5,
        'status' => 'grow'
    ];
    $ch = curl_init("$baseUrl/parcels");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parcelData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Create Parcel ($httpCode):\n";
    echo $response . "\n\n";
}
