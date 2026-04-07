<?php

require_once 'extractors.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    $action = $_POST['action'] ?? 'transcript';

    if (empty($url)) {
        echo json_encode(['success' => false, 'message' => 'URL is required.']);
        exit;
    }

    // 1. Caching for SPEED
    $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
    $cacheKey = md5($url . '_' . $action);
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached) { echo json_encode($cached); exit; }
    }

    $extractor = new MediaExtractor();
    $ytdlp = 'yt-dlp.exe'; // Use local binary

    // Use chdir for Windows compatibility with spaces in directory names
    $currentDir = getcwd();
    chdir(__DIR__);

    // 2. Optimized info extraction
    $infoCmd = 'yt-dlp.exe --dump-json --no-playlist --no-warnings --no-check-certificates ' . escapeshellarg($url);
    $infoJson = shell_exec($infoCmd);
    $info = json_decode($infoJson, true);

    if (!$info) {
        chdir($currentDir);
        echo json_encode(['success' => false, 'message' => 'Could not fetch video info. Make sure the URL is public and valid.']);
        exit;
    }

    $title = $info['title'] ?? 'Video Content';

    // 3. Robust subtitle extraction (Support VTT/SRT and auto-subs)
    $tempDir = 'temp_subs';
    if (!is_dir($tempDir)) @mkdir($tempDir, 0777, true);
    
    $tempFileBase = $tempDir . DIRECTORY_SEPARATOR . 'sub_' . time();
    
    // We try to get any available subtitles, prioritizing English and auto-generated
    $subCmd = 'yt-dlp.exe --skip-download --write-auto-subs --write-subs ' .
              ' --sub-langs "en.*,.*" --sub-format "vtt/srt/best" ' . 
              ' --output ' . escapeshellarg($tempFileBase) . 
              ' ' . escapeshellarg($url) . ' 2>&1';
    
    $subOutput = shell_exec($subCmd);
    
    chdir($currentDir); // Restore directory

    // Search for ANY generated subtitle file (vtt or srt)
    $files = glob(__DIR__ . DIRECTORY_SEPARATOR . $tempFileBase . '*');
    $subFile = null;
    
    // Prioritize English VTT/SRT
    foreach ($files as $f) {
        if (strpos($f, '.en.') !== false && (strpos($f, '.vtt') !== false || strpos($f, '.srt') !== false)) {
            $subFile = $f;
            break;
        }
    }
    
    // Fallback to ANY subtitle file
    if (!$subFile && !empty($files)) {
        foreach ($files as $f) {
            if (strpos($f, '.vtt') !== false || strpos($f, '.srt') !== false) {
                $subFile = $f;
                break;
            }
        }
    }

    if (!$subFile || !file_exists($subFile)) {
        $msg = 'No transcript available for this video. ';
        if (strpos($subOutput, 'Available subtitles') !== false) {
            $msg .= 'Captions exist but are in an incompatible format.';
        } else {
            $msg .= 'Video must have subtitles or auto-captions enabled.';
        }
        echo json_encode(['success' => false, 'message' => $msg]);
        exit;
    }

    $content = file_get_contents($subFile);
    $ext = pathinfo($subFile, PATHINFO_EXTENSION);
    
    // Cleanup
    foreach($files as $f) @unlink($f);

    $transcript = ($ext === 'vtt') ? cleanVtt($content) : cleanSrt($content);

    if (empty($transcript)) {
        echo json_encode(['success' => false, 'message' => 'Failed to parse subtitles.']);
        exit;
    }

    $response = [];
    if ($action === 'transcript') {
        $response = [
            'success' => true,
            'title' => $title,
            'transcript' => $transcript
        ];
    } elseif ($action === 'summary') {
        $summary = generateSummary($transcript);
        $response = [
            'success' => true,
            'title' => $title,
            'summary' => $summary
        ];
    }

    @file_put_contents($cacheFile, json_encode($response));
    echo json_encode($response);
    exit;
}

/**
 * Clean VTT content to plain text
 */
function cleanVtt($vtt) {
    // Remove header
    $vtt = preg_replace('/^WEBVTT.*?\n\n/s', '', $vtt);
    // Remove timestamps and metadata
    $vtt = preg_replace('/\d{2}:\d{2}:\d{2}\.\d{3} --> \d{2}:\d{2}:\d{2}\.\d{3}.*?\n/s', '', $vtt);
    // Remove positioning tags
    $vtt = preg_replace('/<.*?>/', '', $vtt);
    // Remove extra lines and combine
    $lines = explode("\n", $vtt);
    $cleanLines = [];
    foreach($lines as $line) {
        $line = trim($line);
        if ($line && !is_numeric($line)) $cleanLines[] = $line;
    }
    return trim(implode(' ', array_unique($cleanLines)));
}

/**
 * Clean SRT content to plain text
 */
function cleanSrt($srt) {
    // Remove indices and timestamps
    $srt = preg_replace('/\d+\r?\n\d{2}:\d{2}:\d{2},\d{3} --> \d{2}:\d{2}:\d{2},\d{3}/', '', $srt);
    $srt = strip_tags($srt);
    $lines = explode("\n", $srt);
    $cleanLines = [];
    foreach($lines as $line) {
        $line = trim($line);
        if ($line && !is_numeric($line)) $cleanLines[] = $line;
    }
    return trim(implode(' ', array_unique($cleanLines)));
}

/**
 * Simple text-based summarization logic
 */
function generateSummary($text) {
    if (strlen($text) < 200) return "This video is too short for a detailed summary. Content: " . $text;

    $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (count($sentences) <= 5) return $text;

    // Pick key points (Beginning, Middle, End)
    $summary = "• Key Insight: " . $sentences[0] . "\n\n";
    
    $mid = floor(count($sentences) / 2);
    $summary .= "• Main Discussion: " . $sentences[$mid] . " " . ($sentences[$mid+1] ?? "") . "\n\n";
    
    $summary .= "• Conclusion: " . end($sentences);

    return $summary;
}
