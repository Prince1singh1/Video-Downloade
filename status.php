<?php
/**
 * System Status Dashboard
 * Media Downloader Pro
 */

function checkBinary($name, $localPath) {
    if (file_exists($localPath)) {
        return ['status' => 'OK', 'path' => 'Local: ' . $localPath, 'color' => '#22c55e'];
    }
    
    // Check global
    $check = shell_exec("$name -version");
    if ($check) {
        return ['status' => 'OK', 'path' => 'Global PATH', 'color' => '#22c55e'];
    }
    
    return ['status' => 'MISSING', 'path' => 'Not found in api/ or system PATH', 'color' => '#ef4444'];
}

$ytdlp = checkBinary('yt-dlp', __DIR__ . '/api/yt-dlp.exe');
$ffmpeg = checkBinary('ffmpeg', __DIR__ . '/api/ffmpeg.exe');


$php_version = phpversion();
$exec_enabled = function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));
$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$temp_dir = sys_get_temp_dir();
$is_temp_writable = is_writable($temp_dir);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status | Media Downloader</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png?v=1.1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .status-card { padding: 25px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); position: relative; overflow: hidden; }
        .status-card h3 { margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .status-indicator { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: bold; background: rgba(255,255,255,0.1); }
        .alert-box { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 15px; border-radius: 10px; margin-top: 20px; display: none; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo"><img src="assets/img/logo.png?v=1.1" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;"> Media<span>Downloader</span></a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="tools.php">Tools</a></li>
                    <li><a href="status.php" class="active">Status</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container" style="padding-top: 120px; padding-bottom: 80px;">
        <div class="section-title">
            <span class="badge" style="background:var(--accent-color); color:#fff; margin-bottom:10px;">Diagnostics</span>
            <h1>System Health Dashboard</h1>
            <p>Real-time monitor for server binaries, site dependencies, and environment health.</p>
        </div>

        <div class="status-grid">
            <!-- yt-dlp Status -->
            <div class="status-card glass">
                <h3><i class="fa-solid fa-cloud-arrow-down" style="color:var(--accent-color);"></i> yt-dlp Engine</h3>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Status: <b style="color:<?php echo $ytdlp['color']; ?>;"><?php echo $ytdlp['status']; ?></b></span>
                    <span class="status-indicator" style="background:<?php echo $ytdlp['color']; ?>;"></span>
                </div>
                <p style="font-size:0.8rem; margin-top:10px; color:#aaa; font-family:monospace;"><?php echo $ytdlp['path']; ?></p>
            </div>

            <!-- FFmpeg Status -->
            <div class="status-card glass" style="<?php echo $ffmpeg['status'] === 'MISSING' ? 'border:1px solid #ef4444;' : ''; ?>">
                <h3><i class="fa-solid fa-file-video" style="color:#a855f7;"></i> FFmpeg Converter</h3>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Status: <b style="color:<?php echo $ffmpeg['color']; ?>;"><?php echo $ffmpeg['status']; ?></b></span>
                    <span class="status-indicator" style="background:<?php echo $ffmpeg['color']; ?>;"></span>
                </div>
                <p style="font-size:0.8rem; margin-top:10px; color:#aaa; font-family:monospace;"><?php echo $ffmpeg['path']; ?></p>
                <?php if ($ffmpeg['status'] === 'MISSING'): ?>
                    <p style="font-size:0.75rem; color:#ef4444; margin-top:8px;"><i class="fa-solid fa-triangle-exclamation"></i> Required for 1080p+ Audio Merge</p>
                <?php endif; ?>
            </div>

            <!-- PHP Environment -->
            <div class="status-card glass">
                <h3><i class="fa-brands fa-php" style="color:#777bb4;"></i> PHP Environment</h3>
                <ul style="list-style:none; padding:0; font-size:0.85rem; display:grid; gap:8px;">
                    <li>Version: <b><?php echo $php_version; ?></b></li>
                    <li>Shell Exec: <b style="color:<?php echo $exec_enabled ? '#22c55e':'#ef4444'; ?>;"><?php echo $exec_enabled ? 'Enabled':'Disabled'; ?></b></li>
                    <li>Max Upload: <b><?php echo $upload_max; ?></b></li>
                    <li>Temp Writable: <b style="color:<?php echo $is_temp_writable ? '#22c55e':'#ef4444'; ?>;"><?php echo $is_temp_writable ? 'Yes':'No'; ?></b></li>
                </ul>
            </div>
        </div>

        <?php if ($ffmpeg['status'] === 'MISSING'): ?>
        <div class="glass" style="margin-top:30px; padding:25px; border-left: 4px solid #ef4444;">
            <h3 style="color:#ef4444; margin-bottom:10px;"><i class="fa-solid fa-circle-info"></i> How to Fix "No Audio" in High Resolutions</h3>
            <p style="font-size:0.9rem; line-height:1.6;">
                Most platforms (like YouTube) serve 1080p and 4K videos without audio in a single file. Our system needs <b>FFmpeg</b> to merge them on-the-fly.
            </p>
            <div style="margin-top:15px; background:rgba(0,0,0,0.2); padding:15px; border-radius:8px;">
                <p style="font-weight:bold; margin-bottom:8px;">Installation Steps:</p>
                <ol style="margin-left:20px; font-size:0.85rem; display:grid; gap:5px;">
                    <li>Download `ffmpeg.exe` for Windows.</li>
                    <li>Place it inside the <code>api/</code> folder.</li>
                    <li>The system will automatically detect it and enable High-Quality audio merging.</li>
                </ol>
            </div>
        </div>
        <?php endif; ?>


    </main>

    <footer style="margin-top:0;">
        <div class="container" style="text-align:center;">
            <p>&copy; 2026 Media Downloader Pro — Stability First</p>
        </div>
    </footer>
</body>
</html>
