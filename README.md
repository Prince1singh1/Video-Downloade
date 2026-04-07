# Media Downloader — Lite Version 🎥 🚀

An all-in-one media downloader built with PHP that is **100% compatible with free hosting** (like InfinityFree). This version uses cloud-based extraction, meaning it doesn't need large local binaries like FFmpeg.

## 🌟 Lite Features
- **Cloud Extraction**: Uses a high-speed external API to fetch download links.
- **Zero Binary Dependency**: No `ffmpeg.exe` or `yt-dlp.exe` required.
- **Ultra Lightweight**: The entire project is under 1MB, fixing the "10MB File Limit" on free hosts.
- **Platform Support**: Works with YouTube, Instagram, and Facebook.
- **No Permissions Needed**: Doesn't require `exec()` or `shell_exec()` permissions.

---

## 🌐 Live UI Demo
See the design and interface live at:
👉 **[https://prince1singh1.github.io/Video-Downloade/](https://prince1singh1.github.io/Video-Downloade/)**

---

## 🛠️ Free Hosting Installation (InfinityFree / Hostinger)

This version is designed specifically for free web hosts:

### 1. Requirements
- **PHP 7.4 or higher**
- **cURL extension enabled** (Standard on almost all hosts)

### 2. Set Up
1. Download the files from this repository.
2. Upload the contents (especially `api/`, `assets/`, and `index.php`) to your host's `public_html` or `htdocs` folder using FTP (like FileZilla).
3. **No configuration needed!** The cloud API handles the extraction automatically.

---

## ⚠️ Important Limitations of Lite Version
- **720p Max Quality**: Since we cannot merge video and audio on a free server, YouTube videos are limited to **720p HD**.
- **1080p/4K**: High-quality links will still appear, but they will be **Video Only** (no sound).

---

## 📂 Project Structure
- **/api**: Contains backend logic (PHP) and the Cloud API connector.
- **/assets**: CSS, JavaScript, and Image assets.
- **/docs**: The static version used for GitHub Pages.
- **index.php**: Main application entry point.

## 🤝 Contributing
Feel free to fork this project and submit pull requests for new features or bug fixes.

## ⚠️ Disclaimer
This tool is for educational purposes only. Please respect the copyright of the creators on all platforms. 

---
**Developed with ❤️ for the community.**
