<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Profile Info | Media Downloader</title>
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
            <h1>Social Media <br> Profile Info</h1>
            <p>Paste an Instagram or TikTok profile link to see stats and download profile picture.</p>
            
            <div class="search-container">
                <form id="profileForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste Profile URL (IG, TikTok)..." required>
                    <button type="submit">Get Info <i class="fa-solid fa-user-circle" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <div id="loader" class="loader"></div>
            <div id="resultContent" style="display:none; margin-top: 40px; animation: fadeIn 0.5s ease;">
                <div class="glass" style="padding: 40px; max-width: 800px; margin: 0 auto; text-align: left;">
                    <div id="profileHeader" style="display:flex; gap: 30px; align-items: center; flex-wrap: wrap; margin-bottom: 30px;">
                        <!-- Profile data will be injected here -->
                    </div>
                    <div id="profileStats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                        <!-- Profile stats here -->
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
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('urlInput').value.trim();
            const loader = document.getElementById('loader');
            const resultArea = document.getElementById('resultContent');
            const header = document.getElementById('profileHeader');
            const stats = document.getElementById('profileStats');

            if (!url) return;

            resultArea.style.display = 'none';
            loader.style.display = 'block';

            try {
                const response = await fetch('api/profile_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `url=${encodeURIComponent(url)}`
                });

                const data = await response.json();

                if (data.success) {
                    header.innerHTML = `
                        <div style="position: relative;">
                            <img src="${data.avatar}" style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid var(--accent-color); object-fit: cover;">
                            <a href="api/proxy.php?url=${encodeURIComponent(data.avatar)}&name=${data.username}_avatar.jpg" class="btn-download" style="position: absolute; bottom: 0; right: 0; padding: 8px; border-radius: 50%;"><i class="fa-solid fa-download"></i></a>
                        </div>
                        <div style="flex:1;">
                            <h2 style="margin:0; font-size: 2rem;">${data.name}</h2>
                            <p style="color:var(--accent-color); font-weight:700; font-size: 1.2rem; margin: 5px 0;">@${data.username}</p>
                            <p style="color:var(--text-secondary); line-height: 1.6;">${data.bio || 'No bio available'}</p>
                        </div>
                    `;

                    stats.innerHTML = `
                        <div class="glass" style="padding: 15px; text-align: center;">
                            <h4 style="margin:0; color:var(--text-secondary); font-size: 0.8rem; text-transform:uppercase;">Followers</h4>
                            <p style="margin:5px 0 0; font-size: 1.5rem; font-weight:800;">${data.followers}</p>
                        </div>
                        <div class="glass" style="padding: 15px; text-align: center;">
                            <h4 style="margin:0; color:var(--text-secondary); font-size: 0.8rem; text-transform:uppercase;">Following</h4>
                            <p style="margin:5px 0 0; font-size: 1.5rem; font-weight:800;">${data.following}</p>
                        </div>
                        <div class="glass" style="padding: 15px; text-align: center;">
                            <h4 style="margin:0; color:var(--text-secondary); font-size: 0.8rem; text-transform:uppercase;">Posts</h4>
                            <p style="margin:5px 0 0; font-size: 1.5rem; font-weight:800;">${data.posts}</p>
                        </div>
                        <div class="glass" style="padding: 15px; text-align: center;">
                            <h4 style="margin:0; color:var(--text-secondary); font-size: 0.8rem; text-transform:uppercase;">Verified</h4>
                            <p style="margin:5px 0 0; font-size: 1.5rem; font-weight:800; color:${data.verified ? '#3b82f6' : '#ef4444'};"><i class="fa-solid ${data.verified ? 'fa-check-circle' : 'fa-times-circle'}"></i></p>
                        </div>
                    `;

                    resultArea.style.display = 'block';
                    showToast('Profile extracted!', 'success');
                } else {
                    showToast(data.message || 'Failed to extract profile', 'error');
                }
            } catch (error) {
                showToast('An error occurred while fetching profile', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });
    </script>
</body>
</html>
