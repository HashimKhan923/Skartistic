// Frontend motion layer. Decorative only: every feature here sits on top of markup that
// already works without it, and nothing runs for visitors who prefer reduced motion.
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { startAurora } from './motion/aurora';
import { splitWords, buildOdometers } from './motion/shared';

gsap.registerPlugin(ScrollTrigger);
gsap.defaults({ ease: 'expo.out' });

const root = document.documentElement;
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const isDesktop = () => window.innerWidth >= 1024;
const PIN_QUERY = '(min-width: 1024px) and (min-height: 700px)';

window.__skMotion = true;

let lenis = null;

/* ── Helpers ──────────────────────────────────────────────── */

const clearAfterReveal = 'transform,opacity,visibility,transition';

// Hides now (opacity only, so keyboard users can still tab to it), reveals in staggered batches
// as it scrolls in, then hands the element back to its own CSS.
function batchReveal(els, from, opts = {}) {
    if (!els.length) return;
    gsap.set(els, { opacity: 0, transition: 'none', ...from });
    ScrollTrigger.batch(els, {
        start: opts.start || 'top 90%',
        once: true,
        onEnter: (batch) => gsap.to(batch, {
            opacity: 1, x: 0, y: 0, scale: 1, rotateX: 0, rotateY: 0, rotate: 0,
            duration: opts.duration || 1.15,
            stagger: opts.stagger ?? 0.09,
            clearProps: clearAfterReveal,
        }),
    });
}

const once = (trigger, start, fn) => ScrollTrigger.create({ trigger, start, once: true, onEnter: fn });

/* ── Smooth scroll ────────────────────────────────────────── */
function initSmoothScroll() {
    lenis = new Lenis({ lerp: 0.1, wheelMultiplier: 1 });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
    window.skScrollTo = (target, opts = {}) => lenis.scrollTo(target, { offset: -100, duration: 1.4, ...opts });

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href^="#"]');
        if (!a || a.getAttribute('href').length < 2) return;
        const dest = document.querySelector(a.getAttribute('href'));
        if (!dest) return;
        e.preventDefault();
        window.skScrollTo(dest);
    });
}

/* ── Curtain + page transitions ───────────────────────────── */
function initCurtain() {
    const curtain = $('#curtain');
    const cols = $$('.cu-cols i', curtain);
    const word = $('.cu-word', curtain);
    const count = $('.cu-count', curtain);
    const label = $('.cu-label', curtain);
    const stage = { first: false, next: '' };

    try {
        stage.first = !sessionStorage.getItem('sk-visited');
        stage.next = sessionStorage.getItem('sk-next') || '';
        sessionStorage.setItem('sk-visited', '1');
        sessionStorage.removeItem('sk-next');
    } catch (e) { /* private mode: treat as a normal visit */ }

    curtain.style.animation = 'none';

    const pageReady = new Promise((res) => {
        const done = () => (document.fonts?.ready || Promise.resolve()).then(res);
        if (document.readyState === 'complete') done(); else window.addEventListener('load', done, { once: true });
        setTimeout(res, 2400);
    });

    const lift = (delay = 0) => gsap.timeline({ delay })
        .call(() => { initPage(); })
        .to([word, count, label], { autoAlpha: 0, y: -24, duration: 0.45, ease: 'power3.in' }, 0.02)
        .to(cols, {
            yPercent: -100, duration: 1, ease: 'expo.inOut',
            stagger: { each: 0.055, from: 'center' },
            onStart: () => { lenis?.start(); },
        }, 0.12)
        .set(curtain, { display: 'none' });

    lenis?.stop();

    if (stage.first) {
        const letters = (word.dataset.word || '').split('').map((c) => {
            const s = document.createElement('span');
            s.textContent = c;
            word.append(s);
            return s;
        });
        gsap.set([word, count], { visibility: 'visible' });
        gsap.from(letters, { yPercent: 110, duration: 1, stagger: 0.035 });
        const n = { v: 0 };
        const run = gsap.to(n, {
            v: 100, duration: 1.5, ease: 'power2.inOut',
            onUpdate: () => { count.textContent = String(Math.round(n.v)).padStart(3, '0'); },
        });
        Promise.all([pageReady, run.then()]).then(() => lift());
    } else {
        if (stage.next) {
            label.textContent = stage.next;
            gsap.set(label, { visibility: 'visible' });
        }
        lift(stage.next ? 0.1 : 0);
    }

    // Leaving: columns rise, then navigate.
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-no-curtain')) return;
        const url = new URL(a.href, location.href);
        if (url.origin !== location.origin || !/^https?:$/.test(url.protocol)) return;
        if (url.pathname === location.pathname && url.search === location.search) return;
        e.preventDefault();

        const text = (a.dataset.label || a.textContent).replace(/[→↗]/g, '').replace(/\s+/g, ' ').trim().slice(0, 28);
        try { sessionStorage.setItem('sk-next', text); } catch (err) { /* ignore */ }

        gsap.killTweensOf([cols, label, word, count]);
        gsap.set(curtain, { display: 'block' });
        gsap.set(word, { autoAlpha: 0 });
        gsap.set(count, { autoAlpha: 0 });
        gsap.set(cols, { yPercent: 100 });
        label.textContent = text;
        gsap.set(label, { autoAlpha: 0, y: 26 });
        gsap.timeline({ onComplete: () => { location.href = a.href; } })
            .to(cols, { yPercent: 0, duration: 0.65, ease: 'power4.inOut', stagger: { each: 0.04, from: 'edges' } })
            .to(label, { autoAlpha: 1, y: 0, duration: 0.4, ease: 'power3.out' }, 0.25);
    });

    // Back/forward cache restores a covered page otherwise.
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) gsap.set(curtain, { display: 'none' });
    });

    // Prefetch on intent so the new page is ready when the curtain finishes.
    const seen = new Set();
    document.addEventListener('pointerover', (e) => {
        const a = e.target.closest?.('a[href]');
        if (!a || seen.has(a.href)) return;
        const url = new URL(a.href, location.href);
        if (url.origin !== location.origin || url.pathname.startsWith('/lang') || a.target === '_blank') return;
        seen.add(a.href);
        const l = document.createElement('link');
        l.rel = 'prefetch';
        l.href = a.href;
        document.head.append(l);
    }, { passive: true });
}

/* ── Ambient particles (desktop only) ─────────────────────── */
function initParticles() {
    const cvs = $('#bg-canvas');
    if (!cvs) return;
    const lowEnd = (navigator.deviceMemory || 8) <= 4 || (navigator.hardwareConcurrency || 8) <= 4;
    if (!isDesktop() || lowEnd) { cvs.remove(); return; }
    const ctx = cvs.getContext('2d');
    const css = getComputedStyle(root);
    let brand = css.getPropertyValue('--brand-rgb').trim() || '108,43,217';
    let accent = css.getPropertyValue('--accent-rgb').trim() || '168,85,247';
    window.addEventListener('sk-theme', () => {
        brand = css.getPropertyValue('--brand-rgb').trim() || brand;
        accent = css.getPropertyValue('--accent-rgb').trim() || accent;
    });
    let W = 0, H = 0, mx = -999, my = -999;
    const size = () => { W = cvs.width = window.innerWidth; H = cvs.height = window.innerHeight; };
    size();
    window.addEventListener('resize', size, { passive: true });
    window.addEventListener('pointermove', (e) => { mx = e.clientX; my = e.clientY; }, { passive: true });
    const pts = Array.from({ length: 64 }, () => ({
        x: Math.random() * W, y: Math.random() * H,
        vx: (Math.random() - 0.5) * 0.16, vy: (Math.random() - 0.5) * 0.16,
        r: Math.random() * 1.4 + 0.4, a: Math.random() * 0.25 + 0.05,
        useBrand: Math.random() > 0.5,
    }));
    gsap.ticker.add(() => {
        ctx.clearRect(0, 0, W, H);
        for (let i = 0; i < pts.length; i++) {
            const p = pts[i];
            p.x += p.vx; p.y += p.vy;
            const dmx = p.x - mx, dmy = p.y - my, dm = dmx * dmx + dmy * dmy;
            if (dm < 14400 && dm > 0) { const f = (1 - dm / 14400) * 1.1 / Math.sqrt(dm); p.x += dmx * f; p.y += dmy * f; }
            if (p.x < 0) p.x = W; else if (p.x > W) p.x = 0;
            if (p.y < 0) p.y = H; else if (p.y > H) p.y = 0;
            for (let j = i + 1; j < pts.length; j++) {
                const q = pts[j], dx = p.x - q.x, dy = p.y - q.y, d = dx * dx + dy * dy;
                if (d < 12100) {
                    ctx.strokeStyle = `rgba(${brand},${(1 - d / 12100) * 0.07})`;
                    ctx.lineWidth = 0.6;
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke();
                }
            }
            ctx.fillStyle = `rgba(${p.useBrand ? brand : accent},${p.a})`;
            ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fill();
        }
    });
}

/* ── Cursor ───────────────────────────────────────────────── */
function initCursor() {
    if (!fine) return;
    root.classList.add('has-cursor');
    const dot = $('#cur-dot'), ring = $('#cur-ring'), trail = $('#cur-trail'), label = $('#cur-label');
    const dx = gsap.quickSetter(dot, 'x', 'px'), dy = gsap.quickSetter(dot, 'y', 'px');
    const rx = gsap.quickTo(ring, 'x', { duration: 0.45, ease: 'power3' }), ry = gsap.quickTo(ring, 'y', { duration: 0.45, ease: 'power3' });
    const tx = gsap.quickTo(trail, 'x', { duration: 1.1, ease: 'power3' }), ty = gsap.quickTo(trail, 'y', { duration: 1.1, ease: 'power3' });
    root.classList.add('cur-hide');
    window.addEventListener('pointermove', (e) => {
        root.classList.remove('cur-hide');
        dx(e.clientX); dy(e.clientY);
        rx(e.clientX); ry(e.clientY);
        tx(e.clientX); ty(e.clientY);
    }, { passive: true });
    document.addEventListener('mouseleave', () => root.classList.add('cur-hide'));

    const hot = 'a,button,[role="button"],label,summary,.gallery-btn';
    document.addEventListener('pointerover', (e) => {
        if (!e.target.closest) return;
        const tagged = e.target.closest('[data-cursor]');
        const onField = e.target.closest('input,textarea,select');
        const isHot = !onField && e.target.closest(hot);
        // Nested controls inside a tagged card (e.g. Apply Now) show the plain hover state instead
        const nested = tagged && isHot && isHot !== tagged && !isHot.classList.contains('svc-link');
        if (tagged && !nested && !onField) {
            label.textContent = tagged.dataset.cursor;
            root.classList.add('cur-label');
            gsap.to(ring, { scale: 1, duration: 0.45 });
        } else {
            root.classList.remove('cur-label');
            gsap.to(ring, { scale: isHot ? 1.5 : 1, duration: 0.45 });
        }
        gsap.to(ring, { opacity: onField ? 0 : 1, duration: 0.2 });
        gsap.to(dot, { scale: isHot ? 1.8 : 1, opacity: onField ? 0 : 1, duration: 0.25 });
    }, { passive: true });
}

/* ── Nav ──────────────────────────────────────────────────── */
function initNav() {
    const nav = $('#navbar'), list = $('#nl');
    const items = $$(':scope > li', list);
    items.forEach((li, i) => li.style.setProperty('--i', i));

    nav.classList.add('nav-away');
    gsap.delayedCall(0.45, () => nav.classList.remove('nav-away'));

    // Away on scroll down, back on scroll up
    ScrollTrigger.create({
        start: 0, end: 'max',
        onUpdate: (self) => {
            const away = self.direction === 1 && self.scroll() > 420 && !list.classList.contains('open') && !document.activeElement?.closest?.('#navbar');
            nav.classList.toggle('nav-away', away);
        },
    });

    // Sliding hover pill
    if (fine) {
        const pill = document.createElement('li');
        pill.className = 'nav-pill';
        pill.setAttribute('aria-hidden', 'true');
        list.prepend(pill);
        const home = () => $(':scope > li > a.active', list);
        const moveTo = (a, animate = true) => {
            if (!a) { gsap.to(pill, { opacity: 0, duration: 0.25 }); return; }
            const r = a.getBoundingClientRect(), p = list.getBoundingClientRect();
            gsap.to(pill, { x: r.left - p.left, width: r.width, opacity: 1, duration: animate ? 0.55 : 0, ease: 'expo.out', overwrite: 'auto' });
        };
        items.forEach((li) => li.addEventListener('pointerenter', () => { if (window.innerWidth > 1080) moveTo($(':scope > a', li)); }));
        list.addEventListener('pointerleave', () => { if (window.innerWidth > 1080) moveTo(home()); });
        document.fonts?.ready.then(() => { if (window.innerWidth > 1080) moveTo(home(), false); });
    }

    // Progress ring on the to-top button
    const top = $('#to-top');
    if (top) {
        top.insertAdjacentHTML('afterbegin', '<svg class="tt-ring" viewBox="0 0 50 50" aria-hidden="true"><circle cx="25" cy="25" r="23.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" pathLength="100" stroke-dasharray="100" stroke-dashoffset="100"/></svg>');
        const c = $('circle', top);
        ScrollTrigger.create({ start: 0, end: 'max', onUpdate: (s) => c.setAttribute('stroke-dashoffset', String(100 - s.progress * 100)) });
    }
}

/* ── Button micro-interactions ────────────────────────────── */
function initButtons() {
    $$('.btn-primary > span, .btn-schedule > span, .btn-contact-out > span').forEach((s) => {
        if (s.querySelector('.roll') || s.classList.contains('arr')) return;
        const t = s.textContent.trim();
        s.textContent = '';
        const roll = document.createElement('span');
        const inner = document.createElement('span');
        roll.className = 'roll';
        roll.dataset.t = t;
        inner.textContent = t;
        roll.append(inner);
        s.replaceWith(roll);
    });

    if (!fine) return;

    $$('.btn-outline, .btn-contact-out, .btn-price-out').forEach((btn) => {
        btn.classList.add('has-fill');
        const fill = document.createElement('i');
        fill.className = 'btn-fill';
        fill.setAttribute('aria-hidden', 'true');
        btn.prepend(fill);
        const at = (e) => {
            const r = btn.getBoundingClientRect();
            return { left: e.clientX - r.left, top: e.clientY - r.top, reach: Math.hypot(r.width, r.height) / 4 };
        };
        btn.addEventListener('pointerenter', (e) => {
            const p = at(e);
            gsap.set(fill, { left: p.left, top: p.top });
            gsap.to(fill, { scale: p.reach, duration: 0.7, overwrite: true });
        });
        btn.addEventListener('pointerleave', (e) => {
            const p = at(e);
            gsap.set(fill, { left: p.left, top: p.top });
            gsap.to(fill, { scale: 0, duration: 0.55, overwrite: true });
        });
    });

    $$('.btn-primary, .btn-schedule, .btn-contact-out, .nav-cta, .btn-outline, .sv-btn-primary, .sv-btn-secondary, .sv-btn-white, .ab-cta-btn, .btn-ghost .arr').forEach((btn) => {
        const strength = btn.classList.contains('arr') ? 0.5 : 0.28;
        const x = gsap.quickTo(btn, 'x', { duration: 0.6, ease: 'power3' });
        const y = gsap.quickTo(btn, 'y', { duration: 0.6, ease: 'power3' });
        btn.addEventListener('pointermove', (e) => {
            const r = btn.getBoundingClientRect();
            x((e.clientX - (r.left + r.width / 2)) * strength);
            y((e.clientY - (r.top + r.height / 2)) * strength);
        });
        btn.addEventListener('pointerleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 1, ease: 'elastic.out(1,.35)', overwrite: 'auto' });
        });
    });
}

/* ── Card spotlight + tilt ────────────────────────────────── */
function initCards() {
    if (!fine) return;
    // Purple sweep on service cards grows from where the pointer came in
    $('.svc-cell').forEach((el) => el.addEventListener('pointerenter', (e) => {
        const r = el.getBoundingClientRect();
        el.style.setProperty('--fx', `${e.clientX - r.left}px`);
        el.style.setProperty('--fy', `${e.clientY - r.top}px`);
    }));
    $$('.svc-cell,.price-card,.testi-block,.benefit-box,.info-card,.audit-item,.stat-box,.sv-offer-card,.ab-val-item,.team-card,.blog-card,.job-card,.cta-block,.form-card').forEach((el) => {
        el.classList.add('has-spot');
        const s = document.createElement('span');
        s.className = 'spot';
        s.setAttribute('aria-hidden', 'true');
        el.prepend(s);
        el.addEventListener('pointermove', (e) => {
            const r = el.getBoundingClientRect();
            el.style.setProperty('--mx', `${e.clientX - r.left}px`);
            el.style.setProperty('--my', `${e.clientY - r.top}px`);
        }, { passive: true });
    });

    $$('.svc-cell,.price-card,.team-card,.blog-card,.sv-offer-card,.project-card').forEach((el) => {
        let rx, ry, active = false;
        el.addEventListener('pointerenter', () => {
            if (gsap.isTweening(el)) return;
            gsap.set(el, { transformPerspective: 1000 });
            rx = gsap.quickTo(el, 'rotateX', { duration: 0.7, ease: 'power3' });
            ry = gsap.quickTo(el, 'rotateY', { duration: 0.7, ease: 'power3' });
            active = true;
        });
        el.addEventListener('pointermove', (e) => {
            if (!active) return;
            const r = el.getBoundingClientRect();
            ry(((e.clientX - r.left) / r.width - 0.5) * 7);
            rx(-((e.clientY - r.top) / r.height - 0.5) * 7);
        }, { passive: true });
        el.addEventListener('pointerleave', () => {
            if (!active) return;
            active = false;
            gsap.to(el, { rotateX: 0, rotateY: 0, duration: 1, ease: 'expo.out', overwrite: 'auto', clearProps: 'transform' });
        });
    });
}

/* ── Hero (home) ──────────────────────────────────────────── */
function initHomeHero() {
    const hero = $('.hero-c');
    if (!hero) return;
    startAurora(hero);
    addGridGlow(hero);

    const staticLine = $('.hero-static-line', hero);
    const words = staticLine ? splitWords(staticLine) : [];
    const parts = ['.hero-trust', '.hero-ticker-wrap', '.hero-sub-c', '.hero-c-btns', '.hero-scroll-pill'].map((s) => $(s, hero)).filter(Boolean);
    const chips = $$('.hero-chip', hero);

    gsap.set(parts, { opacity: 0, y: 34 });
    gsap.set(words, { yPercent: 115 });
    gsap.set(chips, { opacity: 0, scale: 0.6 });

    gsap.timeline({ delay: 0.5 })
        .to(parts[0], { opacity: 1, y: 0, duration: 1 }, 0)
        .to(words, { yPercent: 0, duration: 1.1, stagger: 0.07 }, 0.1)
        .to(parts.slice(1), { opacity: 1, y: 0, duration: 1.1, stagger: 0.12, clearProps: 'transform' }, 0.3)
        .to(chips, { opacity: 1, scale: 1, duration: 1.2, stagger: 0.09, ease: 'back.out(1.8)' }, 0.55);

    // Idle float plus depth on pointer move, on separate wrappers' worth of axes
    chips.forEach((chip, i) => {
        gsap.to(chip, { rotate: i % 2 ? 2.5 : -2.5, duration: 3 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut' });
        chip.dataset.depth = String(14 + i * 7);
        chip._px = gsap.quickTo(chip, 'x', { duration: 1.1, ease: 'power3' });
        chip._py = gsap.quickTo(chip, 'y', { duration: 1.1, ease: 'power3' });
    });
    hero.addEventListener('pointermove', (e) => {
        const r = hero.getBoundingClientRect();
        const nx = (e.clientX - r.left) / r.width - 0.5, ny = (e.clientY - r.top) / r.height - 0.5;
        chips.forEach((c) => { const d = +c.dataset.depth; c._px(nx * d * -2); c._py(ny * d * -2); });
    }, { passive: true });

    // Scroll away
    gsap.timeline({ scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 0.6 } })
        .to('.hero-c-content', { y: -130, scale: 0.92, opacity: 0, ease: 'none' }, 0)
        .to('.hero-chips', { y: -240, opacity: 0, ease: 'none' }, 0)
        .to('.hero-c-grid', { y: 120, ease: 'none' }, 0);

    // Device frame: tilted back, lands flat
    const frame = $('.device-frame');
    if (frame) {
        gsap.fromTo(frame, { rotateX: 34, scale: 0.8, y: 80, transformPerspective: 1500, transformOrigin: '50% 0%' }, {
            rotateX: 0, scale: 1, y: 0, ease: 'none',
            scrollTrigger: { trigger: '.device-section', start: 'top 96%', end: 'top 18%', scrub: 0.7 },
        });
        $$('.mock-card', frame).forEach((card, i) => {
            gsap.fromTo(card, { y: 40 + i * 44 }, { y: 0, ease: 'none', scrollTrigger: { trigger: frame, start: 'top 90%', end: 'top 25%', scrub: 0.9 } });
        });
        gsap.fromTo($$('.mock-bar', frame), { scaleX: 0, transformOrigin: 'left' }, {
            scaleX: 1, stagger: 0.03, ease: 'none',
            scrollTrigger: { trigger: frame, start: 'top 72%', end: 'top 22%', scrub: 0.9 },
        });
    }
}

/* ── Inner-page heroes ────────────────────────────────────── */
function addGridGlow(host) {
    if (!fine) return;
    host.classList.add('has-grid');
    const g = document.createElement('div');
    g.className = 'grid-glow';
    g.setAttribute('aria-hidden', 'true');
    host.prepend(g);
    host.addEventListener('pointermove', (e) => {
        const r = host.getBoundingClientRect();
        g.style.setProperty('--gx', `${e.clientX - r.left}px`);
        g.style.setProperty('--gy', `${e.clientY - r.top}px`);
    }, { passive: true });
}

function initPageHeroes() {
    $$('.page-hero, .ab-hero, .sv-hero').forEach((hero) => {
        hero.style.position = hero.style.position || 'relative';
        hero.style.overflow = 'hidden';
        startAurora(hero);
        if (hero.classList.contains('page-hero')) {
            addGridGlow(hero);
            const c = $('.container', hero);
            if (c) { c.style.position = 'relative'; c.style.zIndex = 2; }
        }

        const heading = $('h1', hero);
        const words = heading ? splitWords(heading) : [];
        const rest = $$('.breadcrumb, .ab-hero-tag, .sv-hero-tag, p, .sv-hero-btns', hero);
        gsap.set(words, { yPercent: 115, rotate: 5, transformOrigin: '0% 100%' });
        gsap.set(rest, { opacity: 0, y: 26 });
        gsap.timeline({ delay: 0.45 })
            .to(words, { yPercent: 0, rotate: 0, duration: 1.25, stagger: 0.075 }, 0)
            .to(rest, { opacity: 1, y: 0, duration: 1, stagger: 0.1, clearProps: 'transform' }, 0.15);

        const content = $('.container, .sv-container', hero);
        if (content) {
            gsap.to(content, { y: 70, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 0.5 } });
        }

        const orb = $('.page-hero-orb', hero);
        if (orb && fine) {
            const ox = gsap.quickTo(orb, 'x', { duration: 1.8, ease: 'power3' });
            const oy = gsap.quickTo(orb, 'y', { duration: 1.8, ease: 'power3' });
            hero.addEventListener('pointermove', (e) => {
                const r = hero.getBoundingClientRect();
                ox((e.clientX - r.left - r.width * 0.85) * 0.4);
                oy((e.clientY - r.top - r.height * 0.5) * 0.4);
            }, { passive: true });
        }
    });
}

/* ── Headline + tag reveals ───────────────────────────────── */
function initHeadings() {
    $$('.s-h, .cta-block h2, .ab-sec-h, .ab-mission-title, .ab-cta-box h2, .sv-section-title, .sv-cta h2, .ab-team-hd h2').forEach((el) => {
        const words = splitWords(el);
        gsap.set(words, { yPercent: 118, rotate: 5, transformOrigin: '0% 100%' });
        once(el, 'top 90%', () => gsap.to(words, { yPercent: 0, rotate: 0, duration: 1.2, stagger: 0.07 }));
    });

    const GLYPHS = '!<>-_\\/[]{}—=+*^?#01';
    $$('.s-tag, .ab-sec-tag, .sv-tag').forEach((el) => {
        const text = el.textContent.trim();
        if (!text) return;
        el.setAttribute('aria-label', text);
        gsap.set(el, { opacity: 0 });
        once(el, 'top 92%', () => {
            gsap.set(el, { opacity: 0.9 });
            const p = { v: 0 };
            gsap.to(p, {
                v: 1, duration: 1, ease: 'none',
                onUpdate: () => {
                    const shown = Math.floor(text.length * p.v);
                    let out = text.slice(0, shown);
                    for (let i = shown; i < text.length; i++) {
                        out += text[i] === ' ' ? ' ' : GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
                    }
                    el.textContent = out;
                },
                onComplete: () => { el.textContent = text; gsap.set(el, { clearProps: 'visibility,opacity' }); },
            });
        });
    });

    // Statement: words ink in as you scroll
    $$('[data-scrub-text]').forEach((el) => {
        el.setAttribute('aria-label', el.textContent.trim());
        const parts = el.textContent.trim().split(/\s+/);
        el.textContent = '';
        const spans = parts.map((w, i) => {
            const s = document.createElement('span');
            s.className = 'w';
            s.textContent = w;
            el.append(s);
            if (i < parts.length - 1) el.append(' ');
            return s;
        });
        ScrollTrigger.create({
            trigger: el, start: 'top 82%', end: 'bottom 45%', scrub: true,
            onUpdate: (self) => {
                const lit = Math.round(self.progress * spans.length);
                spans.forEach((s, i) => s.classList.toggle('lit', i < lit));
            },
        });
    });
}

/* ── Generic reveals ──────────────────────────────────────── */
function initReveals() {
    const special = '.cta-block,.ab-cta-box,.sv-cta,.proc-item,.work-head *';
    batchReveal($$('.reveal, .sv-reveal, .ab-anim:not(.ab-num-cell):not(.ab-photo-item):not(.ab-award-item):not(.ab-val-item):not(.ab-founder-row):not(.ab-team-card):not(.ab-faq-item)').filter((el) => !el.matches(special)), { y: 46 });
    batchReveal($$('.reveal-l, .sv-reveal-l').filter((el) => !el.matches(special)), { x: -70 });
    batchReveal($$('.reveal-r, .sv-reveal-r').filter((el) => !el.matches(special)), { x: 70 });

    // Cards rise out of the page in 3D
    batchReveal($$('.svc-cell,.blog-card,.team-card,.benefit-box,.audit-item,.info-card,.job-card,.project-card,.sv-offer-card,.sv-reveal-s,.ab-val-item,.ab-award-item,.ab-team-card,.ab-num-cell,.ab-photo-item'), { y: 70, rotateX: -14, scale: 0.96, transformPerspective: 1100 }, { stagger: 0.1 });
    batchReveal($$('.faq-item,.ab-faq-item'), { y: 30 }, { stagger: 0.06, duration: 0.9 });
    batchReveal($$('.ab-founder-row'), { y: 60 });
    batchReveal($$('.sv-tech-pill'), { y: 16, scale: 0.85 }, { stagger: 0.025, duration: 0.7, start: 'top 94%' });
    batchReveal($$('.field-wrap'), { y: 22 }, { stagger: 0.05, duration: 0.8 });
    batchReveal($$('.blog-content > *'), { y: 26 }, { stagger: 0.04, duration: 0.9, start: 'top 94%' });

    $$('.sv-vis-bars').forEach((wrap) => {
        gsap.from($$('.sv-vis-bar', wrap), { scaleY: 0, transformOrigin: 'bottom', stagger: 0.03, duration: 0.9, scrollTrigger: { trigger: wrap, start: 'top 90%', once: true } });
    });

    // Service process rail draws itself
    $$('.sv-process-connector').forEach((line) => {
        gsap.from(line, { scaleY: 0, transformOrigin: 'top', ease: 'none', scrollTrigger: { trigger: line, start: 'top 80%', end: 'bottom 55%', scrub: true } });
    });
}

/* ── Images: clip reveal only. Nothing may zoom an image here, or it gets cropped ── */
function initImages() {
    $$('.project-media,.blog-img,.team-img,.ab-photo-item,.sv-work-img-wrap,.ab-founder-photo,.ab-team-thumb').forEach((wrap) => {
        gsap.fromTo(wrap, { clipPath: 'inset(10% 8% 10% 8% round 18px)' }, {
            clipPath: 'inset(0% 0% 0% 0% round 0px)', duration: 1.4, clearProps: 'clipPath',
            scrollTrigger: { trigger: wrap, start: 'top 90%', once: true },
        });
    });
}

/* ── Odometer counters ────────────────────────────────────── */
function initOdometers() {
    buildOdometers('.stat-box-n,.h-stat-n,.ab-num-big', (el, roll) => once(el, 'top 92%', roll), gsap);
}

/* ── Marquees follow scroll speed and direction ───────────── */
function runMarquee(track, isLogos) {
    const loop = gsap.to(track, { xPercent: -50, ease: 'none', duration: isLogos ? 40 : 28, repeat: -1 });
    loop.totalTime(loop.duration() * 50); // room to run backwards
    const skew = gsap.quickTo(track, 'skewX', { duration: 0.5, ease: 'power3' });
    let settle;
    ScrollTrigger.create({
        start: 0, end: 'max',
        onUpdate: (self) => {
            const v = self.getVelocity();
            const dir = v < 0 ? -1 : 1;
            gsap.to(loop, { timeScale: dir * (1 + Math.min(Math.abs(v) / 260, 6)), duration: 0.25, overwrite: true });
            if (!isLogos) skew(gsap.utils.clamp(-9, 9, v / -220));
            clearTimeout(settle);
            settle = setTimeout(() => {
                gsap.to(loop, { timeScale: dir, duration: 1.4, overwrite: true });
                if (!isLogos) skew(0);
            }, 120);
        },
    });
    track.addEventListener('pointerenter', () => gsap.to(loop, { timeScale: 0, duration: 0.6, overwrite: true }));
    track.addEventListener('pointerleave', () => gsap.to(loop, { timeScale: 1, duration: 0.6, overwrite: true }));
}

function setupLogoLoop(track) {
    const originals = Array.from(track.children);
    if (originals.length < 2) return; // a single logo just sits centred
    track.classList.add('is-loop');
    const addCopy = (nodes) => nodes.forEach((n) => {
        const c = n.cloneNode(true);
        c.setAttribute('aria-hidden', 'true');
        c.setAttribute('tabindex', '-1');
        track.append(c);
    });
    while (track.scrollWidth < window.innerWidth * 1.1) addCopy(originals);
    addCopy(Array.from(track.children)); // two identical halves, so -50% lands seamlessly
    runMarquee(track, true);
}

function initMarquees() {
    const words = $('.marquee-track');
    if (words) runMarquee(words, false);

    const logos = $('.clients-track');
    if (!logos) return;
    // Measure real widths: an unloaded logo has none, which would over-clone or skip the loop
    const waits = $$('img', logos).filter((i) => !i.complete).map((i) => new Promise((res) => {
        i.addEventListener('load', res, { once: true });
        i.addEventListener('error', res, { once: true });
    }));
    Promise.race([Promise.all(waits), new Promise((res) => setTimeout(res, 2500))]).then(() => safely('logo loop', () => setupLogoLoop(logos)));
}

/* ── Process: pinned step-through ─────────────────────────── */
function initProcess(mm) {
    $$('.proc-row').forEach((row) => {
        const section = row.closest('section');
        const steps = $$('.proc-item', row);
        if (!steps.length) return;

        mm.add(PIN_QUERY, () => {
            section.classList.add('proc-pinned');
            const track = document.createElement('div');
            track.className = 'proc-track';
            track.innerHTML = '<i></i>';
            row.before(track);
            const fill = $('i', track);

            gsap.set(steps, { opacity: 0.22, y: 26 });
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section, start: () => (section.offsetHeight > window.innerHeight ? 'bottom bottom' : 'top top'), end: `+=${steps.length * 70}%`,
                    pin: true, scrub: 0.6, anticipatePin: 1,
                    onUpdate: (self) => {
                        const on = self.progress * steps.length;
                        steps.forEach((s, i) => s.classList.toggle('is-on', on > i + 0.15));
                    },
                },
            });
            tl.to(fill, { scaleX: 1, ease: 'none', duration: steps.length }, 0);
            steps.forEach((s, i) => tl.to(s, { opacity: 1, y: 0, ease: 'power2.out', duration: 0.5 }, i + 0.1));

            return () => { track.remove(); section.classList.remove('proc-pinned'); steps.forEach((s) => s.classList.remove('is-on')); };
        });

        if (!window.matchMedia(PIN_QUERY).matches) batchReveal(steps, { y: 50 });
    });
}

/* ── Testimonials: stacked deck fans out ──────────────────── */
function initTestimonials(mm) {
    const wrap = $('.testi-wrap');
    if (!wrap) return;
    const cards = $$('.testi-block', wrap);
    if (!cards.length) return;

    mm.add('(min-width: 1024px)', () => {
        wrap.classList.add('fan');
        const box = wrap.getBoundingClientRect();
        const cx = box.left + box.width / 2, cy = box.top + box.height / 2;
        cards.forEach((card, i) => {
            const r = card.getBoundingClientRect();
            gsap.fromTo(card, {
                x: cx - (r.left + r.width / 2), y: cy - (r.top + r.height / 2) + 70,
                rotate: (i - (cards.length - 1) / 2) * 7, scale: 0.92, zIndex: cards.length - i,
            }, {
                x: 0, y: 0, rotate: 0, scale: 1, ease: 'power2.out',
                scrollTrigger: { trigger: wrap, start: 'top 92%', end: 'top 34%', scrub: 0.7 },
            });
        });
        return () => wrap.classList.remove('fan');
    });
    if (!isDesktop()) batchReveal(cards, { y: 50 });
}

/* ── Pricing ──────────────────────────────────────────────── */
function initPricing() {
    $$('.price-wrap').forEach((wrap) => {
        const cards = $$('.price-card', wrap);
        const mid = (cards.length - 1) / 2;
        cards.forEach((card, i) => {
            const side = i - mid;
            gsap.set(card, { opacity: 0, y: 90, rotateY: isDesktop() ? side * -26 : 0, rotateX: 8, transformPerspective: 1300, transformOrigin: side < 0 ? '100% 50%' : side > 0 ? '0% 50%' : '50% 100%' });
        });
        once(wrap, 'top 82%', () => {
            gsap.to(cards, { opacity: 1, y: 0, rotateY: 0, rotateX: 0, duration: 1.5, stagger: { each: 0.13, from: 'center' }, clearProps: 'transform,opacity,visibility' });
            gsap.from($$('.price-features li:not([style])', wrap), { opacity: 0, x: -14, duration: 0.7, stagger: 0.025, delay: 0.5, clearProps: 'transform,opacity,visibility' });
        });
    });
}

/* ── CTA blocks open up, footer wordmark rises ────────────── */
function initCtaAndFooter() {
    $$('.cta-block, .ab-cta-box, .sv-cta').forEach((cta) => {
        gsap.fromTo(cta, { clipPath: 'inset(14% 16% 14% 16% round 48px)', y: 40 }, {
            clipPath: 'inset(0% 0% 0% 0% round 20px)', y: 0, ease: 'none',
            scrollTrigger: { trigger: cta, start: 'top 96%', end: 'top 42%', scrub: 0.7 },
        });
        const btns = $$('.btn-primary,.btn-ghost,.ab-cta-btn,.sv-btn-white,.sv-btn-white-out', cta);
        gsap.from(btns, { opacity: 0, y: 24, duration: 1, stagger: 0.1, clearProps: 'transform,opacity,visibility', scrollTrigger: { trigger: cta, start: 'top 62%', once: true } });
    });

    const cols = $('.footer-top > div, .f-badge, .footer-bottom');
    batchReveal(cols, { y: 40 }, { stagger: 0.09 });
}

/* ── Forms ────────────────────────────────────────────────── */
function burst(x, y) {
    const colors = ['var(--brand)', 'var(--accent)', 'var(--magenta)'];
    for (let i = 0; i < 28; i++) {
        const d = document.createElement('i');
        d.className = 'burst-dot';
        d.style.left = `${x}px`;
        d.style.top = `${y}px`;
        d.style.background = colors[i % 3];
        document.body.append(d);
        const ang = Math.random() * Math.PI * 2, dist = 60 + Math.random() * 170;
        gsap.to(d, {
            x: Math.cos(ang) * dist, y: Math.sin(ang) * dist + 60, rotate: Math.random() * 500,
            opacity: 0, scale: 0.3, duration: 1 + Math.random() * 0.7, ease: 'power3.out',
            onComplete: () => d.remove(),
        });
    }
}

function initForms() {
    $$('.m-form').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (e.defaultPrevented) return;
            const btn = $('button[type="submit"]', form);
            if (!btn) return;
            btn.classList.add('is-busy');
            const roll = $('.roll', btn);
            if (roll) {
                roll.textContent = form.dataset.busy || 'Sending…';
                roll.dataset.t = '';
            }
            btn.insertAdjacentHTML('beforeend', '<span class="btn-spin" aria-hidden="true"></span>');
        });
    });

    // After a redirect, bring the visitor back to the result instead of the top of the page
    const flash = $('.flash-success');
    const err = $('.field-error');
    const target = err || flash;
    if (target) {
        setTimeout(() => {
            const focusField = err && err.parentElement.querySelector('.field-input');
            window.skScrollTo(target.closest('.form-card') || target, { offset: -120, duration: 1.2 });
            if (focusField) focusField.focus({ preventScroll: true });
        }, 900);
    }
    if (flash) {
        gsap.from(flash, { opacity: 0, y: 18, scale: 0.96, duration: 1, delay: 1.5 });
        const icon = $('.icon', flash);
        if (icon) {
            gsap.from(icon, { scale: 0, rotate: -160, duration: 1, delay: 1.7, ease: 'back.out(2.4)' });
            gsap.delayedCall(1.8, () => {
                const r = icon.getBoundingClientRect();
                burst(r.left + r.width / 2, r.top + r.height / 2);
            });
        }
    }
    if (err) {
        $$('.field-error').forEach((e) => {
            const wrap = e.closest('.field-wrap');
            gsap.fromTo(wrap, { x: -10 }, { x: 0, duration: 1.1, delay: 1.5, ease: 'elastic.out(1.4,.25)' });
        });
    }
}

/* ── Boot ─────────────────────────────────────────────────── */
function safely(name, fn) {
    try { fn(); } catch (e) { console.error(`[motion] ${name}`, e); }
}

let booted = false;
function initPage() {
    if (booted) return;
    booted = true;
    const mm = gsap.matchMedia();

    safely('hero', initHomeHero);
    safely('page heroes', initPageHeroes);
    safely('headings', initHeadings);
    safely('reveals', initReveals);
    safely('images', initImages);
    safely('odometers', initOdometers);
    safely('marquees', initMarquees);
    safely('process', () => initProcess(mm));
    safely('testimonials', () => initTestimonials(mm));
    safely('pricing', initPricing);
    safely('cta and footer', initCtaAndFooter);
    safely('forms', initForms);
    safely('cards', initCards);
    safely('buttons', initButtons);
    safely('nav', initNav);
    safely('cursor', initCursor);
    safely('particles', initParticles);

    // Triggers were created feature by feature, not in page order. Pin spacers shift everything
    // below them, so sort by position or later triggers measure against the pre-pin layout.
    const remeasure = () => { ScrollTrigger.sort(); ScrollTrigger.refresh(); };
    remeasure();
    document.fonts?.ready.then(remeasure);
    if (document.readyState === 'complete') gsap.delayedCall(0.2, remeasure);
    else window.addEventListener('load', remeasure, { once: true });
}

if (root.classList.contains('m-on')) {
    safely('smooth scroll', initSmoothScroll);
    initCurtain();
}
