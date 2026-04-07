<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';

    if (empty($url)) {
        echo json_encode(['success' => false, 'message' => 'Playlist URL is required.']);
        exit;
    }

    // 1. Caching for SPEED
    $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
    $cacheKey = md5($url . '_playlist');
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) { // 24 hours cache for playlists
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached) { echo json_encode($cached); exit; }
    }

    $ytdlp = __DIR__ . DIRECTORY_SEPARATOR . 'yt-dlp.exe';

    // 2. Extract playlist entries (titles, URLs, thumbnails, durations)
    $cmd = escapeshellarg($ytdlp) . 
           ' --flat-playlist --dump-single-json --no-warnings --no-check-certificates ' . 
           ' --playlist-end 50 ' . // Limit to first 50 videos for speed
           escapeshellarg($url);
    
    $output = shell_exec($cmd);
    $data = json_decode($output, true);

    if (!$data || !isset($data['entries'])) {
        echo json_encode(['success' => false, 'message' => 'Could not fetch playlist info. Make sure it is a public YouTube playlist.']);
        exit;
    }

    $playlistTitle = $data['title'] ?? 'YouTube Playlist';
    $videos = [];

    foreach ($data['entries'] as $entry) {
        $videos[] = [
            'title'     => $entry['title'] ?? 'Video',
            'url'       => 'https://www.youtube.com/watch?v=' . ($entry['id'] ?? ''),
            'thumbnail' => $entry['thumbnails'][0]['url'] ?? 'https://via.placeholder.com/320x180',
            'duration'  => formatDuration($entry['duration'] ?? 0),
            'uploader'  => $entry['uploader'] ?? 'Unknown'
        ];
    }

    $response = [
        'success' => true,
        'title'   => $playlistTitle,
        'videos'  => $videos
    ];

    // Save to cache
    @file_put_contents($cacheFile, json_encode($response));
    echo json_encode($response);
    exit;
}

function formatDuration($seconds) {
    if (!$seconds) return '00:00';
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = $seconds % 60;
    
    if ($h > 0) {
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
    return sprintf('%02d:%02d', $m, $s);
}
