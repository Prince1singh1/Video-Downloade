<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Transcript Generator | Media Downloader</title>
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
            <h1>AI Video <br> Transcription</h1>
            <p>Paste any video URL (YouTube, TikTok, etc.) to extract the full audio transcript instantly.</p>
            
            <div class="search-container">
                <form id="transcriptForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste YouTube link to get transcript..." required>
                    <button type="submit">Extract Text <i class="fa-solid fa-wand-magic-sparkles" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent" style="display:none; margin-top: 40px; animation: fadeIn 0.5s ease;">
                <div class="glass" style="padding: 40px; text-align: left; max-width: 900px; margin: 0 auto;">
                    <h3 id="videoTitle" style="margin-bottom: 20px;"></h3>
                    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="color:var(--accent-color); font-weight:700;">Full Transcript</span>
                        <button class="btn-primary" onclick="copyTranscript()" style="padding: 8px 15px; font-size: 0.85rem;"><i class="fa-solid fa-copy"></i> Copy All</button>
                    </div>
                    <div id="transcriptBox" class="glass" style="padding: 20px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; line-height: 1.8; color: var(--text-secondary); background: rgba(0,0,0,0.2);">
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
        document.getElementById('transcriptForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');
            const transcriptBox = document.getElementById('transcriptBox');
            const videoTitle = document.getElementById('videoTitle');

            if (!url) return;

            resultArea.style.display = 'none';
            loader.style.display = 'block';

            try {
                const response = await fetch('api/ai_tools.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `url=${encodeURIComponent(url)}&action=transcript`
                });

                const data = await response.json();

                if (data.success) {
                    videoTitle.innerText = data.title;
                    transcriptBox.innerText = data.transcript;
                    resultArea.style.display = 'block';
                    showToast('Transcript extracted!', 'success');
                } else {
                    let errorMsg = data.message || 'Failed to get transcript';
                    if (data.debug && data.debug.includes('Available subtitles')) {
                        errorMsg += ' (Check if subtitles are actually enabled on the video)';
                    }
                    showToast(errorMsg, 'error');
                }
            } catch (error) {
                showToast('An error occurred', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });

        function copyTranscript() {
            const text = document.getElementById('transcriptBox').innerText;
            navigator.clipboard.writeText(text).then(() => showToast('Copied to clipboard!', 'success'));
        }
    </script>
</body>
</html>
