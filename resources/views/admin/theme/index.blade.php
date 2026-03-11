@extends('admin.layouts.app')
@section('title', 'Theme & Colors')

@section('styles')
<style>
.theme-preview{background:var(--preview-dark,#050510);border-radius:16px;padding:24px;margin-bottom:28px;position:relative;overflow:hidden}
.theme-preview::before{content:'Live Preview';position:absolute;top:12px;right:16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,255,255,.3)}
.preview-nav{background:rgba(255,255,255,.07);border-radius:10px;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.preview-logo{font-weight:800;color:#fff;font-size:1rem}
.preview-btn{padding:8px 20px;border-radius:8px;font-size:12px;font-weight:700;color:#fff}
.preview-hero{padding:24px 0;text-align:center}
.preview-hero h2{font-size:1.8rem;font-weight:800;color:#fff;margin-bottom:8px}
.preview-hero p{color:rgba(255,255,255,.5);font-size:13px;margin-bottom:16px}
.preview-cards{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:16px}
.preview-card{border-radius:10px;padding:16px;text-align:center}
.preview-card-num{font-size:1.4rem;font-weight:800;color:#fff}
.preview-card-lbl{font-size:10px;color:rgba(255,255,255,.4);margin-top:4px}
.color-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px}
.color-item{border:1.5px solid #e8ecf0;border-radius:14px;padding:16px;transition:border-color .2s}
.color-item:hover{border-color:rgba(124,58,237,.3)}
.color-item label{display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:10px;text-transform:uppercase;letter-spacing:.7px}
.color-swatch-big{width:100%;height:60px;border-radius:10px;border:2px solid rgba(0,0,0,.08);cursor:pointer;transition:transform .2s}
.color-swatch-big:hover{transform:scale(1.02)}
.hex-input{width:100%;margin-top:8px;padding:8px 12px;border:1.5px solid #e8ecf0;border-radius:8px;font-size:13px;font-family:monospace;color:#374151;outline:none;transition:border-color .2s}
.hex-input:focus{border-color:var(--p)}
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start">
    <div>
        <form method="POST" action="{{ route('admin.theme.update') }}" id="themeForm">
            @csrf

            {{-- Color palette --}}
            <div class="card" style="margin-bottom:20px">
                <div class="card-head"><div class="card-title">🎨 Color Palette</div></div>
                <div class="card-body">
                    <div class="color-grid">
                        @php
                        $colors = [
                            ['key'=>'primary_color','label'=>'Primary Color','hint'=>'Buttons & main accents'],
                            ['key'=>'secondary_color','label'=>'Secondary Color','hint'=>'Gradient & hover states'],
                            ['key'=>'accent_color','label'=>'Accent Color','hint'=>'Stars & highlights'],
                            ['key'=>'text_color','label'=>'Text Color','hint'=>'Body text'],
                            ['key'=>'bg_color','label'=>'Background','hint'=>'Page background'],
                            ['key'=>'dark_bg','label'=>'Dark Background','hint'=>'Hero & dark sections'],
                        ];
                        @endphp
                        @foreach($colors as $c)
                        <div class="color-item">
                            <label>{{ $c['label'] }}</label>
                            <input type="color" name="{{ $c['key'] }}" value="{{ $theme[$c['key']] ?? '#7c3aed' }}" class="color-swatch-big" onchange="updatePreview()">
                            <input type="text" class="hex-input" value="{{ $theme[$c['key']] ?? '#7c3aed' }}" placeholder="#000000" oninput="syncColorFromHex(this)" data-for="{{ $c['key'] }}">
                            <div style="font-size:11px;color:#94a3b8;margin-top:4px">{{ $c['hint'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Typography --}}
            <div class="card" style="margin-bottom:20px">
                <div class="card-head"><div class="card-title">🔤 Typography & Style</div></div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Heading Font</label>
                            <select name="font_family" class="form-control" onchange="updatePreview()">
                                @foreach(['Syne'=>'Syne (Current)','Playfair Display'=>'Playfair Display','Space Grotesk'=>'Space Grotesk','Plus Jakarta Sans'=>'Plus Jakarta Sans','Raleway'=>'Raleway','Montserrat'=>'Montserrat','Oswald'=>'Oswald'] as $val => $label)
                                <option value="{{ $val }}" {{ ($theme['font_family'] ?? 'Syne') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Border Radius</label>
                            <select name="border_radius" class="form-control" onchange="updatePreview()">
                                <option value="4px" {{ ($theme['border_radius'] ?? '12px') === '4px' ? 'selected' : '' }}>Sharp (4px)</option>
                                <option value="8px" {{ ($theme['border_radius'] ?? '12px') === '8px' ? 'selected' : '' }}>Slightly Rounded (8px)</option>
                                <option value="12px" {{ ($theme['border_radius'] ?? '12px') === '12px' ? 'selected' : '' }}>Rounded (12px)</option>
                                <option value="20px" {{ ($theme['border_radius'] ?? '12px') === '20px' ? 'selected' : '' }}>Very Rounded (20px)</option>
                                <option value="999px" {{ ($theme['border_radius'] ?? '12px') === '999px' ? 'selected' : '' }}>Pill (999px)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Presets --}}
            <div class="card" style="margin-bottom:20px">
                <div class="card-head"><div class="card-title">✨ Quick Presets</div></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px">
                        @php
                        $presets = [
                            ['name'=>'Purple Dream','p'=>'#7c3aed','s'=>'#06b6d4','a'=>'#f59e0b','dark'=>'#050510','bg'=>'#fafafa','text'=>'#0f0f23'],
                            ['name'=>'Rose Gold','p'=>'#e11d48','s'=>'#f97316','a'=>'#eab308','dark'=>'#0f0a0a','bg'=>'#fafafa','text'=>'#1c0e0e'],
                            ['name'=>'Ocean Blue','p'=>'#2563eb','s'=>'#06b6d4','a'=>'#10b981','dark'=>'#030712','bg'=>'#f8fafc','text'=>'#0f172a'],
                            ['name'=>'Forest','p'=>'#059669','s'=>'#0d9488','a'=>'#d97706','dark'=>'#030f0a','bg'=>'#fafafa','text'=>'#052e16'],
                            ['name'=>'Midnight','p'=>'#6366f1','s'=>'#8b5cf6','a'=>'#f59e0b','dark'=>'#03030a','bg'=>'#f8f8ff','text'=>'#1e1b4b'],
                            ['name'=>'Sunset','p'=>'#dc2626','s'=>'#ea580c','a'=>'#ca8a04','dark'=>'#0f0500','bg'=>'#fafafa','text'=>'#1c0d0d'],
                        ];
                        @endphp
                        @foreach($presets as $preset)
                        <button type="button" onclick="applyPreset({{ json_encode($preset) }})" style="border:1.5px solid #e8ecf0;border-radius:12px;padding:12px;text-align:left;background:#fff;cursor:pointer;transition:all .2s" onmouseenter="this.style.borderColor='rgba(124,58,237,.4)';this.style.boxShadow='0 4px 15px rgba(124,58,237,.1)'" onmouseleave="this.style.borderColor='#e8ecf0';this.style.boxShadow=''">
                            <div style="display:flex;gap:4px;margin-bottom:8px">
                                <div style="width:20px;height:20px;border-radius:4px;background:{{ $preset['p'] }}"></div>
                                <div style="width:20px;height:20px;border-radius:4px;background:{{ $preset['s'] }}"></div>
                                <div style="width:20px;height:20px;border-radius:4px;background:{{ $preset['a'] }}"></div>
                                <div style="width:20px;height:20px;border-radius:4px;background:{{ $preset['dark'] }}"></div>
                            </div>
                            <div style="font-size:12px;font-weight:600;color:#374151">{{ $preset['name'] }}</div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="font-size:15px;padding:13px 32px">
                💾 Save Theme Changes
            </button>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost" style="margin-left:12px">
                🌐 Preview Site
            </a>
        </form>
    </div>

    {{-- Live preview --}}
    <div style="position:sticky;top:92px">
        <div class="card">
            <div class="card-head"><div class="card-title">👁️ Live Preview</div></div>
            <div class="card-body">
                <div id="livePreview" style="background:#050510;border-radius:14px;padding:18px;overflow:hidden">
                    <div style="background:rgba(255,255,255,.07);border-radius:8px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <span id="prev-logo" style="font-weight:800;color:#fff;font-size:14px">SK Artistic</span>
                        <span id="prev-btn" style="background:linear-gradient(135deg,#7c3aed,#06b6d4);color:#fff;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:700">Contact Us</span>
                    </div>
                    <div style="text-align:center;padding:16px 0">
                        <div id="prev-title" style="font-size:1.4rem;font-weight:800;background:linear-gradient(135deg,#7c3aed,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:6px">Designing without borders</div>
                        <div style="color:rgba(255,255,255,.4);font-size:11px">From idea to execution...</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px">
                        @foreach(['46+ Revenue','65+ Projects','70+ Clients','60+ Reviews'] as $s)
                        <div id="prev-card" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:14px;text-align:center">
                            <div style="font-size:1.1rem;font-weight:800;color:#fff">{{ explode(' ', $s)[0] }}</div>
                            <div style="font-size:10px;color:rgba(255,255,255,.4);margin-top:3px">{{ implode(' ', array_slice(explode(' ', $s), 1)) }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <div id="prev-primary-btn" style="flex:1;background:linear-gradient(135deg,#7c3aed,#06b6d4);color:#fff;padding:10px;border-radius:8px;text-align:center;font-size:12px;font-weight:700">Get Started</div>
                        <div style="flex:1;border:1.5px solid rgba(255,255,255,.2);color:rgba(255,255,255,.7);padding:9px;border-radius:8px;text-align:center;font-size:12px;font-weight:600">Our Services</div>
                    </div>
                </div>

                <div style="margin-top:16px;background:#fff;border:1px solid rgba(0,0,0,.07);border-radius:14px;padding:16px">
                    <div style="font-size:12px;font-weight:700;color:#374151;margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px">Light Section Preview</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        @foreach(['Service 1','Service 2'] as $s)
                        <div id="prev-service-card" style="border:1.5px solid rgba(124,58,237,.12);border-radius:10px;padding:14px">
                            <div style="font-size:1.2rem;margin-bottom:8px">⚡</div>
                            <div style="font-size:12px;font-weight:700;margin-bottom:4px">{{ $s }}</div>
                            <div style="font-size:11px;color:#6b7280">Short description here</div>
                            <div id="prev-link" style="font-size:11px;font-weight:600;color:#7c3aed;margin-top:8px">Learn more →</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const p = document.querySelector('[name=primary_color]').value;
    const s = document.querySelector('[name=secondary_color]').value;
    const dark = document.querySelector('[name=dark_bg]').value;
    const bg = document.querySelector('[name=bg_color]').value;
    const r = document.querySelector('[name=border_radius]').value;

    document.getElementById('livePreview').style.background = dark;
    const gradient = `linear-gradient(135deg,${p},${s})`;
    document.getElementById('prev-btn').style.background = gradient;
    document.getElementById('prev-title').style.background = gradient;
    document.getElementById('prev-title').style.webkitBackgroundClip = 'text';
    document.getElementById('prev-title').style.webkitTextFillColor = 'transparent';
    document.getElementById('prev-primary-btn').style.background = gradient;
    document.querySelectorAll('[id=prev-link]').forEach(el => el.style.color = p);
    document.querySelectorAll('[id=prev-service-card]').forEach(el => el.style.borderColor = p + '30');
}

function syncColorFromHex(input) {
    const val = input.value;
    const key = input.dataset.for;
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.querySelector(`[name=${key}]`).value = val;
        updatePreview();
    }
}

// Sync hex inputs from color pickers
document.querySelectorAll('input[type=color]').forEach(inp => {
    inp.addEventListener('input', () => {
        const hexInput = document.querySelector(`.hex-input[data-for="${inp.name}"]`);
        if (hexInput) hexInput.value = inp.value;
        updatePreview();
    });
});

function applyPreset(preset) {
    const map = {'p':'primary_color','s':'secondary_color','a':'accent_color','dark':'dark_bg','bg':'bg_color','text':'text_color'};
    for (const [short, long] of Object.entries(map)) {
        if (preset[short]) {
            const colorInp = document.querySelector(`[name=${long}]`);
            const hexInp = document.querySelector(`.hex-input[data-for=${long}]`);
            if (colorInp) colorInp.value = preset[short];
            if (hexInp) hexInp.value = preset[short];
        }
    }
    updatePreview();
}
</script>
@endsection