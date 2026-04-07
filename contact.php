<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Media Downloader</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png?v=1.1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="page-disclaimer">

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
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container" style="padding-top: 60px; max-width: 600px;">
        <div class="glass" style="padding: 40px; border-radius: var(--radius-lg);">
            <h1 class="section-title">Get in Touch</h1>
            <p style="color: var(--text-secondary); margin-bottom: 30px;">Have questions or feedback? We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.</p>
            
            <form id="contactForm" onsubmit="event.preventDefault(); showToast('Message sent successfully!', 'success'); this.reset();">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500;">Your Name</label>
                    <input type="text" required style="width: 100%; border-radius: 8px; border: 1px solid var(--glass-border); padding: 12px; background: rgba(0,0,0,0.2); color: #fff;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500;">Email Address</label>
                    <input type="email" required style="width: 100%; border-radius: 8px; border: 1px solid var(--glass-border); padding: 12px; background: rgba(0,0,0,0.2); color: #fff;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500;">Message</label>
                    <textarea rows="5" required style="width: 100%; border-radius: 8px; border: 1px solid var(--glass-border); padding: 12px; background: rgba(0,0,0,0.2); color: #fff;"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
        
        <div class="ad-slot ad-native glass" style="margin-top: 30px;"> Contact Page Native Ad </div>
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; 2026 MediaDownloader Pro. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
