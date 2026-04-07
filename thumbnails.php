<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Thumbnail Downloader | Media Downloader</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png?v=1.1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="page-tools">

    <!-- Navigation -->
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo"><img src="assets/img/logo.png?v=1.1" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;"> Media<span>Downloader</span></a>
                <ul class="nav-links">
                    <li><a href="index.php">Downloader</a></li>
                    <li><a href="playlist.php">Playlist</a></li>
                    <li><a href="converter.php">Converter</a></li>
                    <li><a href="transcript.php">Transcript</a></li>
                    <li><a href="summarizer.php">Summarizer</a></li>
                    <li><a href="status.php">System Status</a></li>
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero" style="padding: 80px 0 40px;">
        <div class="container">
            <h1>YouTube <br> Thumbnail Downloader</h1>
            <p>Download high-quality thumbnails from any YouTube video in multiple resolutions (1080p, 720p, 480p).</p>
            
            <div class="search-container">
                <form id="ytThumbForm" class="input-box">
                    <input type="url" id="ytThumbInput" placeholder="Paste YouTube link here..." required>
                    <button type="submit">Get Thumbnails <i class="fa-solid fa-image" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent" style="display:none; margin-top: 40px; animation: fadeIn 0.5s ease;">
                <div class="glass" style="padding: 40px; text-align: center; max-width: 900px; margin: 0 auto;">
                    <div id="thumbGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <!-- Thumbs will be injected here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2026 MediaDownloader Pro. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        document.getElementById('ytThumbForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const url = document.getElementById('ytThumbInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');
            const thumbGrid = document.getElementById('thumbGrid');

            const videoIdMatch = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            const videoId = videoIdMatch ? videoIdMatch[1] : null;

            if (videoId) {
                loader.style.display = 'block';
                resultArea.style.display = 'none';

                const sizes = [
                    { label: 'Maximum (1080p)', url: `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`, name: 'maxres' },
                    { label: 'High (720p)', url: `https://img.youtube.com/vi/${videoId}/sddefault.jpg`, name: 'sd' },
                    { label: 'Standard (480p)', url: `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`, name: 'hq' },
                    { label: 'Medium (360p)', url: `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`, name: 'mq' }
                ];

                let html = '';
                sizes.forEach(size => {
                    html += `
                        <div class="glass" style="padding: 15px; border-radius: 12px; background: rgba(0,0,0,0.2);">
                            <img src="${size.url}" style="width: 100%; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <p style="margin-bottom: 10px; font-weight: 600;">${size.label}</p>
                            <a href="api/proxy.php?url=${encodeURIComponent(size.url)}&name=YT_Thumb_${size.name}" target="_blank" class="btn-download" style="width: 100%; font-size: 0.85rem;">Download</a>
                        </div>
                    `;
                });

                thumbGrid.innerHTML = html;
                loader.style.display = 'none';
                resultArea.style.display = 'block';
                showToast('Thumbnails generated!', 'success');
            } else {
                showToast('Invalid YouTube URL', 'error');
            }
        });
    </script>
</body>
</html>
