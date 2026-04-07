/**
 * Utility Tools Logic
 */

// 1. Image Compressor
async function compressImage(file, quality = 0.7) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                canvas.toBlob((blob) => {
                    resolve(blob);
                }, 'image/jpeg', quality);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

// 2. QR Code Generator (Using Google Charts API)
function generateQRCode(text) {
    if (!text) return null;
    const size = '250x250';
    return `https://chart.googleapis.com/chart?cht=qr&chl=${encodeURIComponent(text)}&chs=${size}`;
}

// 3. Text to Speech
function speakText(text) {
    if (!window.speechSynthesis) return false;
    window.speechSynthesis.cancel(); // Stop any current speech
    const utterance = new SpeechSynthesisUtterance(text);
    window.speechSynthesis.speak(utterance);
    return true;
}

// 4. Password Generator
function generatePassword(length = 16, options = { uppercase: true, numbers: true, symbols: true }) {
    const charset = {
        lower: 'abcdefghijklmnopqrstuvwxyz',
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        num: '0123456789',
        sym: '!@#$%^&*()_+~`|}{[]:;?><,./-='
    };
    let chars = charset.lower;
    if (options.uppercase) chars += charset.upper;
    if (options.numbers) chars += charset.num;
    if (options.symbols) chars += charset.sym;
    
    let password = '';
    for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return password;
}

// 5. Word Counter
function countTextStats(text) {
    const trimmed = text.trim();
    const words = trimmed ? trimmed.split(/\s+/).length : 0;
    const chars = text.length;
    const readTime = Math.ceil(words / 200); // Average 200 wpm
    return { words, chars, readTime };
}

// 6. Case Converter
function handleCase(type) {
    const input = document.getElementById('caseInput');
    let text = input.value;
    switch(type) {
        case 'upper': text = text.toUpperCase(); break;
        case 'lower': text = text.toLowerCase(); break;
        case 'sentence': text = text.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, c => c.toUpperCase()); break;
        case 'title': text = text.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' '); break;
    }
    input.value = text;
    showToast(`Converted to ${type} case`, 'success');
}

// 7. Base64
function handleB64(action) {
    const input = document.getElementById('b64Input');
    try {
        if (action === 'encode') {
            input.value = btoa(unescape(encodeURIComponent(input.value)));
            showToast('Encoded to Base64', 'success');
        } else {
            input.value = decodeURIComponent(escape(atob(input.value)));
            showToast('Decoded from Base64', 'success');
        }
    } catch (e) {
        showToast('Invalid Base64 / Text', 'error');
    }
}

// 8. Lorem Ipsum
function generateLorem() {
    const count = document.getElementById('loremCount').value || 3;
    const type = document.getElementById('loremType').value;
    const resultDiv = document.getElementById('loremResult');
    
    const words = ["lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "sed", "do", "eiusmod", "tempor", "incididunt", "ut", "labore", "et", "dolore", "magna", "aliqua"];
    
    let text = "";
    if (type === 'paras') {
        for (let i = 0; i < count; i++) {
            let p = "";
            for (let j = 0; j < 40; j++) p += words[Math.floor(Math.random() * words.length)] + " ";
            text += `<p style="margin-top:10px; font-size:0.9rem; color:var(--text-secondary);">${p.trim()}.</p>`;
        }
    } else {
        let w = "";
        for (let i = 0; i < count; i++) w += words[Math.floor(Math.random() * words.length)] + " ";
        text = `<p style="margin-top:10px; font-size:0.9rem; color:var(--text-secondary);">${w.trim()}.</p>`;
    }
    
    resultDiv.innerHTML = `<div class="glass" style="padding:15px; margin-top:15px; text-align:left;">${text}</div>
    <button class="btn-primary" style="margin-top:10px; width:100%;" onclick="copyToClipboard('${resultDiv.innerText}')">Copy Lorem Ipsum</button>`;
}

// JSON Formatter
function formatJSON(jsonStr) {
    try {
        const obj = JSON.parse(jsonStr);
        return JSON.stringify(obj, null, 4);
    } catch (e) {
        return 'Invalid JSON';
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => showToast('Copied to clipboard!', 'success'));
}

// Event Listeners for Tools
document.addEventListener('DOMContentLoaded', () => {
    // QR Code
    const qrForm = document.getElementById('qrForm');
    if (qrForm) {
        qrForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = document.getElementById('qrInput').value.trim();
            const qrResult = document.getElementById('qrResult');
            const qrURL = generateQRCode(text);
            if (qrURL) {
                qrResult.innerHTML = `
                    <div style="margin-top:20px; text-align:center;">
                        <img src="${qrURL}" alt="QR Code" class="glass" style="max-width: 200px; border-radius: 12px;">
                        <br>
                        <a href="${qrURL}" target="_blank" class="btn-download" style="display:inline-flex; margin-top:10px;">Download QR</a>
                    </div>
                `;
            }
        });
    }

    // Image Downloader
    const imgDownForm = document.getElementById('imgDownloaderForm');
    if (imgDownForm) {
        imgDownForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const url = document.getElementById('imgUrlInput').value.trim();
            if (url) {
                const proxyUrl = `api/proxy.php?url=${encodeURIComponent(url)}&name=Downloaded_Image`;
                window.open(proxyUrl, '_blank');
                showToast('Starting download...', 'success');
            }
        });
    }

    // TTS
    const ttsForm = document.getElementById('ttsForm');
    if (ttsForm) {
        ttsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = document.getElementById('ttsInput').value.trim();
            if (text) {
                speakText(text);
                showToast('Playing Audio...', 'success');
            }
        });
    }

    // Image Compressor
    const compressorForm = document.getElementById('compressorForm');
    if (compressorForm) {
        compressorForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fileInput = document.getElementById('imageInput');
            if (fileInput.files.length === 0) return;
            
            showToast('Compressing...', 'success');
            const compressedBlob = await compressImage(fileInput.files[0]);
            const url = URL.createObjectURL(compressedBlob);
            
            const resultDiv = document.getElementById('compressorResult');
            resultDiv.innerHTML = `
                <div class="glass" style="padding: 20px; margin-top: 20px; text-align:center;">
                    <p style="color:var(--accent-color); font-weight:700;">Compression Complete!</p>
                    <a href="${url}" download="compressed_image.jpg" class="btn-download" style="display:inline-flex; margin-top:15px;">Download Image</a>
                </div>
            `;
        });
    }

    // Password Gen
    const pgForm = document.getElementById('pgForm');
    if (pgForm) {
        pgForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const len = document.getElementById('pgLength').value || 16;
            const pass = generatePassword(len);
            document.getElementById('pgResult').innerHTML = `
                <div class="glass" style="padding:15px; margin-top:15px; word-break:break-all; font-family:monospace; font-size:1.1rem; border:1px solid var(--accent-color);">${pass}</div>
                <button class="btn-primary" style="margin-top:10px; width:100%;" onclick="copyToClipboard('${pass}')">Copy Password</button>
            `;
        });
    }

    // Word Counter
    const wcInput = document.getElementById('wcInput');
    if (wcInput) {
        wcInput.addEventListener('input', () => {
            const stats = countTextStats(wcInput.value);
            document.getElementById('wcStats').innerHTML = `Words: ${stats.words} | Chars: ${stats.chars} | Time: ${stats.readTime} min`;
        });
    }

    // JSON Formatter
    const jsonForm = document.getElementById('jsonForm');
    if (jsonForm) {
        jsonForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = document.getElementById('jsonInput').value;
            const formatted = formatJSON(input);
            document.getElementById('jsonInput').value = formatted;
            showToast(formatted === 'Invalid JSON' ? 'Error formatting' : 'JSON Beautified!', formatted === 'Invalid JSON' ? 'error' : 'success');
        });
    }

    // YouTube Thumbnail
    const ytThumbForm = document.getElementById('ytThumbForm');
    if (ytThumbForm) {
        ytThumbForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const url = document.getElementById('ytThumbInput').value.trim();
            const resultDiv = document.getElementById('ytThumbResult');
            const videoIdMatch = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            const videoId = videoIdMatch ? videoIdMatch[1] : null;

            if (videoId) {
                resultDiv.innerHTML = `
                    <div class="glass" style="margin-top: 20px; padding: 20px; animation: fadeIn 0.5s ease;">
                        <img src="https://img.youtube.com/vi/${videoId}/maxresdefault.jpg" style="width:100%; height:auto; border-radius:12px; margin-bottom:15px; box-shadow:0 10px 20px rgba(0,0,0,0.3);">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                            <a href="api/proxy.php?url=${encodeURIComponent('https://img.youtube.com/vi/' + videoId + '/maxresdefault.jpg')}&name=YT_HD_Thumb" target="_blank" class="btn-download">HD (1080p)</a>
                            <a href="api/proxy.php?url=${encodeURIComponent('https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg')}&name=YT_SD_Thumb" target="_blank" class="btn-download">SD (480p)</a>
                        </div>
                    </div>
                `;
                showToast('Thumbnails found!', 'success');
            } else {
                showToast('Invalid YouTube URL', 'error');
            }
        });
    }
});
