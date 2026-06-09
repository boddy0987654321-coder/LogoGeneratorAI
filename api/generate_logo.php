<?php
// api/generate_logo.php

set_time_limit(300);
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

require_once '../includes/config.php';

$input = json_decode(file_get_contents('php://input'), true);

$brandName = trim($input['brand_name'] ?? '');
$industry  = trim($input['industry'] ?? 'Technology');
$style     = trim($input['style'] ?? 'Modern');
$color     = trim($input['color'] ?? 'Orange & Blue');

if (empty($brandName)) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'Brand name is required']);
    exit;
}

$prompt = "Create a professional, modern logo for a brand called '{$brandName}'. 
           Industry: {$industry}. Style: {$style}. Primary colors: {$color}.
           The logo should be minimalist, clean, vector-style, with white background.
           No text or letters in the logo. Just the icon/symbol.";

$apiUrl = 'https://api.openai.com/v1/images/generations';

$postData = [
    'model' => 'gpt-image-2',
    'prompt' => $prompt,
    'n' => 1,
    'size' => '1024x1024',
    'quality' => 'medium'
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . OPENAI_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

ob_clean();

if ($curlError) {
    echo json_encode([
        'success' => false,
        'error' => 'CURL Error: ' . $curlError
    ]);
    exit;
}

$responseData = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        'success' => false,
        'error' => 'API Error: HTTP ' . $httpCode,
        'message' => $responseData['error']['message'] ?? 'Unknown error'
    ]);
    exit;
}

// Verifică dacă avem URL sau Base64
$imageUrl = $responseData['data'][0]['url'] ?? '';
$b64Json = $responseData['data'][0]['b64_json'] ?? '';

if (!empty($imageUrl)) {
    // Dacă avem URL, îl returnăm direct
    echo json_encode([
        'success' => true,
        'image_url' => $imageUrl,
        'prompt' => $prompt
    ]);
} elseif (!empty($b64Json)) {
    // Dacă avem Base64, creăm un URL de date
    $dataUrl = 'data:image/png;base64,' . $b64Json;
    echo json_encode([
        'success' => true,
        'image_url' => $dataUrl,
        'prompt' => $prompt
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No image URL or Base64 in response',
        'full_response' => $responseData
    ]);
}
?>