<?php

/**
 * Media Extractor Class — Powered by yt-dlp
 * Extracts real media stream URLs from 300+ platforms.
 * All downloads are proxied through THIS server — no external redirects.
 */
class MediaExtractor {
    private $ytdlp;
    private $has_ffmpeg;

    public function __construct() {
        $this->ytdlp = __DIR__ . DIRECTORY_SEPARATOR . 'yt-dlp.exe';
        $ffmpeg_path = __DIR__ . DIRECTORY_SEPARATOR . 'ffmpeg.exe';
        $this->has_ffmpeg = (file_exists($ffmpeg_path) || shell_exec('ffmpeg -version'));
    }

    /**
     * Run yt-dlp and return JSON metadata for a given URL
     */
    private function runYtDlp($url) {
        if (!file_exists($this->ytdlp)) {
            return null;
        }

        // 1. Simple file-based caching to improve SPEED
        $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
        if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
        
        $cacheKey = md5($url);
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.json';
        $cacheTTL = 3600; // 1 hour cache

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if ($cachedData) return $cachedData;
        }

        $oldDir = getcwd();
        $apiDir = dirname($this->ytdlp);
        chdir($apiDir);

        // Run with optimized flags for SPEED and ensure we get all formats
        $cmd = escapeshellarg($this->ytdlp)
            . ' --no-playlist'
            . ' --dump-json'
            . ' --no-warnings'
            . ' --no-check-certificate'
            . ' --no-mtime'
            . ' --socket-timeout 5'
            . ' --concurrent-fragments 5'
            . ' ' . escapeshellarg($url)
            . ' 2>&1';

        $output = shell_exec($cmd);
        chdir($oldDir); // Restore directory

        if (!$output) return null;

        // yt-dlp may output multiple lines; get first valid JSON
        foreach (explode("\n", trim($output)) as $line) {
            $data = json_decode(trim($line), true);
            if ($data && isset($data['title'])) {
                // Save to cache
                @file_put_contents($cacheFile, json_encode($data));
                return $data;
            }
        }
        return null;
    }

    /**
     * Build a proxy download URL that streams the file through THIS server
     */
    private function proxyUrl($url, $platform, $ext = 'mp4', $ytdlp_stream = false, $format_id = 'best', $size = 0) {
        $filename = $platform . '_' . time() . '.' . $ext;
        $proxy_base = 'api/proxy.php?url=' . urlencode($url) . '&name=' . urlencode($filename);

        if ($ytdlp_stream) {
            $proxy_base .= '&ytdlp=1&format=' . urlencode($format_id);
        }
        
        if ($size > 0) {
            $proxy_base .= '&size=' . $size;
        }
        
        return $proxy_base;
    }

    /**
     * Helper to fetch URL content using cURL (for simple OG-tag scraping)
     */
    private function fetchUrl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * Build standard response from yt-dlp JSON data
     */
    private function buildResponse($info, $platform_label) {
        $title     = $info['title'] ?? ($platform_label . ' Video');
        $thumbnail = $info['thumbnail'] ?? '';

        $links = [];
        $formats = $info['formats'] ?? [];

        // 1. Pre-filter and sort formats
        // We want to prioritize formats that have BOTH video and audio.
        usort($formats, function($a, $b) {
            $a_has_both = (($a['vcodec'] ?? 'none') !== 'none' && ($a['acodec'] ?? 'none') !== 'none');
            $b_has_both = (($b['vcodec'] ?? 'none') !== 'none' && ($b['acodec'] ?? 'none') !== 'none');
            
            // Priority 1: Has both audio and video
            if ($a_has_both && !$b_has_both) return -1;
            if (!$a_has_both && $b_has_both) return 1;
            
            // Priority 2: Higher resolution
            $ha = $a['height'] ?? 0;
            $hb = $b['height'] ?? 0;
            if ($ha !== $hb) return $hb - $ha;

            // Priority 3: Higher bitrate
            $ba = $a['tbr'] ?? 0;
            $bb = $b['tbr'] ?? 0;
            if ($ba !== $bb) return $bb - $ba;

            // Priority 4: Better extension (mp4 > webm)
            $ext_a = $a['ext'] ?? '';
            $ext_b = $b['ext'] ?? '';
            if ($ext_a === 'mp4' && $ext_b !== 'mp4') return -1;
            if ($ext_a !== 'mp4' && $ext_b === 'mp4') return 1;

            return 0;
        });

        // 2. Video quality extraction
        $added_video = [];
        $is_yt = (stripos($platform_label, 'YouTube') !== false);

        foreach ($formats as $fmt) {
            $furl   = $fmt['url'] ?? '';
            $ext    = $fmt['ext'] ?? 'mp4';
            $h      = $fmt['height'] ?? 0;
            $vcodec = $fmt['vcodec'] ?? 'none';
            $acodec = $fmt['acodec'] ?? 'none';

            if (!$furl || $vcodec === 'none') continue; 
            if ($ext === 'mhtml' || strpos($furl, 'manifest') !== false) continue;
            
            $label = $h ? "{$h}p" : "Best";
            if ($h == 0 && !empty($fmt['format_note'])) $label = $fmt['format_note'];
            
            $has_audio = ($acodec !== 'none' && $acodec !== 'null');

            // If we already added this resolution with audio, don't add the video-only version
            if (isset($added_video[$label]) && $added_video[$label]['has_audio'] && !$has_audio) continue;

            if (!isset($added_video[$label]) || (!$added_video[$label]['has_audio'] && $has_audio)) {
                $added_video[$label] = [
                    'has_audio' => $has_audio
                ];

                $quality_label = 'Download ' . $label;
                $fsize = $fmt['filesize'] ?? ($fmt['filesize_approx'] ?? 0);
                
                // For YouTube, always offer merged high quality if it's video-only or if it's high res
                // Most high-quality YouTube formats are video-only and require merging.
                if ($is_yt && ($h >= 720 || !$has_audio)) {
                    $quality_label .= ' (HD + Audio)';
                    $links[] = [
                        'quality' => $quality_label,
                        'url'     => $this->proxyUrl($info['webpage_url'] ?? $info['url'], strtolower($platform_label), 'mp4', true, ($fmt['format_id'] . '+bestaudio/best')),
                        'type'    => 'video',
                        'ext'     => 'mp4'
                    ];
                } elseif (!$has_audio) {
                    if ($this->has_ffmpeg) {
                        $quality_label .= ' (HD + Audio)';
                        $links[] = [
                            'quality' => $quality_label,
                            'url'     => $this->proxyUrl($info['webpage_url'] ?? $info['url'], strtolower($platform_label), 'mp4', true, ($fmt['format_id'] . '+bestaudio/best')),
                            'type'    => 'video',
                            'ext'     => 'mp4'
                        ];
                    } else {
                        $quality_label .= ' (No Audio)';
                        $links[] = [
                            'quality' => $quality_label,
                            'url'     => $this->proxyUrl($furl, strtolower($platform_label), $ext, false, 'best', $fsize),
                            'type'    => 'video',
                            'ext'     => $ext
                        ];
                    }
                } else {
                    $links[] = [
                        'quality' => $quality_label,
                        'url'     => $this->proxyUrl($furl, strtolower($platform_label), $ext, false, 'best', $fsize),
                        'type'    => 'video',
                        'ext'     => $ext
                    ];
                }
            }
            
            if (count($links) >= 10) break; 
        }

        // 3. Fallback to best overall if nothing found
        if (empty($links) && isset($info['url'])) {
            $fsize = $info['filesize'] ?? ($info['filesize'] ?? 0);
            $links[] = [
                'quality' => 'Download Best Quality',
                'url'     => $this->proxyUrl($info['url'], strtolower($platform_label), 'mp4', false, 'best', $fsize),
                'type'    => 'video'
            ];
        }

        // 4. Audio only (MP3)
        $best_audio = null;
        foreach ($formats as $fmt) {
            $vcodec = $fmt['vcodec'] ?? 'none';
            $acodec = $fmt['acodec'] ?? 'none';
            $abr    = $fmt['abr'] ?? 0;
            $furl   = $fmt['url'] ?? '';

            if (!$furl || strpos($furl, 'manifest') !== false) continue;
            if ($vcodec === 'none' && $acodec !== 'none') {
                if (!$best_audio || $abr > ($best_audio['abr'] ?? 0)) {
                    $best_audio = $fmt;
                }
            }
        }

        if ($best_audio) {
            $fsize = $best_audio['filesize'] ?? ($best_audio['filesize'] ?? 0);
            $is_yt = (stripos($platform_label, 'YouTube') !== false);
            if ($is_yt) {
                $links[] = [
                    'quality' => 'Download MP3 (Audio)',
                    'url'     => $this->proxyUrl($info['webpage_url'] ?? $info['url'], strtolower($platform_label), 'mp3', true, 'bestaudio/best'),
                    'type'    => 'audio',
                    'ext'     => 'mp3'
                ];
            } else {
                $links[] = [
                    'quality' => 'Download MP3 (Audio)',
                    'url'     => $this->proxyUrl($best_audio['url'], strtolower($platform_label), 'mp3', false, 'best', $fsize),
                    'type'    => 'audio',
                    'ext'     => 'mp3'
                ];
            }
        }

        // 5. Thumbnail
        if ($thumbnail) {
            $links[] = [
                'quality' => 'Download Thumbnail',
                'url'     => $this->proxyUrl($thumbnail, strtolower($platform_label) . '_thumb', 'jpg'),
                'type'    => 'image'
            ];
        }

        return [
            'success'   => true,
            'platform'  => $platform_label,
            'title'     => htmlspecialchars($title),
            'thumbnail' => $thumbnail,
            'links'     => $links
        ];
    }

    /**
     * Main extract method — uses yt-dlp for ALL platforms
     */
    public function extract($url) {
        $platform = $this->detectPlatform($url);

        // yt-dlp handles YouTube, Instagram, TikTok, Twitter, FB, Pinterest, and 300+ more
        $info = $this->runYtDlp($url);

        if ($info) {
            $label = $this->getPlatformLabel($platform);
            return $this->buildResponse($info, $label);
        }

        // Fallback for platforms yt-dlp can't handle (e.g. Terabox)
        switch ($platform) {
            case 'terabox':
                return $this->extractTerabox($url);
            default:
                return [
                    'success' => false,
                    'message' => 'Could not extract video. Make sure the URL is public and valid.'
                ];
        }
    }

    private function getPlatformLabel($platform) {
        $map = [
            'youtube'   => 'YouTube',
            'instagram' => 'Instagram',
            'facebook'  => 'Facebook',
            'tiktok'    => 'TikTok',
            'twitter'   => 'Twitter',
            'pinterest' => 'Pinterest',
            'linkedin'  => 'LinkedIn',
            'terabox'   => 'Terabox',
        ];
        return $map[$platform] ?? 'Video';
    }

    private function detectPlatform($url) {
        if (strpos($url, 'instagram.com') !== false)                                   return 'instagram';
        if (strpos($url, 'facebook.com') !== false || strpos($url, 'fb.watch') !== false) return 'facebook';
        if (strpos($url, 'terabox.com') !== false || strpos($url, 'teraboxapp.com') !== false || strpos($url, '1024tera.com') !== false) return 'terabox';
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false)  return 'youtube';
        if (strpos($url, 'tiktok.com') !== false)                                     return 'tiktok';
        if (strpos($url, 'twitter.com') !== false || strpos($url, 'x.com') !== false) return 'twitter';
        if (strpos($url, 'pinterest.com') !== false)                                  return 'pinterest';
        if (strpos($url, 'linkedin.com') !== false)                                   return 'linkedin';
        return 'unknown';
    }

    private function extractTerabox($url) {
        // Primary attempt using yt-dlp
        $info = $this->runYtDlp($url);
        if ($info && !empty($info['url'])) {
            return $this->buildResponse($info, 'Terabox');
        }

        // Secondary attempt using specific TeraBox patterns if yt-dlp fails
        $html = $this->fetchUrl($url);
        
        // Try to find the download link in the HTML (if any)
        preg_match('/"dlink":"([^"]+)"/', $html, $m);
        $dl = isset($m[1]) ? str_replace('\/', '/', $m[1]) : '';

        if (!$dl) {
            // Some Terabox links use different formats
            preg_match('/"download_url":"([^"]+)"/', $html, $m);
            $dl = isset($m[1]) ? str_replace('\/', '/', $m[1]) : '';
        }

        if (!$dl) {
            return [
                'success' => false,
                'message' => 'TeraBox file is protected or requires a login. Try another link or check if it\'s public.'
            ];
        }

        return [
            'success'   => true,
            'platform'  => 'Terabox',
            'title'     => 'TeraBox Shared File',
            'thumbnail' => 'https://images.unsplash.com/photo-1614850523296-d8c1af93d400?w=400&q=80',
            'links'     => [
                ['quality' => 'Download File (Fast)', 'url' => $this->proxyUrl($dl, 'terabox', 'mp4'), 'type' => 'file']
            ]
        ];
    }
}
