<?php

/**
 * Media Extractor Class — Lite Version (No Binaries)
 * Powered by Third-Party APIs (Cobalt)
 * Works on Free Hosting (InfinityFree)
 */
class MediaExtractor {

    public function __construct() {
        // No local binaries required for Lite version
    }

    /**
     * Extracts media using a Cloud API (Cobalt)
     */
    private function runCloudExtractor($url, $audio_only = false) {
        $api_url = 'https://api.cobalt.tools/api/json';
        
        $post_data = json_encode([
            'url'          => $url,
            'videoQuality' => '720', // Best compatibility for free hosting
            'vCodec'       => 'h264',
            'isAudioOnly'  => $audio_only,
            'isNoTTWatermark' => true
        ]);

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200 || !$response) {
            return null;
        }

        $data = json_decode($response, true);
        return ($data && (isset($data['status']) && ($data['status'] === 'stream' || $data['status'] === 'picker'))) ? $data : null;
    }

    /**
     * Build standard response from Cloud API data
     */
    private function buildResponse($data, $url) {
        $links = [];
        $title = $data['text'] ?? 'Video Download';
        $thumbnail = 'https://plus.unsplash.com/premium_photo-1661601633190-2e40398696ec?w=500&q=80'; 

        if ($data['status'] === 'stream') {
            $links[] = [
                'quality' => 'Download HD (720p)',
                'url'     => $this->proxyUrl($data['url'], 'video', 'mp4'),
                'type'    => 'video',
                'ext'     => 'mp4'
            ];
        } elseif ($data['status'] === 'picker') {
            foreach ($data['picker'] as $item) {
                $quality = $item['quality'] ?? 'HD';
                $links[] = [
                    'quality' => 'Download ' . $quality,
                    'url'     => $this->proxyUrl($item['url'], 'video', 'mp4'),
                    'type'    => 'video',
                    'ext'     => 'mp4'
                ];
                if (count($links) >= 5) break; 
            }
        }

        // Add Audio Option by making a separate call
        $audio_data = $this->runCloudExtractor($url, true);
        if ($audio_data && isset($audio_data['url'])) {
            $links[] = [
                'quality' => 'Download MP3 (Audio)',
                'url'     => $this->proxyUrl($audio_data['url'], 'audio', 'mp3'),
                'type'    => 'audio',
                'ext'     => 'mp3'
            ];
        }

        return [
            'success'   => true,
            'platform'  => $this->getPlatformLabel($this->detectPlatform($url)),
            'title'     => htmlspecialchars($title),
            'thumbnail' => $thumbnail,
            'links'     => $links
        ];
    }

    /**
     * Main extract method
     */
    public function extract($url) {
        $data = $this->runCloudExtractor($url);

        if ($data) {
            return $this->buildResponse($data, $url);
        }

        return [
            'success' => false,
            'message' => 'Lite Extractor could not find video. The link may be private or invalid.'
        ];
    }

    private function proxyUrl($url, $platform, $ext = 'mp4') {
        $filename = $platform . '_' . time() . '.' . $ext;
        return 'api/proxy.php?url=' . urlencode($url) . '&name=' . urlencode($filename);
    }

    private function detectPlatform($url) {
        if (strpos($url, 'instagram.com') !== false) return 'instagram';
        if (strpos($url, 'facebook.com') !== false)  return 'facebook';
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) return 'youtube';
        return 'video';
    }

    private function getPlatformLabel($platform) {
        return ucfirst($platform);
    }
}
