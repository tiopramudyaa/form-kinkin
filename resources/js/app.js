import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('sound', {
        muted: localStorage.getItem('kinkin_muted') === '1',
        toggle() {
            this.muted = !this.muted;
            localStorage.setItem('kinkin_muted', this.muted ? '1' : '0');
        },
    });
});

window.playPop = function playPop() {
    try {
        if (Alpine.store('sound')?.muted) {
            return;
        }

        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(440, ctx.currentTime + 0.12);

        gain.gain.setValueAtTime(0.15, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);

        osc.connect(gain).connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.16);
    } catch {
        // Audio isn't available (unsupported browser, blocked autoplay, etc.) — fail silently.
    }
};

function drawCertificate(name) {
    const canvas = document.getElementById('certificate-canvas');

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');
    const { width, height } = canvas;
    const fun = '"Fredoka", "Comic Sans MS", cursive';

    const bg = ctx.createLinearGradient(0, 0, width, height);
    bg.addColorStop(0, '#fef3c7');
    bg.addColorStop(0.5, '#fbcfe8');
    bg.addColorStop(1, '#fda4af');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, width, height);

    // Scalloped border made of little bumps, because straight lines are boring.
    const scallop = (inset, radius, color) => {
        ctx.fillStyle = color;
        const gap = radius * 1.6;
        for (let x = inset; x <= width - inset; x += gap) {
            ctx.beginPath();
            ctx.arc(x, inset, radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(x, height - inset, radius, 0, Math.PI * 2);
            ctx.fill();
        }
        for (let y = inset; y <= height - inset; y += gap) {
            ctx.beginPath();
            ctx.arc(inset, y, radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(width - inset, y, radius, 0, Math.PI * 2);
            ctx.fill();
        }
    };
    scallop(28, 14, '#f472b6');

    ctx.strokeStyle = '#fbbf24';
    ctx.lineWidth = 4;
    ctx.setLineDash([14, 10]);
    ctx.strokeRect(56, 56, width - 112, height - 112);
    ctx.setLineDash([]);

    // Confetti sprinkled around the corners.
    const confetti = ['🎉', '⭐', '🎊', '🌟', '💖'];
    const spots = [
        [130, 130], [width - 130, 120], [140, height - 110], [width - 120, height - 130],
        [width / 2 - 420, height / 2], [width / 2 + 420, height / 2],
    ];
    ctx.textAlign = 'center';
    spots.forEach(([x, y], i) => {
        ctx.save();
        ctx.translate(x, y);
        ctx.rotate(((i % 2 === 0 ? -1 : 1) * Math.PI) / 10);
        ctx.font = '40px system-ui, sans-serif';
        ctx.fillText(confetti[i % confetti.length], 0, 0);
        ctx.restore();
    });

    ctx.fillStyle = '#be185d';
    ctx.font = `700 58px ${fun}`;
    ctx.fillText('🎉 SERTIFIKAT 🎉', width / 2, 190);

    ctx.fillStyle = '#9d174d';
    ctx.font = `400 24px ${fun}`;
    ctx.fillText('diberikan kepada', width / 2, 245);

    ctx.save();
    ctx.translate(width / 2, 340);
    ctx.rotate(-0.02);
    ctx.fillStyle = '#1c1917';
    ctx.font = `700 66px ${fun}`;
    ctx.fillText(name, 0, 0, width - 220);
    ctx.restore();

    ctx.strokeStyle = '#fbbf24';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.moveTo(width / 2 - 240, 390);
    ctx.lineTo(width / 2 + 240, 390);
    ctx.stroke();

    ctx.fillStyle = '#44403c';
    ctx.font = `400 27px ${fun}`;
    wrapText(
        ctx,
        'atas partisipasinya menyelesaikan survey ini',
        width / 2,
        445,
        width - 260,
        36
    );

    ctx.fillStyle = '#be185d';
    ctx.font = `700 36px ${fun}`;
    ctx.fillText('Selamat ya! 🎉', width / 2, 560);

    // A wobbly little "stamp" because every good certificate needs one.
    ctx.save();
    ctx.translate(width - 190, height - 190);
    ctx.rotate(-0.25);
    ctx.fillStyle = 'rgba(225, 29, 72, 0.12)';
    ctx.beginPath();
    ctx.arc(0, 0, 95, 0, Math.PI * 2);
    ctx.fill();
    ctx.strokeStyle = '#e11d48';
    ctx.lineWidth = 4;
    ctx.setLineDash([6, 6]);
    ctx.stroke();
    ctx.setLineDash([]);
    ctx.fillStyle = '#e11d48';
    ctx.font = `700 26px ${fun}`;
    ctx.fillText('SELESAI', 0, -6);
    ctx.font = `700 22px ${fun}`;
    ctx.fillText('SURVEY', 0, 24);
    ctx.restore();

    ctx.fillStyle = '#78716c';
    ctx.font = `400 20px ${fun}`;
    ctx.textAlign = 'center';
    ctx.fillText(
        new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }),
        width / 2,
        720
    );
}

function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    let line = '';
    let cursorY = y;

    for (const word of words) {
        const testLine = line ? `${line} ${word}` : word;

        if (ctx.measureText(testLine).width > maxWidth && line) {
            ctx.fillText(line, x, cursorY);
            line = word;
            cursorY += lineHeight;
        } else {
            line = testLine;
        }
    }

    ctx.fillText(line, x, cursorY);
}

window.drawCertificate = function drawCertificateAfterFonts(name) {
    if (document.fonts?.ready) {
        document.fonts.ready.then(() => drawCertificate(name));
        return;
    }

    drawCertificate(name);
};

window.downloadCertificate = function downloadCertificate(name) {
    const canvas = document.getElementById('certificate-canvas');

    if (!canvas) {
        return;
    }

    const link = document.createElement('a');
    link.download = `sertifikat-${name.trim().toLowerCase().replace(/\s+/g, '-')}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
};

Alpine.start();
