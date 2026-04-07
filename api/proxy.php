<?php
/**
 * PHP Download Proxy — Forces direct file download to user's device.
 * Streams the remote file through THIS server with proper download headers.
 * NO external redirects. The file saves directly to the user's device.
 */

set_time_limit(0); // Prevent PHP script from timing out
ignore_user_abort(true); // Continue fetching if the user disconnects (save server bandwidth)

// Robust PHP environment settings
@ini_set('memory_limit', '512M');
@ini_set('max_execution_time', '0');
@ini_set('pcre.recursion_limit', '10000');

// Turn off error display to prevent corrupting the stream
error_reporting(0);
ini_set('display_errors', 0);

// Ensure no compression or buffering is applied by the server
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 'Off');
@ini_set('output_buffering', 'Off');
@ini_set('implicit_flush', 'On');

// Security: Only allow URLs starting with http/https
if (!isset($_GET['url'])) {
    http_response_code(400);
    die("Error: No URL provided.");
}

$file_url = urldecode($_GET['url']);
$filename = isset($_GET['name']) ? $_GET['name'] : 'download_' . time() . '.mp4';
$passed_size = isset($_GET['size']) ? intval($_GET['size']) : 0;

// Validate URL
if (!filter_var($file_url, FILTER_VALIDATE_URL) || strpos($file_url, 'http') !== 0) {
    http_response_code(400);
    die("Error: Invalid URL.");
}

// Block redirects to this server (prevent loops)
$host = parse_url($file_url, PHP_URL_HOST);
if ($host === $_SERVER['HTTP_HOST']) {
    http_response_code(400);
    die("Error: Cannot proxy own server.");
}

// Sanitize filename — keep safe characters
$ext = pathinfo($filename, PATHINFO_EXTENSION) ?: 'mp4';
$base = pathinfo($filename, PATHINFO_FILENAME);
$base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);
$safe_filename = substr($base, 0, 80) . '.' . $ext;

// Clean output buffer to prevent corruption
while (ob_get_level()) ob_end_clean();

// 2. yt-dlp Stream Mode (For merging AV or complex formats)
if (isset($_GET['ytdlp']) && $_GET['ytdlp'] === '1') {
    $ytdlp_exe = __DIR__ . DIRECTORY_SEPARATOR . 'yt-dlp.exe';
    $ffmpeg_exe = __DIR__ . DIRECTORY_SEPARATOR . 'ffmpeg.exe';
    $format = isset($_GET['format']) ? str_replace(' ', '+', $_GET['format']) : 'best';
    
    if (!file_exists($ytdlp_exe)) {
        http_response_code(500);
        die("Error: Download engine (yt-dlp) not found.");
    }

    $needs_ffmpeg = (strpos($format, '+') !== false);
    if ($needs_ffmpeg && !file_exists($ffmpeg_exe) && !shell_exec('ffmpeg -version')) {
        http_response_code(500);
        die("Error: FFmpeg is required but missing. Check System Status.");
    }

    // Cache folder management
    $cache_dir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    if (!is_dir($cache_dir)) @mkdir($cache_dir, 0777, true);

    // Periodic cleanup (Delete files older than 2 hours)
    if (rand(1, 20) === 1) {
        $files = glob($cache_dir . DIRECTORY_SEPARATOR . "*.mp4");
        foreach ($files as $file) {
            if (time() - filemtime($file) > 7200) @unlink($file);
        }
    }

    // Generate a unique cache key for this video + format
    $cache_file = $cache_dir . DIRECTORY_SEPARATOR . md5($file_url . $format) . '.mp4';
    
    // If not cached, download and merge it
    if (!file_exists($cache_file)) {
        $cmd = escapeshellarg($ytdlp_exe) 
             . ' -f ' . escapeshellarg($format)
             . ' --no-playlist'
             . ' --no-warnings'
             . ' --quiet'
             . ' --no-check-certificate'
             . ' --no-mtime'
             . ' --merge-output-format mp4'
             . ' --ffmpeg-location ' . escapeshellarg($ffmpeg_exe)
             . ' -o ' . escapeshellarg($cache_file)
             . ' -- ' . escapeshellarg($file_url);

        exec($cmd);
    }

    if (file_exists($cache_file)) {
        // Stream the cached local file with full range support
        serveFile($cache_file, $safe_filename);
        exit;
    } else {
        http_response_code(500);
        die("Error: Failed to process video download.");
    }
}

// 3. Standard cURL Proxy (For direct files)
// For simple direct files, we stream directly with Range support
serveRemoteUrl($file_url, $safe_filename, $passed_size);
exit;

/**
 * Serves a local file with full HTTP Range (Seeking) support
 */
function serveFile($filePath, $filename) {
    if (!file_exists($filePath)) return;

    $size = filesize($filePath);
    $start = 0;
    $end = $size - 1;
    $type = (substr($filename, -4) === '.mp3') ? 'audio/mpeg' : 'video/mp4';

    header('Content-Type: ' . $type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Accept-Ranges: bytes');
    header('Content-Transfer-Encoding: binary');

    if (isset($_SERVER['HTTP_RANGE'])) {
        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
        if (strpos($range, ',') !== false) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            exit;
        }
        $range = explode('-', $range);
        $start = empty($range[0]) ? 0 : $range[0];
        $end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size - 1;
        
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: " . ($end - $start + 1));
    } else {
        header("Content-Length: $size");
    }

    // Clean output buffer
    while (ob_get_level()) ob_end_clean();

    $fp = fopen($filePath, 'rb');
    fseek($fp, $start);
    $chunkSize = 1024 * 64;
    while (!feof($fp) && ($pos = ftell($fp)) <= $end) {
        if ($pos + $chunkSize > $end) {
            $chunkSize = $end - $pos + 1;
        }
        echo fread($fp, $chunkSize);
        flush();
        if (connection_aborted()) break;
    }
    fclose($fp);
}

/**
 * Serves a remote URL via cURL with Range support
 */
function serveRemoteUrl($url, $filename, $passed_size = 0) {
    $content_length = $passed_size;
    $final_url = $url;
    $content_type = 'video/mp4';

    if ($content_length <= 0) {
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
    }

    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Accept-Ranges: bytes');

    $start = 0;
    $end = $content_length - 1;

    if (isset($_SERVER['HTTP_RANGE'])) {
        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
        $range = explode('-', $range);
        $start = $range[0];
        $end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $content_length - 1;
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$content_length");
        header("Content-Length: " . ($end - $start + 1));
    } else {
        header("Content-Length: $content_length");
    }

    while (ob_get_level()) ob_end_clean();

    $ch = curl_init($final_url ?: $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_RANGE, "$start-$end");
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
        echo $data;
        flush();
        return strlen($data);
    });
    curl_exec($ch);
    curl_close($ch);
}

