# Media Downloader Pro 🎥 🚀

An all-in-one media management suite built with PHP. Download videos, audios, and thumbnails from YouTube, Facebook, Instagram, and TeraBox with a single click.

## 🌟 Features
- **High-Quality Downloads**: Supports resolutions from 144p up to **4K (2160p)**.
- **Smart Merging**: Automatically merges high-quality video and audio streams for resolutions 1080p and above.
- **Video Seeking**: Full support for scrubbing (forward/backward) during playback in the browser.
- **Platform Support**: Works with YouTube, Instagram, Facebook, TikTok, and TeraBox.
- **System Dashboard**: Built-in status check for FFmpeg, ytdlp, and server health.
- **Extra Tools**: Includes utility tools like Image Compression, QR Code Generator, etc.

---

## 🌐 Live UI Demo
See the design and interface live at:
👉 **[https://prince1singh1.github.io/Video-Downloade/](https://prince1singh1.github.io/Video-Downloade/)**

> [!NOTE]
> The GitHub Pages link is a **Static UI Demo**. For security and technical reasons, actual downloading and merging are disabled on GitHub's free hosting. To use the full features, follow the local installation guide below.

---

## 🛠️ Local Installation Guide (XAMPP)

To run the full version with downloading enabled, follow these steps:

### 1. Requirements
- **PHP 7.4 or higher** (Included in XAMPP)
- **Apache** (Included in XAMPP)
- **FFmpeg & yt-dlp**: Both are pre-included in this repository under the `api/` folder via Git LFS.

### 2. Set Up
1. Clone this repository into your `htdocs` folder:
   ```bash
   git clone https://github.com/Prince1singh1/Video-Downloade.git
   ```
2. If you are using Git LFS, ensure you pull the large binaries:
   ```bash
   git lfs pull
   ```
3. Open XAMPP Control Panel and start **Apache**.
4. Visit `http://localhost/Video-Downloade` in your browser.

---

## 📂 Project Structure
- **/api**: Contains backend logic (PHP), `ffmpeg.exe`, `yt-dlp.exe`, and caching system.
- **/assets**: CSS, JavaScript, and Image assets.
- **/docs**: The static version used for GitHub Pages.
- **index.php**: Main application entry point.

## 🤝 Contributing
Feel free to fork this project and submit pull requests for new features or bug fixes.

## ⚠️ Disclaimer
This tool is for educational purposes only. Please respect the copyright of the creators on all platforms. 

---
**Developed with ❤️ for the community.**
