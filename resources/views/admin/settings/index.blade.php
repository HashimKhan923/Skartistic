@extends('admin.layouts.app')
@section('title', 'General Settings')
@section('content')

<style>
.sg { display:grid; grid-template-columns:1fr 1fr; gap:22px }
.sg .full { grid-column: 1 / -1 }
.g2col { display:grid; grid-template-columns:1fr 1fr; gap:14px }
.g3col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px }

/* Key badge */
.kb {
  display:inline-block; font-size:9.5px; font-family:monospace;
  background:rgba(99,102,241,.08); color:#6366f1;
  border:1px solid rgba(99,102,241,.18); padding:1px 6px;
  border-radius:4px; margin-left:5px; letter-spacing:.2px; vertical-align:middle;
}
/* Tip/note box */
.tip {
  font-size:12px; color:#64748b; background:#f8fafc;
  border:1px solid #e2e8f0; border-left:3px solid #6366f1;
  border-radius:0 6px 6px 0; padding:9px 14px; margin-bottom:14px; line-height:1.6;
}
.tip strong { color:#4f46e5 }
.tip.warn { border-left-color:#ea580c }
.tip.warn strong { color:#ea580c }

/* Tag pill input */
.tag-wrap {
  border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 10px;
  min-height:44px; display:flex; flex-wrap:wrap; gap:6px; align-items:center;
  background:#fff; cursor:text; transition:border-color .2s, box-shadow .2s;
}
.tag-wrap:focus-within { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.08) }
.tag-pill {
  display:inline-flex; align-items:center; gap:4px;
  background:rgba(99,102,241,.1); color:#4338ca;
  font-size:12px; font-weight:500; padding:3px 10px 3px 12px; border-radius:100px;
  white-space:nowrap;
}
.tag-pill button { background:none;border:none;cursor:pointer;color:#4338ca;font-size:14px;line-height:1;padding:0 }
.tag-pill button:hover { color:#ef4444 }
.tag-inp { border:none;outline:none;font-size:13px;min-width:120px;flex:1;color:#1e293b;background:transparent }

/* Image preview */
.img-preview { margin-top:8px; max-height:90px; border-radius:6px; border:1px solid #e2e8f0; object-fit:cover; display:block }
.rm-chk { font-size:12px; color:#64748b; margin-top:6px; display:flex; align-items:center; gap:5px; cursor:pointer }
</style>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
@csrf

<div class="sg">

{{-- ══════════════════════════════════════════
     1. SITE INFORMATION
══════════════════════════════════════════ --}}
<div class="card">
  <div class="card-head"><div class="card-title">🏢 Site Information</div></div>
  <div class="card-body">

    <div class="form-group">
      <label class="form-label">Site Name <span class="req">*</span> <span class="kb">site_name</span></label>
      <input class="form-control" type="text" name="site_name"
        value="{{ $settings['site_name'] ?? '' }}" placeholder="SK Artistic">
    </div>

    <div class="form-group">
      <label class="form-label">Site Tagline <span class="kb">site_tagline</span></label>
      <input class="form-control" type="text" name="site_tagline"
        value="{{ $settings['site_tagline'] ?? '' }}"
        placeholder="Full-cycle digital agency building the future.">
      <small class="form-text">Used in footer description and meta fallback.</small>
    </div>

    <div class="form-group">
      <label class="form-label">Site Logo <span class="kb">site_logo</span></label>
      <input class="form-control" type="file" name="site_logo"
        accept="image/png,image/svg+xml,image/webp,image/jpeg">
      @if(!empty($settings['site_logo']))
        <img src="{{ asset('storage/'.$settings['site_logo']) }}" class="img-preview" alt="Logo">
        <label class="rm-chk"><input type="checkbox" name="remove_site_logo" value="1"> Remove current logo</label>
      @endif
      <small class="form-text">SVG or PNG transparent recommended. Displayed in the navbar.</small>
    </div>

    <div class="form-group">
      <label class="form-label">Email Address <span class="kb">site_email</span></label>
      <input class="form-control" type="email" name="site_email"
        value="{{ $settings['site_email'] ?? '' }}" placeholder="hello@skartistic.com">
    </div>

    <div class="form-group">
      <label class="form-label">Phone / WhatsApp <span class="kb">site_phone</span></label>
      <input class="form-control" type="text" name="site_phone"
        value="{{ $settings['site_phone'] ?? '' }}" placeholder="+92 300 0000000">
    </div>

    <div class="form-group">
      <label class="form-label">Office Address <span class="kb">site_address</span></label>
      <input class="form-control" type="text" name="site_address"
        value="{{ $settings['site_address'] ?? '' }}" placeholder="Karachi, Pakistan">
    </div>

    <div class="form-group">
      <label class="form-label">Footer Copyright <span class="kb">footer_copyright</span></label>
      <input class="form-control" type="text" name="footer_copyright"
        value="{{ $settings['footer_copyright'] ?? '© 2026 SK Artistic. All rights reserved.' }}">
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════
     2. HERO SECTION
══════════════════════════════════════════ --}}
<div class="card">
  <div class="card-head"><div class="card-title">🚀 Hero Section</div></div>
  <div class="card-body">

    <div class="tip">
      Hero layout: <strong>Static headline</strong> (small) → <strong>Animated cycling word</strong> (large purple) → subtitle → buttons.
    </div>

    <div class="form-group">
      <label class="form-label">Static Headline <span class="kb">hero_line1</span></label>
      <input class="form-control" type="text" name="hero_line1"
        value="{{ $settings['hero_line1'] ?? 'Transforming Visions Into' }}"
        placeholder="Transforming Visions Into">
      <small class="form-text">Smaller bold text shown above the animated cycling word.</small>
    </div>

    <div class="form-group">
      <label class="form-label">Animated Cycling Words <span class="kb">hero_animated_words</span></label>
      <div class="tip" style="margin-bottom:8px">
        Press <strong>Enter</strong> or <strong>comma</strong> to add each phrase. They cycle one-by-one with a letter blur-in animation. Max 6.
      </div>
      @php
        $aw    = $settings['hero_animated_words'] ?? 'Seamless Designs,Digital Creation,Innovative Ideas,Impactful Solutions';
        $awArr = array_filter(array_map('trim', explode(',', $aw)));
      @endphp
      <div class="tag-wrap" data-hidden="heroAnimHidden" onclick="this.querySelector('.tag-inp').focus()">
        @foreach($awArr as $w)
          <span class="tag-pill" data-val="{{ $w }}">
            {{ $w }}<button type="button" onclick="rmTag(this)">×</button>
          </span>
        @endforeach
        <input class="tag-inp" placeholder="e.g. Seamless Designs">
      </div>
      <input type="hidden" id="heroAnimHidden" name="hero_animated_words" value="{{ $aw }}">
    </div>

    <div class="form-group">
      <label class="form-label">Hero Subtitle <span class="kb">hero_subtitle</span></label>
      <textarea class="form-control" name="hero_subtitle" rows="3"
        placeholder="SK Artistic is where ideas turn into innovation…">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
      <small class="form-text">Short paragraph below the animated word. ~150 chars max recommended.</small>
    </div>

    <div class="g2col">
      <div class="form-group">
        <label class="form-label">Primary Button Text <span class="kb">hero_btn1</span></label>
        <input class="form-control" type="text" name="hero_btn1"
          value="{{ $settings['hero_btn1'] ?? 'Schedule a Meeting' }}"
          placeholder="Schedule a Meeting">
        <small class="form-text">Dark filled pill (links to Contact).</small>
      </div>
      <div class="form-group">
        <label class="form-label">Secondary Button Text <span class="kb">hero_btn2</span></label>
        <input class="form-control" type="text" name="hero_btn2"
          value="{{ $settings['hero_btn2'] ?? 'Our Work' }}"
          placeholder="Our Work">
        <small class="form-text">Outlined pill (links to Portfolio).</small>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Trusted Badge Label <span class="kb">hero_trust_text</span></label>
      <input class="form-control" type="text" name="hero_trust_text"
        value="{{ $settings['hero_trust_text'] ?? 'clients worldwide.' }}"
        placeholder="clients worldwide.">
      <small class="form-text">Badge shows: "Trusted by <strong>70+</strong> [your text]". The number comes from Stats → Clients.</small>
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════
     3. PROMO VIDEO / DEVICE SECTION
══════════════════════════════════════════ --}}
<div class="card full">
  <div class="card-head"><div class="card-title">🎬 Promo Video / Device Section</div></div>
  <div class="card-body">

    <div class="tip">
      <strong>Priority order:</strong>
      ① YouTube/Vimeo URL &nbsp;→&nbsp;
      ② Uploaded Video File &nbsp;→&nbsp;
      ③ Promo Image &nbsp;→&nbsp;
      ④ Animated fallback cards (auto).
      Only set what you need — the rest falls back automatically.
    </div>

    <div class="g3col">

      <div class="form-group">
        <label class="form-label">YouTube / Vimeo URL <span class="kb">promo_video</span></label>
        <input class="form-control" type="url" name="promo_video"
          value="{{ $settings['promo_video'] ?? '' }}"
          placeholder="https://youtube.com/watch?v=...">
        <small class="form-text">
          Accepts any YouTube URL format (watch, youtu.be, embed). Auto-extracted and embedded as muted autoplay iframe.
        </small>
      </div>

      <div class="form-group">
        <label class="form-label">Upload Video File <span class="kb">promo_video_file</span></label>
        <input class="form-control" type="file" name="promo_video_file"
          accept="video/mp4,video/webm">
        @if(!empty($settings['promo_video_file']))
          <small class="form-text" style="color:#16a34a;display:block;margin-top:6px">
            ✓ {{ basename($settings['promo_video_file']) }}
          </small>
          <label class="rm-chk">
            <input type="checkbox" name="remove_promo_video_file" value="1"> Remove uploaded video
          </label>
        @endif
        <small class="form-text">MP4 or WebM. Used when no YouTube URL is set. Max 50MB.</small>
      </div>

      <div class="form-group">
        <label class="form-label">Poster / Fallback Image <span class="kb">promo_image</span></label>
        <input class="form-control" type="file" name="promo_image" accept="image/*">
        @if(!empty($settings['promo_image']))
          <img src="{{ asset('storage/'.$settings['promo_image']) }}"
            class="img-preview" alt="Promo Image">
          <label class="rm-chk">
            <input type="checkbox" name="remove_promo_image" value="1"> Remove image
          </label>
        @endif
        <small class="form-text">Used as video poster frame, or static fallback when no video is set.</small>
      </div>

    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     4. MARQUEE BAND
══════════════════════════════════════════ --}}
<div class="card">
  <div class="card-head"><div class="card-title">📢 Scrolling Marquee Band</div></div>
  <div class="card-body">
    <div class="tip">
      The scrolling dark banner between the device section and services grid. Press <strong>Enter</strong> or <strong>comma</strong> to add each item.
    </div>
    @php
      $mw    = $settings['marquee_words'] ?? 'Web Development,UI / UX Design,Mobile Apps,Brand Identity,Backend APIs,Motion Design,Graphic Design,Integrations';
      $mwArr = array_filter(array_map('trim', explode(',', $mw)));
    @endphp
    <div class="form-group">
      <label class="form-label">Marquee Items <span class="kb">marquee_words</span></label>
      <div class="tag-wrap" data-hidden="marqueeHidden" onclick="this.querySelector('.tag-inp').focus()">
        @foreach($mwArr as $w)
          <span class="tag-pill" data-val="{{ $w }}">
            {{ $w }}<button type="button" onclick="rmTag(this)">×</button>
          </span>
        @endforeach
        <input class="tag-inp" placeholder="e.g. Web Development">
      </div>
      <input type="hidden" id="marqueeHidden" name="marquee_words" value="{{ $mw }}">
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     5. STATISTICS
══════════════════════════════════════════ --}}
<div class="card">
  <div class="card-head"><div class="card-title">📊 Statistics</div></div>
  <div class="card-body">
    <div class="tip">
      Used in: hero trusted badge, stats counter band, and CTA section. Include <strong>+</strong> symbol if desired (e.g. <strong>70+</strong>).
    </div>
    <div class="g2col">
      <div class="form-group">
        <label class="form-label">Happy Clients <span class="kb">stats_clients</span></label>
        <input class="form-control" type="text" name="stats_clients"
          value="{{ $settings['stats_clients'] ?? '70+' }}" placeholder="70+">
      </div>
      <div class="form-group">
        <label class="form-label">Projects Completed <span class="kb">stats_projects</span></label>
        <input class="form-control" type="text" name="stats_projects"
          value="{{ $settings['stats_projects'] ?? '65+' }}" placeholder="65+">
      </div>
      <div class="form-group">
        <label class="form-label">5-Star Reviews <span class="kb">stats_reviews</span></label>
        <input class="form-control" type="text" name="stats_reviews"
          value="{{ $settings['stats_reviews'] ?? '60+' }}" placeholder="60+">
      </div>
      <div class="form-group">
        <label class="form-label">Revenue Driven (M) <span class="kb">stats_revenue</span></label>
        <input class="form-control" type="text" name="stats_revenue"
          value="{{ $settings['stats_revenue'] ?? '46+' }}" placeholder="46+">
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     6. SOCIAL MEDIA
══════════════════════════════════════════ --}}
<div class="card full">
  <div class="card-head"><div class="card-title">🔗 Social Media Links</div></div>
  <div class="card-body">
    <div class="g3col">
      <div class="form-group">
        <label class="form-label">Instagram <span class="kb">social_instagram</span></label>
        <input class="form-control" type="url" name="social_instagram"
          value="{{ $settings['social_instagram'] ?? '' }}"
          placeholder="https://instagram.com/skartistic">
      </div>
      <div class="form-group">
        <label class="form-label">WhatsApp <span class="kb">social_whatsapp</span></label>
        <input class="form-control" type="url" name="social_whatsapp"
          value="{{ $settings['social_whatsapp'] ?? '' }}"
          placeholder="https://wa.me/923000000000">
      </div>
      <div class="form-group">
        <label class="form-label">
          LinkedIn <span class="kb">social_linkedin</span>
          <span style="font-size:10px;color:#ef4444;margin-left:4px">★ Added</span>
        </label>
        <input class="form-control" type="url" name="social_linkedin"
          value="{{ $settings['social_linkedin'] ?? '' }}"
          placeholder="https://linkedin.com/company/skartistic">
      </div>
      <div class="form-group">
        <label class="form-label">YouTube <span class="kb">social_youtube</span></label>
        <input class="form-control" type="url" name="social_youtube"
          value="{{ $settings['social_youtube'] ?? '' }}"
          placeholder="https://youtube.com/@skartistic">
      </div>
      <div class="form-group">
        <label class="form-label">Pinterest <span class="kb">social_pinterest</span></label>
        <input class="form-control" type="url" name="social_pinterest"
          value="{{ $settings['social_pinterest'] ?? '' }}"
          placeholder="https://pinterest.com/skartistic">
      </div>
      <div class="form-group">
        <label class="form-label">
          Twitter / X <span class="kb">social_twitter</span>
          <span style="font-size:10px;color:#ef4444;margin-left:4px">★ Added</span>
        </label>
        <input class="form-control" type="url" name="social_twitter"
          value="{{ $settings['social_twitter'] ?? '' }}"
          placeholder="https://x.com/skartistic">
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     7. SEO & META
══════════════════════════════════════════ --}}
<div class="card full">
  <div class="card-head"><div class="card-title">🔍 SEO & Meta Tags</div></div>
  <div class="card-body">
    <div class="g3col">
      <div class="form-group">
        <label class="form-label">Meta Title <span class="kb">meta_title</span></label>
        <input class="form-control" type="text" name="meta_title"
          value="{{ $settings['meta_title'] ?? '' }}"
          placeholder="SK Artistic — Beyond Ordinary">
        <small class="form-text">50–60 chars ideal. Falls back to Site Name if empty.</small>
      </div>
      <div class="form-group" style="grid-column:span 2">
        <label class="form-label">Meta Description <span class="kb">meta_description</span></label>
        <input class="form-control" type="text" name="meta_description"
          value="{{ $settings['meta_description'] ?? '' }}"
          placeholder="Full-cycle digital agency crafting websites, apps, and brands that dominate.">
        <small class="form-text">150–160 chars ideal. Falls back to Site Tagline if empty.</small>
      </div>
      <div class="form-group">
        <label class="form-label">OG / Social Share Image <span class="kb">og_image</span></label>
        <input class="form-control" type="file" name="og_image" accept="image/*">
        @if(!empty($settings['og_image']))
          <img src="{{ asset('storage/'.$settings['og_image']) }}"
            class="img-preview" alt="OG Image">
          <label class="rm-chk">
            <input type="checkbox" name="remove_og_image" value="1"> Remove image
          </label>
        @endif
        <small class="form-text">1200×630px. Shown when pages are shared on WhatsApp, Twitter, Facebook, etc.</small>
      </div>
    </div>
  </div>
</div>

</div>{{-- /.sg --}}

{{-- KEY MISMATCH MIGRATION NOTICE --}}
<div class="tip warn" style="margin-top:18px">
  <strong>⚠ Key name fixes applied:</strong>
  <code>hero_title_line1</code> → <code>hero_line1</code> &nbsp;|&nbsp;
  <code>hero_btn_text</code> → <code>hero_btn1</code>.
  If you had data saved under the old keys, just re-save this form once and everything will migrate automatically.
</div>

<div style="margin-top:16px;display:flex;gap:12px;align-items:center">
  <button type="submit" class="btn btn-primary" style="padding:13px 36px;font-size:15px">
    💾 Save All Settings
  </button>
  <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost">🌐 Preview Site</a>
  @if(session('success'))
    <span style="font-size:13px;color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:8px 16px;border-radius:6px">
      ✓ {{ session('success') }}
    </span>
  @endif
</div>

</form>

<script>
/* ── Tag pill input ── */
function syncHidden(wrap) {
  var hidden = document.getElementById(wrap.dataset.hidden);
  if (!hidden) return;
  hidden.value = Array.from(wrap.querySelectorAll('.tag-pill'))
    .map(function(p){ return p.dataset.val }).join(',');
}
function rmTag(btn) {
  var pill = btn.closest('.tag-pill');
  var wrap = pill.closest('.tag-wrap');
  pill.remove();
  syncHidden(wrap);
}
function addTag(wrap, val) {
  val = val.trim().replace(/,/g,'');
  if (!val) return;
  var existing = Array.from(wrap.querySelectorAll('.tag-pill')).map(function(p){return p.dataset.val});
  if (existing.indexOf(val) !== -1) return;
  var inp = wrap.querySelector('.tag-inp');
  var pill = document.createElement('span');
  pill.className = 'tag-pill';
  pill.dataset.val = val;
  pill.innerHTML = val + '<button type="button" onclick="rmTag(this)">×</button>';
  wrap.insertBefore(pill, inp);
  inp.value = '';
  syncHidden(wrap);
}
document.querySelectorAll('.tag-inp').forEach(function(inp) {
  var wrap = inp.closest('.tag-wrap');
  inp.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault(); addTag(wrap, inp.value);
    }
    if (e.key === 'Backspace' && inp.value === '') {
      var pills = wrap.querySelectorAll('.tag-pill');
      if (pills.length) { pills[pills.length-1].remove(); syncHidden(wrap); }
    }
  });
  inp.addEventListener('blur', function() { if (inp.value.trim()) addTag(wrap, inp.value); });
});

/* ── Image file preview ── */
document.querySelectorAll('input[type="file"][accept*="image"]').forEach(function(inp) {
  inp.addEventListener('change', function() {
    if (!inp.files || !inp.files[0]) return;
    var preview = inp.parentElement.querySelector('.img-preview');
    var reader = new FileReader();
    reader.onload = function(e) {
      if (preview) { preview.src = e.target.result; }
      else {
        var img = document.createElement('img');
        img.className = 'img-preview'; img.src = e.target.result;
        inp.parentElement.appendChild(img);
      }
    };
    reader.readAsDataURL(inp.files[0]);
  });
});
</script>

@endsection