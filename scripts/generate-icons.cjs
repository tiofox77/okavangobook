/**
 * Gera os ícones PNG da PWA + og-image a partir do icon.svg.
 */
const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const imgDir = path.join(__dirname, '..', 'public', 'assets', 'img');
const iconSvg = fs.readFileSync(path.join(imgDir, 'icon.svg'));

async function run() {
    // Ícones quadrados padrão
    const sizes = [192, 512];
    for (const s of sizes) {
        await sharp(iconSvg).resize(s, s).png().toFile(path.join(imgDir, `icon-${s}.png`));
    }

    // Ícone maskable (com margem segura ~10%): fundo + ícone reduzido
    const safe = Math.round(512 * 0.8);
    const padded = await sharp(iconSvg).resize(safe, safe).png().toBuffer();
    await sharp({
        create: { width: 512, height: 512, channels: 4, background: '#0d3a6b' },
    })
        .composite([{ input: padded, gravity: 'center' }])
        .png()
        .toFile(path.join(imgDir, 'icon-maskable-512.png'));

    // Apple touch icon (180)
    await sharp(iconSvg).resize(180, 180).png().toFile(path.join(imgDir, 'apple-touch-icon.png'));

    // Favicon PNG (32) e (16)
    await sharp(iconSvg).resize(32, 32).png().toFile(path.join(imgDir, 'favicon-32.png'));
    await sharp(iconSvg).resize(16, 16).png().toFile(path.join(imgDir, 'favicon-16.png'));

    // OG image 1200x630: fundo gradiente + ícone + texto (via SVG)
    const ogSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630">
        <defs><linearGradient id="g" x1="0" y1="0" x2="1200" y2="630" gradientUnits="userSpaceOnUse">
            <stop stop-color="#1a5fae"/><stop offset="1" stop-color="#0d3a6b"/></linearGradient></defs>
        <rect width="1200" height="630" fill="url(#g)"/>
        <text x="600" y="300" text-anchor="middle" font-family="Arial, sans-serif" font-size="84" font-weight="700" fill="#ffffff">KiandaStay</text>
        <text x="600" y="375" text-anchor="middle" font-family="Arial, sans-serif" font-size="36" fill="#cfe0f5">As melhores acomodações em Angola</text>
        <text x="600" y="470" text-anchor="middle" font-family="Arial, sans-serif" font-size="28" fill="#f59e0b">hotéis · resorts · hospedarias · 18 províncias</text>
    </svg>`;
    const iconForOg = await sharp(iconSvg).resize(150, 150).png().toBuffer();
    await sharp(Buffer.from(ogSvg))
        .composite([{ input: iconForOg, top: 60, left: 525 }])
        .png()
        .toFile(path.join(imgDir, 'og-image.png'));

    console.log('Ícones gerados em', imgDir);
}

run().catch((e) => { console.error(e); process.exit(1); });
