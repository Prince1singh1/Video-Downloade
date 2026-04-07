<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extra Utilities & Tools | Media Downloader</title>
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
                    <li><a href="summarizer.php">Summarizer</a></li>
                    <li><a href="status.php">System Status</a></li>
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container" style="padding-top: 80px;">
        <h1 class="section-title">All-in-One Utility Hub</h1>
        <p style="text-align: center; color: var(--text-secondary); margin-bottom: 60px; max-width: 800px; margin-left: auto; margin-right: auto;">
            Explore our collection of powerful, fast, and free tools designed to help you manage your digital content with ease. No registration required.
        </p>

        <!-- Tools Grid -->
        <div class="tools-grid">
            <!-- YT Thumbnail -->
            <div class="tool-card glass" onclick="window.location.href='thumbnails.php'" style="cursor: pointer;">
                <i class="fa-brands fa-youtube" style="color: #ff0000;"></i>
                <h3>HD Thumbnail Downloader</h3>
                <p>Download maximum resolution thumbnails from any YouTube video URL.</p>
                <a href="thumbnails.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Video Metadata -->
            <div class="tool-card glass" onclick="window.location.href='metadata.php'" style="cursor: pointer;">
                <i class="fa-solid fa-circle-info"></i>
                <h3>Video Metadata Extractor</h3>
                <p>Extract detailed technical info and metadata from any video link.</p>
                <a href="metadata.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Video to MP3 -->
            <div class="tool-card glass" onclick="window.location.href='converter.php'" style="cursor: pointer;">
                <i class="fa-solid fa-file-audio"></i>
                <h3>Video to MP3 Converter</h3>
                <p>Convert your favorite videos into high-quality audio files instantly.</p>
                <a href="converter.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Video Transcript -->
            <div class="tool-card glass" onclick="window.location.href='transcript.php'" style="cursor: pointer;">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <h3>AI Video Transcript</h3>
                <p>Extract the full audio transcript from videos using AI-powered tools.</p>
                <a href="transcript.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Video Summarizer -->
            <div class="tool-card glass" onclick="window.location.href='summarizer.php'" style="cursor: pointer;">
                <i class="fa-solid fa-brain"></i>
                <h3>AI Video Summarizer</h3>
                <p>Get a concise summary of video content without watching everything.</p>
                <a href="summarizer.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- TeraBox Downloader -->
            <div class="tool-card glass" onclick="window.location.href='index.php'" style="cursor: pointer;">
                <i class="fa-solid fa-cloud" style="color: #00a1ff;"></i>
                <h3>TeraBox Downloader</h3>
                <p>Fast and easy way to download files and videos from TeraBox links.</p>
                <a href="index.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Video to GIF -->

            <!-- Profile Info -->
            <div class="tool-card glass" onclick="window.location.href='profile.php'" style="cursor: pointer;">
                <i class="fa-solid fa-user-circle"></i>
                <h3>Profile Info Extractor</h3>
                <p>Get follower stats and profile pictures from IG and TikTok accounts.</p>
                <a href="profile.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Playlist Downloader -->
            <div class="tool-card glass" onclick="window.location.href='playlist.php'" style="cursor: pointer;">
                <i class="fa-solid fa-list-ul"></i>
                <h3>Playlist Downloader</h3>
                <p>Download all videos from a YouTube playlist in bulk easily.</p>
                <a href="playlist.php" class="btn-primary" style="margin-top: 15px; width: 100%; text-align: center;">Open Tool</a>
            </div>

            <!-- Domain IP Lookup -->
            <div class="tool-card glass" onclick="openToolModal('domainLookup')" style="cursor: pointer;">
                <i class="fa-solid fa-globe"></i>
                <h3>Domain/IP Lookup</h3>
                <p>Find the server IP address and DNS records for any domain name.</p>
                <button class="btn-primary" style="margin-top: 15px; width: 100%;">Open Tool</button>
            </div>

            <!-- HTML to Markdown -->
            <div class="tool-card glass" onclick="openToolModal('htmlMd')" style="cursor: pointer;">
                <i class="fa-solid fa-file-code"></i>
                <h3>HTML to Markdown</h3>
                <p>Convert your HTML snippets into clean GitHub-flavored Markdown.</p>
                <button class="btn-primary" style="margin-top: 15px; width: 100%;">Open Tool</button>
            </div>

            <!-- Color Palette Gen -->
            <div class="tool-card glass" onclick="openToolModal('colorPalette')" style="cursor: pointer;">
                <i class="fa-solid fa-palette"></i>
                <h3>Color Palette Gen</h3>
                <p>Generate stunning, harmonious color palettes for your designs.</p>
                <button class="btn-primary" style="margin-top: 15px; width: 100%;">Open Tool</button>
            </div>

            <!-- Privacy Gen -->
            <div class="tool-card glass" onclick="openToolModal('privacyGen')" style="cursor: pointer;">
                <i class="fa-solid fa-user-shield"></i>
                <h3>Privacy Policy Gen</h3>
                <p>Generate a basic privacy policy for your website or app instantly.</p>
                <button class="btn-primary" style="margin-top: 15px; width: 100%;">Open Tool</button>
            </div>

            <!-- Unit Converter -->
            <div class="tool-card glass" onclick="openToolModal('unitConv')" style="cursor: pointer;">
                <i class="fa-solid fa-arrows-left-right"></i>
                <h3>Smart Unit Converter</h3>
                <p>Convert between metric and imperial units (Weight, Length, Temp).</p>
                <button class="btn-primary" style="margin-top: 15px; width: 100%;">Open Tool</button>
            </div>

            <!-- Image Downloader -->
            <div class="tool-card glass">
                <i class="fa-solid fa-download"></i>
                <h3>Direct Image Downloader</h3>
                <p>Force download any image from a URL directly to your device.</p>
                <form id="imgDownloaderForm">
                    <input type="url" id="imgUrlInput" placeholder="Paste Image URL" required>
                    <button type="submit" class="btn-primary" style="width: 100%;">Download Image</button>
                </form>
            </div>

            <!-- QR Generator -->
            <div class="tool-card glass">
                <i class="fa-solid fa-qrcode"></i>
                <h3>QR Code Generator</h3>
                <p>Generate high-quality QR codes for URLs, text, or contact info.</p>
                <form id="qrForm">
                    <input type="text" id="qrInput" placeholder="Enter text or URL" required>
                    <button type="submit" class="btn-primary" style="width: 100%;">Generate QR</button>
                </form>
                <div id="qrResult"></div>
            </div>

            <!-- Image Compressor -->
            <div class="tool-card glass">
                <i class="fa-solid fa-file-image"></i>
                <h3>Smart Image Compressor</h3>
                <p>Reduce image size by up to 80% without visible quality loss.</p>
                <form id="compressorForm">
                    <input type="file" id="imageInput" accept="image/*" required>
                    <button type="submit" class="btn-primary" style="width: 100%;">Compress Now</button>
                </form>
                <div id="compressorResult"></div>
            </div>

            <!-- Text to Speech -->
            <div class="tool-card glass">
                <i class="fa-solid fa-volume-high"></i>
                <h3>Text to Speech (AI)</h3>
                <p>Convert your written content into natural-sounding spoken audio.</p>
                <form id="ttsForm">
                    <textarea id="ttsInput" rows="3" placeholder="Enter text to speak..." required></textarea>
                    <button type="submit" class="btn-primary" style="width: 100%;">Play Audio</button>
                </form>
            </div>

            <!-- Case Converter -->
            <div class="tool-card glass">
                <i class="fa-solid fa-font"></i>
                <h3>Smart Case Converter</h3>
                <p>Change text case between UPPER, lower, Sentence, and Title case.</p>
                <textarea id="caseInput" rows="3" placeholder="Type or paste text..."></textarea>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <button class="btn-primary" onclick="handleCase('upper')">UPPER</button>
                    <button class="btn-primary" onclick="handleCase('lower')">lower</button>
                    <button class="btn-primary" onclick="handleCase('sentence')">Sentence</button>
                    <button class="btn-primary" onclick="handleCase('title')">Title</button>
                </div>
            </div>

            <!-- Base64 Converter -->
            <div class="tool-card glass">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Base64 Encoder/Decoder</h3>
                <p>Securely encode or decode text strings to and from Base64 format.</p>
                <textarea id="b64Input" rows="3" placeholder="Enter text..."></textarea>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <button class="btn-primary" onclick="handleB64('encode')">Encode</button>
                    <button class="btn-primary" onclick="handleB64('decode')">Decode</button>
                </div>
            </div>

            <!-- Word Counter -->
            <div class="tool-card glass">
                <i class="fa-solid fa-pen-nib"></i>
                <h3>Professional Word Counter</h3>
                <p>Get detailed statistics about your text, including reading time.</p>
                <textarea id="wcInput" rows="3" placeholder="Paste your text here..."></textarea>
                <div id="wcStats" class="glass" style="padding: 10px; font-size: 0.9rem; color: var(--accent-color); font-weight: 700; border-radius: 8px;">
                    Words: 0 | Chars: 0 | Time: 0 min
                </div>
            </div>

            <!-- Password Gen -->
            <div class="tool-card glass">
                <i class="fa-solid fa-key"></i>
                <h3>Secure Password Gen</h3>
                <p>Create unhackable, high-entropy passwords with custom length.</p>
                <form id="pgForm">
                    <input type="number" id="pgLength" value="16" min="4" max="64">
                    <button type="submit" class="btn-primary" style="width: 100%;">Generate Secure Pass</button>
                </form>
                <div id="pgResult"></div>
            </div>

            <!-- JSON Formatter -->
            <div class="tool-card glass">
                <i class="fa-solid fa-code"></i>
                <h3>JSON Formatter & Validator</h3>
                <p>Clean up messy JSON and validate its structure instantly.</p>
                <form id="jsonForm">
                    <textarea id="jsonInput" rows="3" placeholder="Paste raw JSON here..." required></textarea>
                    <button type="submit" class="btn-primary" style="width: 100%;">Beautify JSON</button>
                </form>
            </div>

            <!-- Lorem Ipsum -->
            <div class="tool-card glass">
                <i class="fa-solid fa-paragraph"></i>
                <h3>Lorem Ipsum Generator</h3>
                <p>Generate placeholder text for your designs and layouts.</p>
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="number" id="loremCount" value="3" min="1" max="10" style="margin-bottom: 0;">
                    <select id="loremType" style="margin-bottom: 0;">
                        <option value="paras">Paragraphs</option>
                        <option value="words">Words</option>
                    </select>
                </div>
                <button class="btn-primary" style="width: 100%;" onclick="generateLorem()">Generate Placeholder</button>
                <div id="loremResult"></div>
            </div>

            <!-- Coming Soon -->
            <div class="tool-card glass" style="opacity: 0.6; cursor: not-allowed;">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <h3>AI Content Rewriter</h3>
                <p>Rewrite your articles and sentences using AI (Coming Soon).</p>
            </div>
        </div>

        <div class="ad-slot ad-native glass"> 🚀 Support our tools by allowing ads! 🚀 </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="index.php" class="logo footer-logo"><img src="assets/img/logo.png?v=1.1" alt="Logo" style="height: 30px; width: auto; border-radius: 5px;"> Media<span>Downloader</span></a>
                    <p style="color:var(--text-secondary); font-size: 0.95rem;">The ultimate suite for downloading media and managing your digital files with ease and speed.</p>
                </div>
                <div class="footer-links">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="status.php">System Status</a></li>
                        <li><a href="tools.php">Extra Tools</a></li>
                        <li><a href="blog.php">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="disclaimer.php">Disclaimer</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Community</h4>
                    <div style="display:flex; gap: 20px; margin-top: 15px; font-size: 1.2rem;">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-telegram"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2026 MediaDownloader Pro. All rights reserved. <br> <span style="font-size: 0.8rem; margin-top: 5px; display: block;">Designed for speed & privacy.</span></p>
            </div>
        </div>
    </footer>

    <!-- Tool Modal -->
    <div id="toolModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); backdrop-filter: blur(10px);">
        <div class="modal-content glass" style="margin: 5% auto; padding: 40px; width: 90%; max-width: 800px; position: relative; border: 1px solid rgba(255,255,255,0.1);">
            <span class="close-modal" onclick="closeToolModal()" style="position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer; color: #fff;">&times;</span>
            <div id="modalBody">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/tools.js"></script>
    <script src="assets/js/extra_tools.js"></script>
</body>
</html>
