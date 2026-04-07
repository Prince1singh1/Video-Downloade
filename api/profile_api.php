<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';

    if (empty($url)) {
        echo json_encode(['success' => false, 'message' => 'Profile URL is required.']);
        exit;
    }

    // 1. Caching for SPEED
    $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
    $cacheKey = md5($url . '_profile');
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) { // 24 hours cache for profiles
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached) { echo json_encode($cached); exit; }
    }

    $ytdlp = __DIR__ . DIRECTORY_SEPARATOR . 'yt-dlp.exe';

    // 2. Extract profile data
    $cmd = escapeshellarg($ytdlp) . 
           ' --dump-json --no-warnings --no-check-certificates ' . 
           escapeshellarg($url);
    
    $output = shell_exec($cmd);
    $data = json_decode($output, true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Could not fetch profile info. Make sure it is a public profile.']);
        exit;
    }

    // Parse data based on platform (Instagram/TikTok)
    $response = [
        'success'   => true,
        'username'  => $data['uploader_id'] ?? $data['uploader'] ?? 'unknown',
        'name'      => $data['uploader'] ?? 'Unknown User',
        'bio'       => $data['description'] ?? '',
        'avatar'    => $data['thumbnail'] ?? 'https://via.placeholder.com/150',
        'followers' => formatNumber($data['follower_count'] ?? 0),
        'following' => formatNumber($data['following_count'] ?? 0),
        'posts'     => formatNumber($data['playlist_count'] ?? 0),
        'verified'  => $data['uploader_verified'] ?? false
    ];

    // Save to cache
    @file_put_contents($cacheFile, json_encode($response));
    echo json_encode($response);
    exit;
}

function formatNumber($num) {
    if (!$num) return '0';
    if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
    if ($num >= 1000) return round($num / 1000, 1) . 'K';
    return number_format($num);
}
