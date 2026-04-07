<?php

require_once 'api/extractors.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';

    if (empty($url)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid URL.']);
        exit;
    }

    $extractor = new MediaExtractor();
    $result = $extractor->extract($url);

    echo json_encode($result);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
