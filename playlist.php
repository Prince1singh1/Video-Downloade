<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Playlist Extractor | Media Downloader</title>
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
            <h1>YouTube Playlist <br> Extractor</h1>
            <p>Paste a YouTube playlist link to see and download all individual videos instantly.</p>
            
            <div class="search-container">
                <form id="playlistForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste YouTube Playlist URL..." required>
                    <button type="submit">Extract Playlist <i class="fa-solid fa-list" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent" style="display:none; margin-top: 40px; animation: fadeIn 0.5s ease;">
                <div class="glass" style="padding: 40px; text-align: left; max-width: 1000px; margin: 0 auto;">
                    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid var(--glass-border); padding-bottom: 15px;">
                        <h3 id="playlistTitle" style="margin: 0;"></h3>
                        <span id="videoCount" class="glass" style="padding: 5px 15px; font-size: 0.9rem; font-weight: 700; color: var(--accent-color);"></span>
                    </div>
                    <div id="playlistItems" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                        <!-- Playlist items will be injected here -->
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
        document.getElementById('playlistForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');
            const itemsContainer = document.getElementById('playlistItems');
            const playlistTitle = document.getElementById('playlistTitle');
            const videoCount = document.getElementById('videoCount');

            if (!url) return;

            resultArea.style.display = 'none';
            loader.style.display = 'block';

            try {
                const response = await fetch('api/playlist_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `url=${encodeURIComponent(url)}`
                });

                const data = await response.json();

                if (data.success) {
                    playlistTitle.innerText = data.title;
                    videoCount.innerText = `${data.videos.length} Videos`;
                    
                    let html = '';
                    data.videos.forEach(video => {
                        html += `
                            <div class="glass" style="padding: 15px; border-radius: 12px; transition: transform 0.3s ease; cursor: pointer;" onclick="window.location.href='index.php?url=${encodeURIComponent(video.url)}'">
                                <div style="position: relative;">
                                    <img src="${video.thumbnail}" style="width: 100%; border-radius: 8px; aspect-ratio: 16/9; object-fit: cover;">
                                    <span style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.8); padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">${video.duration}</span>
                                </div>
                                <h4 style="margin: 12px 0 8px; font-size: 0.95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${video.title}</h4>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);"><i class="fa-solid fa-user" style="margin-right: 5px;"></i>${video.uploader}</span>
                                    <button class="btn-primary" style="padding: 5px 10px; font-size: 0.75rem;">Download</button>
                                </div>
                            </div>
                        `;
                    });
                    itemsContainer.innerHTML = html;
                    resultArea.style.display = 'block';
                    showToast('Playlist extracted!', 'success');
                } else {
                    showToast(data.message || 'Failed to extract playlist', 'error');
                }
            } catch (error) {
                showToast('An error occurred while fetching playlist', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });
    </script>
</body>
</html>
