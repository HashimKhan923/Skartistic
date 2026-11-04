// Helpers used by both the public site and the admin panel.

// Wraps each word in a mask, keeping <br> and gradient <span>s intact.
export function splitWords(el) {
    if (el.dataset.split) return Array.from(el.querySelectorAll('.si'));
    el.dataset.split = '1';
    el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
    const walk = (node) => {
        Array.from(node.childNodes).forEach((n) => {
            if (n.nodeType === Node.TEXT_NODE) {
                const frag = document.createDocumentFragment();
                n.textContent.split(/(\s+)/).forEach((part) => {
                    if (!part) return;
                    if (/^\s+$/.test(part)) { frag.append(' '); return; }
                    const w = document.createElement('span');
                    const i = document.createElement('span');
                    w.className = 'sw';
                    i.className = 'si';
                    i.textContent = part;
                    w.append(i);
                    frag.append(w);
                });
                n.replaceWith(frag);
            } else if (n.nodeType === Node.ELEMENT_NODE && n.tagName !== 'BR') {
                walk(n);
            }
        });
    };
    walk(el);
    return Array.from(el.querySelectorAll('.si'));
}

// Turns "65+" into rolling digit reels. `schedule(el, roll)` decides when roll() fires.
export function buildOdometers(selector, schedule, gsap = window.gsap) {
    document.querySelectorAll(selector).forEach((el) => {
        const raw = el.textContent.trim();
        const m = raw.match(/^(\D*)(\d[\d.,]*)(.*)$/);
        if (!m) return;
        el.setAttribute('aria-label', raw);
        el.textContent = '';
        const wrap = document.createElement('span');
        wrap.className = 'odo';
        const fixed = (t) => {
            if (!t) return;
            const s = document.createElement('span');
            s.className = 'odo-fix';
            s.textContent = t;
            wrap.append(s);
        };
        fixed(m[1]);
        const reels = [];
        m[2].split('').forEach((ch) => {
            if (!/\d/.test(ch)) { fixed(ch); return; }
            const col = document.createElement('span');
            col.className = 'odo-col';
            const reel = document.createElement('span');
            reel.className = 'odo-reel';
            // Two full spins before landing, so every digit visibly rolls
            for (let n = 0; n <= 20 + +ch; n++) {
                const b = document.createElement('b');
                b.textContent = String(n % 10);
                reel.append(b);
            }
            col.append(reel);
            wrap.append(col);
            reels.push({ reel, stops: 20 + +ch });
        });
        fixed(m[3]);
        el.append(wrap);
        schedule(el, () => {
            reels.forEach(({ reel, stops }, i) => {
                gsap.fromTo(reel, { yPercent: 0 }, {
                    yPercent: -(stops / (stops + 1)) * 100,
                    duration: 2.1 + i * 0.25, ease: 'expo.inOut',
                });
            });
        });
    });
}
