<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All-in-One Media Downloader | Fast & Free HD Downloads</title>
    <meta name="description" content="Download videos from Instagram, Facebook, and Terabox for free. HD YouTube thumbnails and advanced media tools.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png?v=1.1">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="page-home">

    <!-- Navigation -->
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <img src="assets/img/logo.png?v=1.1" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;">
                    Media<span>Downloader</span>
                </a>
                <ul class="nav-links">
                    <li><a href="index.php">Downloader</a></li>
                    <li><a href="playlist.php">Playlist</a></li>
                    <li><a href="converter.php">Converter</a></li>
                    <li><a href="summarizer.php">Summarizer</a></li>
                    <li><a href="status.php">System Status</a></li>
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
                <div class="mobile-menu-btn" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>All-in-One Media <br> Downloader</h1>
            <p>Download high-quality videos (144p to 4K), MP3 audio, and thumbnails from YouTube, Instagram, Facebook, and TeraBox instantly.</p>
            
            <!-- Download Search Form -->
            <div class="search-container">
                <form id="downloadForm" class="input-box">
                    <input type="url" id="urlInput" placeholder="Paste link (YouTube, TeraBox, Instagram, etc.)..." required>
                    <button type="submit">Download <i class="fa-solid fa-arrow-down" style="margin-left: 5px;"></i></button>
                </form>
            </div>

            <!-- Loader -->
            <div id="loader" class="loader"></div>

            <!-- Result Content Area -->
            <div id="resultContent"></div>

            <div class="ad-slot ad-banner-horizontal glass"> Adsterra Banner Ads Here (728x90) </div>
        </div>
    </section>

    <!-- Supported Platforms Icons -->
    <section class="container" style="animation: fadeIn 1s ease;">
        <div class="glass" style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; margin-bottom: 80px; padding: 40px; border-radius: var(--radius-lg);">
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-brands fa-instagram" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #E1306C;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">Instagram</span>
            </div>
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-brands fa-facebook-f" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #1877F2;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">Facebook</span>
            </div>
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-brands fa-tiktok" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #00f2ea;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">TikTok</span>
            </div>
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-brands fa-twitter" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #1DA1F2;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">Twitter / X</span>
            </div>
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-brands fa-youtube" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #ff0000;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">YouTube</span>
            </div>
            <div class="platform-icon" style="text-align: center; transition: var(--transition);">
                <i class="fa-solid fa-cloud" style="font-size: 2.5rem; display: block; margin-bottom: 12px; color: #00a1ff;"></i>
                <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary);">TeraBox</span>
            </div>
        </div>
    </section>

    <style>
        .platform-icon:hover {
            transform: translateY(-15px) scale(1.1);
            filter: drop-shadow(0 0 15px rgba(255,255,255,0.2));
        }
        .platform-icon:hover span {
            color: #fff !important;
        }
    </style>

    <!-- Extra Tools Section Banner -->
    <section class="container" style="margin-top: 50px;">
        <div class="glass" style="padding: 60px; text-align: center; border-radius: var(--radius-lg); background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(0,0,0,0));">
            <h2 style="font-size: 2rem; margin-bottom: 20px;">Need more than just downloads?</h2>
            <p style="color: var(--text-secondary); margin-bottom: 30px;">Explore our collection of utility tools like Image Compression, QR Generators, and more.</p>
            <a href="tools.php" class="btn-primary" style="text-decoration: none; padding: 15px 40px;">Explore Tools <i class="fa-solid fa-circle-arrow-right"></i></a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="index.php" class="logo footer-logo"><img src="assets/img/logo.png" alt="Logo" style="height: 30px; width: auto; border-radius: 5px;"> Media<span>Downloader</span></a>
                    <p style="color:var(--text-secondary); font-size: 0.9rem;">The ultimate suite for downloading media and managing your digital files with ease and speed.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="status.php">System Status</a></li>
                        <li><a href="tools.php">Extra Tools</a></li>
                        <li><a href="blog.php">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="disclaimer.php">Disclaimer</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Follow Us</h4>
                    <div style="display:flex; gap: 15px; margin-top: 10px;">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-telegram"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2026 MediaDownloader Pro. All rights reserved. <br> <span style="font-size: 0.75rem;">Developed with ❤️ for the community.</span></p>
            </div>
        </div>
    </footer>

    <!-- Adsterra Popunder Placeholder -->
    <!-- Place your Adsterra Popunder Script here -->

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
