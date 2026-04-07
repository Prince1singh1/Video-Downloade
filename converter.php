<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video to MP3 Converter | Media Downloader</title>
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
            <h1>Video to <br> Audio Converter</h1>
            <p>Convert any video from any platform into high-quality MP3 audio instantly.</p>
            
            <div class="search-container">
                <form id="convertForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste video link for MP3 conversion..." required>
                    <button type="submit">Convert to MP3 <i class="fa-solid fa-file-audio" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent"></div>
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
        document.getElementById('convertForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');

            if (!url) return;

            resultArea.style.display = 'none';
            loader.style.display = 'block';

            try {
                const response = await fetch('download.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `url=${encodeURIComponent(url)}`
                });

                const data = await response.json();

                if (data.success) {
                    // Filter for audio links
                    const audioLinks = data.links.filter(l => l.type === 'audio' || l.quality.includes('MP3'));
                    if (audioLinks.length > 0) {
                        displayAudioResult(data, audioLinks);
                    } else {
                        showToast('No audio stream found for this video', 'error');
                    }
                } else {
                    showToast(data.message || 'Failed to fetch details', 'error');
                }
            } catch (error) {
                showToast('An error occurred', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });

        function displayAudioResult(data, audioLinks) {
            const resultArea = document.getElementById('resultContent');
            resultArea.innerHTML = `
                <div class="result-card glass" style="margin-top:40px; text-align:left;">
                    <img src="${data.thumbnail}" class="result-img" style="width:200px; border-radius:10px;">
                    <div class="result-info">
                        <h3>${data.title}</h3>
                        <p style="color:var(--text-secondary);">Platform: ${data.platform}</p>
                        <div class="download-options" style="margin-top:20px;">
                            ${audioLinks.map(l => `
                                <a href="${l.url}" class="btn-download" style="background:#10b981; border-color:#10b981;">
                                    <i class="fa-solid fa-music"></i> ${l.quality}
                                </a>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            resultArea.style.display = 'block';
        }
    </script>
</body>
</html>
