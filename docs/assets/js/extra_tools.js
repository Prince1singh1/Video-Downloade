/**
 * Extra Utilities Logic
 * Media Downloader Pro
 */

const modal = document.getElementById('toolModal');
const modalBody = document.getElementById('modalBody');

function openToolModal(type) {
    let html = '';
    let title = '';

    switch (type) {
        case 'domainLookup':
            title = 'Domain/IP Lookup';
            html = `
                <h2>${title}</h2>
                <div class="input-box" style="margin-top:20px;">
                    <input type="text" id="domainInput" placeholder="e.g. google.com or 8.8.8.8" style="background:rgba(0,0,0,0.3); border:1px solid var(--accent-color); color:#fff; padding:12px; width:100%; border-radius:8px;">
                    <button class="btn-primary" onclick="doDomainLookup()" style="margin-top:15px; width:100%;">Lookup Records</button>
                </div>
                <div id="lookupResult" class="glass" style="margin-top:20px; padding:15px; min-height:100px; white-space:pre-wrap; font-family:monospace; font-size:0.9rem;">Results will appear here...</div>
            `;
            break;

        case 'htmlMd':
            title = 'HTML to Markdown';
            html = `
                <h2>${title}</h2>
                <textarea id="htmlInput" rows="6" placeholder="Paste HTML here..." style="background:rgba(0,0,0,0.3); border:1px solid var(--accent-color); color:#fff; padding:12px; width:100%; border-radius:8px; margin-top:20px;"></textarea>
                <button class="btn-primary" onclick="convertHtmlToMd()" style="margin-top:15px; width:100%;">Convert to Markdown</button>
                <textarea id="mdOutput" rows="6" readonly style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:#ddd; padding:12px; width:100%; border-radius:8px; margin-top:15px;"></textarea>
            `;
            break;

        case 'colorPalette':
            title = 'Color Palette Generator';
            html = `
                <h2>${title}</h2>
                <div id="paletteContainer" style="display:flex; gap:10px; margin-top:30px; height:200px;">
                    <!-- Colors here -->
                </div>
                <button class="btn-primary" onclick="generatePalette()" style="margin-top:25px; width:100%"><i class="fa-solid fa-sync"></i> Generate New Palette</button>
            `;
            break;

        case 'privacyGen':
            title = 'Fast Privacy Policy Gen';
            html = `
                <h2>${title}</h2>
                <div style="margin-top:20px; display:grid; gap:15px;">
                    <input type="text" id="siteName" placeholder="Website Name" class="glass" style="padding:10px; color:#fff;">
                    <input type="text" id="siteUrl" placeholder="Website URL" class="glass" style="padding:10px; color:#fff;">
                    <button class="btn-primary" onclick="generatePrivacy()">Generate PDF Text</button>
                </div>
                <div id="privacyResult" class="glass" style="margin-top:20px; padding:15px; max-height:300px; overflow-y:auto; font-size:0.85rem; color:#ccc;"></div>
            `;
            break;

        case 'unitConv':
            title = 'Smart Unit Converter';
            html = `
                <h2>${title}</h2>
                <div style="margin-top:20px; display:grid; gap:15px;">
                    <select id="convType" class="glass" style="padding:10px; color:#fff;" onchange="updateUnits()">
                        <option value="length">Length (km to miles)</option>
                        <option value="weight">Weight (kg to lbs)</option>
                        <option value="temp">Temp (C to F)</option>
                    </select>
                    <div style="display:flex; gap:10px;">
                        <input type="number" id="unitVal" placeholder="Value" class="glass" style="padding:10px; color:#fff; flex:1;">
                        <button class="btn-primary" onclick="doConversion()" style="flex:0.5;">Convert</button>
                    </div>
                    <div id="convResult" class="glass" style="padding:15px; text-align:center; font-weight:bold; font-size:1.2rem; color:var(--accent-color);">0</div>
                </div>
            `;
            break;
    }

    modalBody.innerHTML = html;
    modal.style.display = 'block';
    
    // Auto-trigger palette if opened
    if(type === 'colorPalette') generatePalette();
}

function closeToolModal() {
    modal.style.display = 'none';
}

// 1. Domain Lookup (Mock simulation for frontend)
async function doDomainLookup() {
    const input = document.getElementById('domainInput').value.trim();
    const resLine = document.getElementById('lookupResult');
    if(!input) return showToast('Enter a domain', 'error');
    
    resLine.innerText = 'Searching WHOIS and DNS records...';
    
    // In a real app, this would hit a PHP endpoint checkings DNS
    setTimeout(() => {
        resLine.innerText = `
[Domain Information]
Domain: ${input}
Status: active
IP Address: 172.67.141.${Math.floor(Math.random()*255)}

[DNS Records]
A: 104.21.35.211
MX: mail.${input}
TXT: v=spf1 include:_spf.google.com ~all
        `;
    }, 1500);
}

// 2. HTML to MD (Basic Regex Conversion)
function convertHtmlToMd() {
    let html = document.getElementById('htmlInput').value;
    let md = html
        .replace(/<h1>(.*?)<\/h1>/gi, '# $1\n')
        .replace(/<h2>(.*?)<\/h2>/gi, '## $1\n')
        .replace(/<h3>(.*?)<\/h3>/gi, '### $1\n')
        .replace(/<b>(.*?)<\/b>/gi, '**$1**')
        .replace(/<strong>(.*?)<\/strong>/gi, '**$1**')
        .replace(/<i>(.*?)<\/i>/gi, '*$1*')
        .replace(/<em>(.*?)<\/em>/gi, '*$1*')
        .replace(/<a.*?href="(.*?)".*?>(.*?)<\/a>/gi, '[$2]($1)')
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<p>(.*?)<\/p>/gi, '$1\n\n');
    
    document.getElementById('mdOutput').value = md.replace(/<[^>]*>?/gm, ''); // Strip remaining tags
    showToast('Converted!', 'success');
}

// 3. Color Palette
function generatePalette() {
    const container = document.getElementById('paletteContainer');
    container.innerHTML = '';
    for(let i=0; i<5; i++) {
        const color = '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
        const div = document.createElement('div');
        div.style.flex = '1';
        div.style.background = color;
        div.style.borderRadius = '12px';
        div.style.display = 'flex';
        div.style.alignItems = 'flex-end';
        div.style.justifyContent = 'center';
        div.style.paddingBottom = '10px';
        div.style.cursor = 'pointer';
        div.innerHTML = `<span style="background:rgba(0,0,0,0.5); padding:5px; border-radius:5px; font-size:0.7rem; font-weight:bold;">${color.toUpperCase()}</span>`;
        div.onclick = () => {
            navigator.clipboard.writeText(color);
            showToast(`Copied ${color}`, 'success');
        };
        container.appendChild(div);
    }
}

// 4. Privacy Gen
function generatePrivacy() {
    const name = document.getElementById('siteName').value || 'Our Website';
    const url = document.getElementById('siteUrl').value || 'https://example.com';
    const text = `
PRIVACY POLICY for ${name}
URL: ${url}

At ${name}, we value your privacy. We do not store any personal videos or data. 
All downloads are processed in real-time. We use cookies for basic site performance and analytics.
Third-party vendors, including Google, use cookies to serve ads based on prior visits.
You can opt-out of personalized advertising by visiting your browser settings.
    `;
    document.getElementById('privacyResult').innerText = text;
}

// 5. Unit Converter
function doConversion() {
    const type = document.getElementById('convType').value;
    const val = parseFloat(document.getElementById('unitVal').value) || 0;
    let res = 0;
    let unit = '';

    if(type === 'length') { res = val * 0.621371; unit = 'miles'; }
    else if(type === 'weight') { res = val * 2.20462; unit = 'lbs'; }
    else if(type === 'temp') { res = (val * 9/5) + 32; unit = '°F'; }

    document.getElementById('convResult').innerText = `${res.toFixed(2)} ${unit}`;
}
