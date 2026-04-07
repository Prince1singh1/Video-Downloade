<?php
/**
 * System Check Utility
 * Verifies presence of required binaries
 */

header('Content-Type: application/json');

$ytdlp = __DIR__ . DIRECTORY_SEPARATOR . 'yt-dlp.exe';
$ffmpeg = __DIR__ . DIRECTORY_SEPARATOR . 'ffmpeg.exe';

$results = [
    'success' => true,
    'binaries' => [
        'yt-dlp' => [
            'name' => 'yt-dlp.exe',
            'status' => file_exists($ytdlp) ? 'found' : 'missing',
            'path' => $ytdlp,
            'required' => true,
            'description' => 'Required for all video extraction and downloading.'
        ],
        'ffmpeg' => [
            'name' => 'ffmpeg.exe',
            'status' => (file_exists($ffmpeg) || shell_exec('ffmpeg -version')) ? 'found' : 'missing',
            'path' => file_exists($ffmpeg) ? $ffmpeg : 'Global System Path',
            'required' => false,
            'description' => 'Required for Video-to-GIF conversion and some advanced audio features.'
        ]
    ],
    'environment' => [
        'os' => PHP_OS,
        'php_version' => PHP_VERSION,
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size')
    ]
];

echo json_encode($results);
exit;
