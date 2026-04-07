<?php
/**
 * PHP Download Proxy — Lite Version
 * Streams remote files through this server to bypass CORS and fix filenames.
 * No local binaries or merging required.
 */

set_time_limit(0);
ini_set('memory_limit', '512M');

// Security: Check URL
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Error: No URL provided.");
}

$file_url = urldecode($_GET['url']);
$filename = isset($_GET['name']) ? $_GET['name'] : 'download_' . time() . '.mp4';

// Validate URL
if (!filter_var($file_url, FILTER_VALIDATE_URL) || strpos($file_url, 'http') !== 0) {
    http_response_code(400);
    die("Error: Invalid URL.");
}

// Sanitize filename
$ext = pathinfo($filename, PATHINFO_EXTENSION) ?: 'mp4';
$base = pathinfo($filename, PATHINFO_FILENAME);
$base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
$safe_filename = substr($base, 0, 80) . '.' . $ext;

// Clean output buffer
while (ob_get_level()) ob_end_clean();

// Use cURL to stream the remote file
serveRemoteUrl($file_url, $safe_filename);

/**
 * Serves a remote URL via cURL with Range (Seeking) support
 */
function serveRemoteUrl($url, $filename) {
    $ch_head = curl_init($url);
    curl_setopt($ch_head, CURLOPT_NOBODY, true);
    curl_setopt($ch_head, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_head, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch_head, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_head, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_exec($ch_head);
    
    $content_length = curl_getinfo($ch_head, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $content_type = curl_getinfo($ch_head, CURLINFO_CONTENT_TYPE);
    $final_url = curl_getinfo($ch_head, CURLINFO_EFFECTIVE_URL);
    curl_close($ch_head);

    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Accept-Ranges: bytes');

    $start = 0;
    $end = ($content_length > 0) ? ($content_length - 1) : 0;

    if (isset($_SERVER['HTTP_RANGE']) && $content_length > 0) {
        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
        $range = explode('-', $range);
        $start = $range[0];
        $end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $content_length - 1;
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$content_length");
        header("Content-Length: " . ($end - $start + 1));
    } elseif ($content_length > 0) {
        header("Content-Length: $content_length");
    }

    $ch = curl_init($final_url ?: $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    if ($content_length > 0) curl_setopt($ch, CURLOPT_RANGE, "$start-$end");
    
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
        echo $data;
        flush();
        return strlen($data);
    });
    curl_exec($ch);
    curl_close($ch);
}
