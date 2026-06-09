<?php
// api/save_logo.php

header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$brandName = trim($input['brand_name'] ?? '');
$industry  = trim($input['industry'] ?? 'Technology');
$style     = trim($input['style'] ?? 'Modern');
$color     = trim($input['color'] ?? 'Purple & Blue');
$imageUrl  = $input['image_url'] ?? '';
$icon      = $input['icon'] ?? '🎨';

if (empty($brandName)) {
    echo json_encode(['success' => false, 'error' => 'Brand name required']);
    exit;
}

// Funcții pentru JSON
function getLogos($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}

function saveLogos($file, $logos) {
    file_put_contents($file, json_encode($logos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$logosFile = '../data/logos.json';
$logos = getLogos($logosFile);
$maxId = count($logos) > 0 ? max(array_column($logos, 'id')) : 0;

$newLogo = [
    'id'         => $maxId + 1,
    'user_id'    => $userId,
    'brand_name' => $brandName,
    'industry'   => $industry,
    'style'      => $style,
    'color'      => $color,
    'icon'       => $icon,
    'image_url'  => $imageUrl,
    'created_at' => date('Y-m-d H:i:s'),
    'status'     => 'saved'
];

$logos[] = $newLogo;
saveLogos($logosFile, $logos);

echo json_encode([
    'success' => true,
    'logo_id' => $newLogo['id'],
    'message' => 'Logo saved successfully'
]);
?>