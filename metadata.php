<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Metadata & Info | Media Downloader</title>
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
            <h1>Video Metadata <br> Extractor</h1>
            <p>Paste any video link to see all available metadata including title, views, upload date, and technical details.</p>
            
            <div class="search-container">
                <form id="metadataForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste video link here..." required>
                    <button type="submit">Extract Info <i class="fa-solid fa-circle-info" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent" style="display:none; margin-top: 40px; animation: fadeIn 0.5s ease;">
                <div class="glass" style="padding: 40px; text-align: left; max-width: 900px; margin: 0 auto;">
                    <div id="metadataDisplay" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                        <!-- Metadata will be injected here -->
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
        document.getElementById('metadataForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');
            const metadataDisplay = document.getElementById('metadataDisplay');

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
                    // Display some common metadata
                    let html = `
                        <div class="glass" style="padding: 20px; border-radius: 12px; background: rgba(0,0,0,0.2);">
                            <h3 style="margin-bottom: 20px; color: var(--accent-color);">General Information</h3>
                            <ul style="list-style: none; line-height: 2;">
                                <li><strong>Title:</strong> ${data.title}</li>
                                <li><strong>Platform:</strong> ${data.platform}</li>
                                <li><strong>Thumbnail:</strong> <a href="${data.thumbnail}" target="_blank" style="color:var(--accent-color);">View Image</a></li>
                            </ul>
                        </div>
                        <div class="glass" style="padding: 20px; border-radius: 12px; background: rgba(0,0,0,0.2);">
                            <h3 style="margin-bottom: 20px; color: var(--accent-color);">Download Options</h3>
                            <ul style="list-style: none; line-height: 2;">
                                ${data.links.map(l => `<li><strong>${l.quality}:</strong> Available</li>`).join('')}
                            </ul>
                        </div>
                    `;
                    metadataDisplay.innerHTML = html;
                    resultArea.style.display = 'block';
                    showToast('Metadata extracted!', 'success');
                } else {
                    showToast(data.message || 'Failed to fetch metadata', 'error');
                }
            } catch (error) {
                showToast('An error occurred', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });
    </script>
</body>
</html>
