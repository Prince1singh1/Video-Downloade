/**
 * Main Downloader Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const downloadForm = document.getElementById('downloadForm');
    const urlInput = document.getElementById('urlInput');
    const resultArea = document.getElementById('resultContent');
    const loader = document.getElementById('loader');

    // Handle URL parameter from other pages (like Playlist)
    const urlParams = new URLSearchParams(window.location.search);
    const passedUrl = urlParams.get('url');
    if (passedUrl && urlInput) {
        urlInput.value = decodeURIComponent(passedUrl);
        // Automatically trigger download
        setTimeout(() => downloadForm.dispatchEvent(new Event('submit')), 100);
    }

    if (downloadForm) {
        downloadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = urlInput.value.trim();

            if (!url) {
                showToast('Please enter a valid URL', 'error');
                return;
            }

            // Reset UI
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
                    displayResult(data);
                } else {
                    showToast(data.message || 'Failed to fetch video details', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('An error occurred. Please try again.', 'error');
            } finally {
                loader.style.display = 'none';
            }
        });
    }

    function displayResult(data) {
        const resultHTML = `
            <div class="result-card glass">
                <img src="${data.thumbnail}" alt="Thumbnail" class="result-img">
                <div class="result-info">
                    <h3>${data.title}</h3>
                    <p>Platform: <strong>${data.platform}</strong></p>
                    <div class="ad-slot ad-banner-horizontal"> Adsterra Banner Ads Here </div>
                    <div id="downloadActionArea">
                        <button class="btn-primary" id="generateLinkBtn">Generate Download Link</button>
                    </div>
                </div>
            </div>
        `;

        resultArea.innerHTML = resultHTML;
        resultArea.style.display = 'block';

        const genBtn = document.getElementById('generateLinkBtn');
        if (genBtn) {
            genBtn.addEventListener('click', () => {
                const actionArea = document.getElementById('downloadActionArea');
                actionArea.innerHTML = `
                    <div style="margin-top: 20px; animation: fadeIn 0.5s ease;">
                        <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 10px; display: flex; justify-content: space-between;">
                            <span>Preparing secure stream...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; border: 1px solid var(--glass-border);">
                            <div id="progressBarFill" style="height: 100%; width: 0%; background: linear-gradient(90deg, var(--accent-color), #60a5fa); transition: width 0.2s ease; box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);"></div>
                        </div>
                    </div>
                `;

                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.floor(Math.random() * 25) + 15;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(interval);
                        setTimeout(renderLinks, 100);
                    }
                    const fill = document.getElementById('progressBarFill');
                    const text = document.getElementById('progressPercent');
                    if (fill) fill.style.width = `${progress}%`;
                    if (text) text.innerText = `${progress}%`;
                }, 100);

                function renderLinks() {
                    let linksHTML = '<div class="download-options" style="margin-top: 25px; animation: fadeIn 0.5s ease;">';
                    data.links.forEach(link => {
                        const icon = link.type === 'audio' ? 'fa-music' : (link.type === 'image' ? 'fa-image' : 'fa-video');
                        linksHTML += `
                            <a href="${link.url}" class="btn-download dl-link" data-filename="${data.title}.${link.ext || 'mp4'}">
                                <i class="fa-solid ${icon}"></i> ${link.quality}
                            </a>
                        `;
                    });
                    linksHTML += '</div>';
                    actionArea.innerHTML = linksHTML;
                    showToast('All links generated!', 'success');

                    // Add click listeners to handle downloads in current tab
                    document.querySelectorAll('.dl-link').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = btn.getAttribute('href');
                            window.location.href = url;
                        });
                    });
                }
            });
        }
    }
});

/**
 * Toast Notification System
 */
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = message;
    
    // Simple styling for toast
    Object.assign(toast.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        background: type === 'success' ? '#10b981' : '#ef4444',
        color: '#fff',
        padding: '12px 24px',
        borderRadius: '8px',
        boxShadow: '0 4px 6px -1px rgba(0,0,0,0.1)',
        zIndex: '3000',
        animation: 'fadeIn 0.3s ease'
    });

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
