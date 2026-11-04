// Liquid aurora behind hero sections. Raw WebGL (no library) so it stays tiny,
// reads the Theme panel colours from CSS variables, and pauses when off-screen.

const VERT = `attribute vec2 p;void main(){gl_Position=vec4(p,0.,1.);}`;

const FRAG = `
precision mediump float;
uniform vec2 u_res;
uniform vec2 u_mouse;
uniform float u_time;
uniform vec3 u_a;
uniform vec3 u_b;
uniform vec3 u_c;
uniform float u_k;

vec2 hash(vec2 p){
  p = vec2(dot(p, vec2(127.1, 311.7)), dot(p, vec2(269.5, 183.3)));
  return -1.0 + 2.0 * fract(sin(p) * 43758.5453123);
}
float noise(vec2 p){
  vec2 i = floor(p), f = fract(p);
  vec2 u = f * f * (3.0 - 2.0 * f);
  return mix(mix(dot(hash(i), f), dot(hash(i + vec2(1, 0)), f - vec2(1, 0)), u.x),
             mix(dot(hash(i + vec2(0, 1)), f - vec2(0, 1)), dot(hash(i + vec2(1, 1)), f - vec2(1, 1)), u.x), u.y);
}
float fbm(vec2 p){
  float v = 0.0, a = 0.5;
  for (int i = 0; i < 4; i++) { v += a * noise(p); p = p * 2.02 + vec2(1.7, 9.2); a *= 0.5; }
  return v;
}
void main(){
  vec2 uv = gl_FragCoord.xy / u_res;
  vec2 st = uv * vec2(u_res.x / u_res.y, 1.0);
  vec2 m = u_mouse * vec2(u_res.x / u_res.y, 1.0);
  float t = u_time * 0.07;

  // Domain warping gives the slow liquid folds; the pointer bends them locally.
  vec2 warp = vec2(fbm(st * 1.15 + t), fbm(st * 1.15 - t + 4.3));
  float pull = exp(-distance(st, m) * 2.1);
  float f = fbm(st * 1.5 + warp * 1.8 + (st - m) * pull * 0.9);

  vec3 col = mix(u_a, u_b, smoothstep(-0.35, 0.45, f));
  col = mix(col, u_c, smoothstep(-0.1, 0.5, warp.y));

  // Fade toward the top and bottom edges so it melts into the page.
  float edge = smoothstep(0.0, 0.32, uv.y) * smoothstep(1.0, 0.62, uv.y);
  float alpha = (0.1 + 0.26 * smoothstep(-0.25, 0.5, f) + pull * 0.16) * edge * u_k;
  gl_FragColor = vec4(col * alpha, alpha);
}`;

const rgbVar = (name, fallback) => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    if (raw.startsWith('#') && raw.length === 7) {
        return [1, 3, 5].map((i) => parseInt(raw.slice(i, i + 2), 16) / 255);
    }
    const parts = raw.split(',').map((v) => parseFloat(v) / 255);
    return parts.length === 3 && parts.every((v) => !Number.isNaN(v)) ? parts : fallback;
};

export function startAurora(host) {
    const canvas = document.createElement('canvas');
    canvas.className = 'aurora';
    canvas.setAttribute('aria-hidden', 'true');
    const gl = canvas.getContext('webgl', { alpha: true, antialias: false, premultipliedAlpha: true, powerPreference: 'low-power' });
    if (!gl) return null;

    const compile = (type, src) => {
        const s = gl.createShader(type);
        gl.shaderSource(s, src);
        gl.compileShader(s);
        return gl.getShaderParameter(s, gl.COMPILE_STATUS) ? s : null;
    };
    const vs = compile(gl.VERTEX_SHADER, VERT);
    const fs = compile(gl.FRAGMENT_SHADER, FRAG);
    if (!vs || !fs) return null;

    const prog = gl.createProgram();
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) return null;
    gl.useProgram(prog);

    gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    const loc = gl.getAttribLocation(prog, 'p');
    gl.enableVertexAttribArray(loc);
    gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

    const u = (n) => gl.getUniformLocation(prog, n);
    const uRes = u('u_res'), uMouse = u('u_mouse'), uTime = u('u_time');
    const applyPalette = () => {
        gl.uniform3fv(u('u_a'), rgbVar('--aurora-a', [0.66, 0.33, 0.97]));
        gl.uniform3fv(u('u_b'), rgbVar('--aurora-b', [0.65, 0.55, 0.98]));
        gl.uniform3fv(u('u_c'), rgbVar('--aurora-c', [0.92, 0.9, 1]));
        gl.uniform1f(u('u_k'), parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--aurora-k')) || 1);
    };
    applyPalette();
    window.addEventListener('sk-theme', applyPalette);

    host.prepend(canvas);

    const resize = () => {
        const r = host.getBoundingClientRect();
        const scale = 0.45; // soft by nature, so a low resolution looks identical and costs far less
        canvas.width = Math.max(2, Math.round(r.width * scale));
        canvas.height = Math.max(2, Math.round(r.height * scale));
        gl.viewport(0, 0, canvas.width, canvas.height);
        gl.uniform2f(uRes, canvas.width, canvas.height);
    };
    resize();
    window.addEventListener('resize', resize, { passive: true });

    let tx = 0.5, ty = 0.5, mx = 0.5, my = 0.5, visible = true, raf = 0;
    host.addEventListener('pointermove', (e) => {
        const r = host.getBoundingClientRect();
        tx = (e.clientX - r.left) / r.width;
        ty = 1 - (e.clientY - r.top) / r.height;
    }, { passive: true });

    const start = performance.now();
    const frame = (now) => {
        raf = visible ? requestAnimationFrame(frame) : 0;
        mx += (tx - mx) * 0.04;
        my += (ty - my) * 0.04;
        gl.uniform2f(uMouse, mx, my);
        gl.uniform1f(uTime, (now - start) / 1000 + 20);
        gl.drawArrays(gl.TRIANGLES, 0, 3);
    };

    new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
        if (visible && !raf) raf = requestAnimationFrame(frame);
    }).observe(host);

    raf = requestAnimationFrame(frame);
    return canvas;
}
