// Admin panel motion. Same language as the public site, tuned for a tool people use all day:
// transitions are shorter, and reveals use IntersectionObserver because forms open and close
// sections constantly, which would leave scroll-position triggers measuring a stale layout.
import gsap from 'gsap';
import Lenis from 'lenis';
import { startAurora } from './motion/aurora';
import { splitWords, buildOdometers } from './motion/shared';

gsap.defaults({ ease: 'expo.out' });

const root = document.documentElement;
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

window.__skMotion = true;

let lenis = null;

function safely(name, fn) {
    try { fn(); } catch (e) { console.error(`[admin motion] ${name}`, e); }
}

/* ── Reveals ──────────────────────────────────────────────── */
const clearAfterReveal = 'transform,opacity,transition';

// Opacity only, so keyboard users can still tab to anything not yet revealed.
function reveal(els, from, opts = {}) {
    if (!els.length) return;
    gsap.set(els, { opacity: 0, transition: 'none', ...from });
    const io = new IntersectionObserver((entries) => {
        const hit = entries.filter((e) => e.isIntersecting).map((e) => e.target);
        if (!hit.length) return;
        hit.forEach((el) => io.unobserve(el));
        gsap.to(hit, {
            opacity: 1, x: 0, y: 0, scale: 1, rotateX: 0,
            duration: opts.duration || 0.9,
            stagger: opts.stagger ?? 0.06,
            clearProps: clearAfterReveal,
        });
    }, { rootMargin: '0px 0px -4% 0px' });
    els.forEach((el) => io.observe(el));
}

const whenVisible = (el, fn) => {
    const io = new IntersectionObserver(([e]) => {
        if (!e.isIntersecting) return;
        io.disconnect();
        fn();
    });
    io.observe(el);
};

function initReveals() {
    reveal($$('.stat-box'), { y: 50, rotateX: -12, scale: 0.96, transformPerspective: 1000 }, { stagger: 0.07, duration: 1.1 });
    const cards = $$('.content .card, .content .form-section').filter((el) => !el.parentElement.closest('.card, .form-section'));
    reveal(cards, { y: 34 }, { stagger: 0.07 });
    reveal($$('.alert'), { y: -14, scale: 0.98 });
    reveal($$('tbody tr').slice(0, 60), { y: 12 }, { stagger: 0.03, duration: 0.7 });
    reveal($$('.activity-item, .top-page'), { x: -14 }, { stagger: 0.04, duration: 0.7 });
    $$('.chart-bar-wrap').forEach((wrap) => {
        const bars = $$('.chart-bar', wrap);
        gsap.set(bars, { scaleY: 0, transformOrigin: 'bottom' });
        whenVisible(wrap, () => gsap.to(bars, { scaleY: 1, duration: 1, stagger: 0.02, clearProps: 'transform' }));
    });
    $$('.top-page-bar, .device-seg').forEach((bar) => {
        gsap.set(bar, { scaleX: 0, transformOrigin: 'left' });
        whenVisible(bar, () => gsap.to(bar, { scaleX: 1, duration: 1.2, clearProps: 'transform' }));
    });
}

/* ── Banner + login stage ─────────────────────────────────── */
function addGridGlow(host) {
    if (!fine) return;
    host.classList.add('has-grid');
    const g = document.createElement('div');
    g.className = 'grid-glow';
    g.setAttribute('aria-hidden', 'true');
    host.prepend(g);
    const target = host.id === 'login-stage' ? window : host;
    target.addEventListener('pointermove', (e) => {
        const r = host.getBoundingClientRect();
        g.style.setProperty('--gx', `${e.clientX - r.left}px`);
        g.style.setProperty('--gy', `${e.clientY - r.top}px`);
    }, { passive: true });
    if (target === window) g.style.opacity = '.55';
}

function scramble(el) {
    const GLYPHS = '!<>-_\\/[]{}—=+*^?#01';
    const text = el.textContent.trim();
    if (!text) return;
    el.setAttribute('aria-label', text);
    const p = { v: 0 };
    gsap.to(p, {
        v: 1, duration: 0.8, ease: 'none',
        onUpdate: () => {
            const shown = Math.floor(text.length * p.v);
            let out = text.slice(0, shown);
            for (let i = shown; i < text.length; i++) out += text[i] === ' ' ? ' ' : GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
            el.textContent = out;
        },
        onComplete: () => { el.textContent = text; },
    });
}

function initBanner() {
    const banner = $('#page-banner');
    if (!banner) return;
    startAurora(banner);
    addGridGlow(banner);
    const words = splitWords($('h1', banner));
    gsap.set(words, { yPercent: 118, rotate: 5, transformOrigin: '0% 100%' });
    gsap.to(words, { yPercent: 0, rotate: 0, duration: 1.1, stagger: 0.06, delay: 0.25 });
    gsap.delayedCall(0.2, () => scramble($('.banner-tag', banner)));
}

function initLogin() {
    const stage = $('#login-stage');
    if (!stage) return;
    startAurora(stage);
    addGridGlow(stage);
    const words = splitWords($('[data-split]'));
    const rise = $$('[data-rise]');
    gsap.set(words, { yPercent: 118, rotate: 5, transformOrigin: '0% 100%' });
    gsap.set(rise, { opacity: 0, y: 30 });
    gsap.timeline({ delay: 0.25 })
        .to(words, { yPercent: 0, rotate: 0, duration: 1.2, stagger: 0.09 }, 0)
        .to(rise, { opacity: 1, y: 0, duration: 1, stagger: 0.1, clearProps: 'transform,opacity' }, 0.25);
    $$('[data-shake]').forEach((el) => gsap.fromTo(el, { x: -12 }, { x: 0, duration: 1.1, delay: 0.7, ease: 'elastic.out(1.4,.25)' }));
}

/* ── Sidebar ──────────────────────────────────────────────── */
function initSidebar() {
    const nav = $('.sidebar-nav');
    if (!nav) return;
    const items = $$('.nav-item', nav);

    let first = false;
    try {
        first = !sessionStorage.getItem('sk-a-nav');
        sessionStorage.setItem('sk-a-nav', '1');
    } catch (e) { /* private mode */ }
    if (first) gsap.from(items, { opacity: 0, x: -24, duration: 0.8, stagger: 0.025, delay: 0.3, clearProps: 'transform,opacity' });

    if (!fine) return;
    const pill = document.createElement('div');
    pill.className = 'side-pill';
    pill.setAttribute('aria-hidden', 'true');
    nav.prepend(pill);
    items.forEach((item) => item.addEventListener('pointerenter', () => {
        gsap.to(pill, { y: item.offsetTop, height: item.offsetHeight, opacity: item.classList.contains('active') ? 0 : 1, duration: 0.5, overwrite: 'auto' });
    }));
    nav.addEventListener('pointerleave', () => gsap.to(pill, { opacity: 0, duration: 0.3, overwrite: 'auto' }));
}

/* ── Cards ────────────────────────────────────────────────── */
function initCards() {
    if (!fine) return;
    const spotted = [...$$('.stat-box'), ...$$('.content .card').filter((el) => !el.parentElement.closest('.card')), ...$$('.login-box')];
    spotted.forEach((el) => {
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

    $$('.stat-box').forEach((el) => {
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
            ry(((e.clientX - r.left) / r.width - 0.5) * 10);
            rx(-((e.clientY - r.top) / r.height - 0.5) * 10);
        }, { passive: true });
        el.addEventListener('pointerleave', () => {
            if (!active) return;
            active = false;
            gsap.to(el, { rotateX: 0, rotateY: 0, duration: 1, overwrite: 'auto', clearProps: 'transform' });
        });
    });
}

/* ── Buttons ──────────────────────────────────────────────── */
function initButtons() {
    document.addEventListener('pointerdown', (e) => {
        const btn = e.target.closest?.('.btn, .login-btn, .view-site-btn');
        if (!btn) return;
        const r = btn.getBoundingClientRect();
        const dot = document.createElement('span');
        dot.className = 'btn-ripple';
        dot.style.left = `${e.clientX - r.left}px`;
        dot.style.top = `${e.clientY - r.top}px`;
        dot.addEventListener('animationend', () => dot.remove());
        if (getComputedStyle(btn).position === 'static') btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.append(dot);
    });

    if (!fine) return;

    $$('.btn-ghost').forEach((btn) => {
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
            gsap.to(fill, { scale: p.reach, duration: 0.6, overwrite: true });
        });
        btn.addEventListener('pointerleave', (e) => {
            const p = at(e);
            gsap.set(fill, { left: p.left, top: p.top });
            gsap.to(fill, { scale: 0, duration: 0.5, overwrite: true });
        });
    });

    // Magnetic pull on the main calls to action only: small table buttons need to hold still
    $$('.card-head .btn-primary, .view-site-btn, .login-btn, form > .btn-primary').forEach((btn) => {
        const x = gsap.quickTo(btn, 'x', { duration: 0.6, ease: 'power3' });
        const y = gsap.quickTo(btn, 'y', { duration: 0.6, ease: 'power3' });
        btn.addEventListener('pointermove', (e) => {
            const r = btn.getBoundingClientRect();
            x((e.clientX - (r.left + r.width / 2)) * 0.2);
            y((e.clientY - (r.top + r.height / 2)) * 0.2);
        });
        btn.addEventListener('pointerleave', () => gsap.to(btn, { x: 0, y: 0, duration: 1, ease: 'elastic.out(1,.35)', overwrite: 'auto' }));
    });
}

/* ── Flash + cursor ───────────────────────────────────────── */
function burst(x, y) {
    const colors = ['var(--p)', 'var(--s)', 'var(--magenta)'];
    for (let i = 0; i < 26; i++) {
        const d = document.createElement('i');
        d.className = 'burst-dot';
        d.style.left = `${x}px`;
        d.style.top = `${y}px`;
        d.style.background = colors[i % 3];
        document.body.append(d);
        const ang = Math.random() * Math.PI * 2, dist = 50 + Math.random() * 150;
        gsap.to(d, {
            x: Math.cos(ang) * dist, y: Math.sin(ang) * dist + 50, rotate: Math.random() * 500,
            opacity: 0, scale: 0.3, duration: 1 + Math.random() * 0.6, ease: 'power3.out',
            onComplete: () => d.remove(),
        });
    }
}

function initFlash() {
    const ico = $('.alert-success .alert-ico');
    if (!ico) return;
    gsap.from(ico, { scale: 0, rotate: -160, duration: 0.9, delay: 0.55, ease: 'back.out(2.4)' });
    gsap.delayedCall(0.7, () => {
        const r = ico.getBoundingClientRect();
        burst(r.left + r.width / 2, r.top + r.height / 2);
    });
}

function initCursor() {
    if (!fine) return;
    root.classList.add('has-cursor', 'cur-hide');
    const dot = $('#cur-dot'), ring = $('#cur-ring');
    const dx = gsap.quickSetter(dot, 'x', 'px'), dy = gsap.quickSetter(dot, 'y', 'px');
    const rx = gsap.quickTo(ring, 'x', { duration: 0.4, ease: 'power3' }), ry = gsap.quickTo(ring, 'y', { duration: 0.4, ease: 'power3' });
    window.addEventListener('pointermove', (e) => {
        root.classList.remove('cur-hide');
        dx(e.clientX); dy(e.clientY);
        rx(e.clientX); ry(e.clientY);
    }, { passive: true });
    document.addEventListener('mouseleave', () => root.classList.add('cur-hide'));
    document.addEventListener('pointerover', (e) => {
        if (!e.target.closest) return;
        const onField = e.target.closest('input,textarea,select');
        const hot = !onField && e.target.closest('a,button,label,[onclick],summary');
        gsap.to(ring, { scale: hot ? 1.5 : 1, opacity: onField ? 0 : 1, duration: 0.35 });
        gsap.to(dot, { scale: hot ? 1.8 : 1, opacity: onField ? 0 : 1, duration: 0.25 });
    }, { passive: true });
}

/* ── Curtain: quick enough not to slow down real work ─────── */
function initCurtain(boot) {
    const curtain = $('#curtain');
    const cols = $$('.cu-cols i', curtain);
    const label = $('.cu-label', curtain);
    curtain.style.animation = 'none';

    let next = '';
    try {
        next = sessionStorage.getItem('sk-a-next') || '';
        sessionStorage.removeItem('sk-a-next');
    } catch (e) { /* private mode */ }
    if (next) {
        label.textContent = next;
        gsap.set(label, { visibility: 'visible' });
    }

    boot();
    gsap.timeline()
        .to(label, { opacity: 0, y: -18, duration: 0.3, ease: 'power3.in' }, 0)
        .to(cols, { yPercent: -100, duration: 0.7, ease: 'expo.inOut', stagger: { each: 0.035, from: 'center' } }, 0.05)
        .set(curtain, { display: 'none' });

    const leave = (text, done) => {
        try { sessionStorage.setItem('sk-a-next', text); } catch (e) { /* ignore */ }
        gsap.killTweensOf([cols, label]);
        gsap.set(curtain, { display: 'block' });
        gsap.set(cols, { yPercent: 100 });
        label.textContent = text;
        gsap.set(label, { visibility: 'visible', opacity: 0, y: 18 });
        return gsap.timeline({ onComplete: done })
            .to(cols, { yPercent: 0, duration: 0.42, ease: 'power4.inOut', stagger: { each: 0.025, from: 'edges' } })
            .to(label, { opacity: 1, y: 0, duration: 0.25, ease: 'power3.out' }, 0.14);
    };

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (a.target === '_blank' || a.hasAttribute('download') || a.getAttribute('href').startsWith('#')) return;
        const url = new URL(a.href, location.href);
        if (url.origin !== location.origin || !/^https?:$/.test(url.protocol)) return;
        if (url.pathname === location.pathname && url.search === location.search) return;
        e.preventDefault();
        leave(a.textContent.replace(/[→↗←]/g, '').replace(/\p{Extended_Pictographic}/gu, '').replace(/\s+/g, ' ').trim().slice(0, 28), () => { location.href = a.href; });
    });

    // Bubbles after any inline onsubmit, so a cancelled "are you sure?" never gets here.
    // The browser keeps submitting underneath; the curtain just covers the wait.
    document.addEventListener('submit', (e) => {
        if (e.defaultPrevented) return;
        const form = e.target;
        if (form.target === '_blank') return;
        const btn = e.submitter;
        if (btn) {
            btn.classList.add('is-busy');
            const spin = document.createElement('span');
            spin.className = 'btn-spin';
            spin.setAttribute('aria-hidden', 'true');
            btn.append(spin);
        }
        const method = form.querySelector('input[name="_method"]')?.value?.toUpperCase();
        const text = form.dataset.label || (method === 'DELETE' ? 'Deleting…' : (form.method || '').toLowerCase() === 'get' ? 'Loading…' : 'Saving…');
        leave(text, () => {});
    });

    // Back/forward cache restores a covered page otherwise
    window.addEventListener('pageshow', (e) => {
        if (!e.persisted) return;
        gsap.set(curtain, { display: 'none' });
        $$('.is-busy').forEach((b) => { b.classList.remove('is-busy'); $('.btn-spin', b)?.remove(); });
    });
}

/* ── Boot ─────────────────────────────────────────────────── */
function bootPage() {
    safely('banner', initBanner);
    safely('login', initLogin);
    safely('reveals', initReveals);
    safely('odometers', () => buildOdometers('.stat-box-num, .metric-big', whenVisible, gsap));
    safely('cards', initCards);
    safely('buttons', initButtons);
    safely('sidebar', initSidebar);
    safely('flash', initFlash);
    safely('cursor', initCursor);
}

if (root.classList.contains('m-on')) {
    safely('smooth scroll', () => {
        lenis = new Lenis({ lerp: 0.12, prevent: (node) => !!node.closest?.('textarea, select, .sidebar, .table-wrap, [data-lenis-prevent]') });
        gsap.ticker.add((t) => lenis.raf(t * 1000));
        gsap.ticker.lagSmoothing(0);
    });
    initCurtain(bootPage);
}
