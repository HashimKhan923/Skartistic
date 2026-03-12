@extends('frontend.layouts.app')
@section('title','About — '.($settings['site_name']??'SK Artistic'))

@section('content')
<style>

/* ---- Variables ---- */
.ab-page { --ab-purple:#7c3aed; --ab-muted:#6b7280; --ab-border:#e5e7eb; --ab-bg:#fff; --ab-bg2:#f9f9f9; --ab-text:#0f0f0f; }

/* ---- Reset for this page ---- */
.ab-page *, .ab-page *::before, .ab-page *::after { box-sizing:border-box; }
.ab-page { font-family:var(--font-b); color:var(--ab-text); background:#fff; }

/* NOTE: No custom container defined here.
   All sections use the site's existing .container class
   so content aligns with the navbar automatically. */

/* ---- Animations ---- */
@keyframes ab-fadeup { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
@keyframes ab-fadein { from{opacity:0} to{opacity:1} }
.ab-anim { opacity:0; }
.ab-anim.ab-visible { animation:ab-fadeup .65s ease forwards; }

/* ======================================================
   1. HERO
   ====================================================== */
.ab-hero { padding:80px 0 48px; }
.ab-hero-tag {
  color:var(--ab-purple);
  font-size:14px;
  font-weight:600;
  letter-spacing:.03em;
  margin-bottom:14px;
  margin-top:30px;
  display:block;
  opacity:0;
  animation:ab-fadeup .5s ease forwards .05s;
}
.ab-hero h1 {
  font-size:clamp(2.2rem,5vw,3.6rem);
  font-weight:800;
  line-height:1.1;
  color:var(--ab-text);
  margin:0 0 18px;
  opacity:0;
  animation:ab-fadeup .55s ease forwards .15s;
}
.ab-hero-sub {
  font-size:clamp(1rem,1.8vw,1.15rem);
  font-weight:500;
  color:var(--ab-muted);
  max-width:640px;
  line-height:1.65;
  opacity:0;
  animation:ab-fadeup .55s ease forwards .25s;
}

/* ======================================================
   2. MISSION + PHOTOS (two-column)
   ====================================================== */
.ab-mission-wrap { padding:0 0 80px; }
.ab-mission-grid {
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:72px;
  align-items:start;
}
@media(max-width:768px){ .ab-mission-grid{ grid-template-columns:1fr; gap:40px; } }

/* left: text */
.ab-mission-title { font-size:20px; font-weight:800; margin:0 0 18px; color:var(--ab-text); }
.ab-mission-para { font-size:15px; line-height:1.85; color:var(--ab-muted); margin:0 0 18px; }

/* stats */
.ab-nums-label {
  font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
  color:var(--ab-muted); margin:36px 0 0; padding-top:32px;
  border-top:1px solid var(--ab-border);
}
.ab-nums-grid {
  display:grid;
  grid-template-columns:1fr 1fr;
  border-top:1px solid var(--ab-border);
  margin-top:14px;
}
.ab-num-cell { padding:22px 0; border-bottom:1px solid var(--ab-border); }
.ab-num-cell:nth-child(odd)  { padding-right:24px; border-right:1px solid var(--ab-border); }
.ab-num-cell:nth-child(even) { padding-left:24px; }
.ab-num-big { font-size:2.8rem; font-weight:800; line-height:1; color:var(--ab-text); margin-bottom:5px; }
.ab-num-lbl { font-size:13px; color:var(--ab-muted); }

/* right: photo collage */
.ab-photos-col { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.ab-photo-item {
  border-radius:16px;
  overflow:hidden;
  background:#e9eaf0;
  aspect-ratio:4/3;
  opacity:0;
  transform:translateY(24px);
}
.ab-photo-item.ab-visible { animation:ab-fadeup .6s ease forwards; }
.ab-photo-item:first-child { grid-column:1 / -1; aspect-ratio:16/9; }
.ab-photo-item img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .55s ease; }
.ab-photo-item:hover img { transform:scale(1.04); }
.ab-photo-placeholder {
  width:100%; height:100%; min-height:160px;
  display:flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg,#f0f0f5,#e5e5ef);
  color:#bbb; font-size:2.5rem;
}

/* ======================================================
   3. MILESTONES / AWARDS
   ====================================================== */
.ab-milestones { padding:90px 0; text-align:center; }
.ab-sec-tag   { color:var(--ab-purple); font-size:13px; font-weight:600; letter-spacing:.04em; display:block; margin-bottom:10px; }
.ab-sec-h     { font-size:clamp(1.9rem,4vw,3rem); font-weight:800; color:var(--ab-text); margin:0 0 14px; line-height:1.1; }
.ab-sec-sub   { font-size:15px; color:var(--ab-muted); max-width:540px; margin:0 auto 52px; line-height:1.7; }

.ab-awards-card {
  background:#f4f4f5;
  border-radius:20px;
  padding:44px 36px;
  display:flex;
  justify-content:space-around;
  align-items:center;
  flex-wrap:wrap;
  gap:28px;
}
.ab-award-item {
  display:flex; flex-direction:column; align-items:center; gap:10px;
  flex:1; min-width:130px;
  opacity:0; transform:translateY(18px);
}
.ab-award-item.ab-visible { animation:ab-fadeup .55s ease forwards; }
.ab-award-trophy { font-size:2rem; }
.ab-award-brand  { font-size:15px; font-weight:800; color:#555; letter-spacing:-.01em; }
.ab-award-achiev { font-size:12px; font-weight:600; color:var(--ab-text); text-align:center; line-height:1.4; }
.ab-award-year   { font-size:12px; color:var(--ab-muted); }

/* ======================================================
   4. CORE VALUES
   ====================================================== */
.ab-values { padding:90px 0; }
.ab-values-hd { text-align:center; margin-bottom:52px; }
.ab-values-grid {
  display:grid;
  grid-template-columns:1fr 1fr;
  border-top:1px solid var(--ab-border);
  border-left:1px solid var(--ab-border);
}
@media(max-width:640px){ .ab-values-grid{ grid-template-columns:1fr; } }
.ab-val-item {
  display:flex; gap:18px;
  padding:30px 32px;
  border-bottom:1px solid var(--ab-border);
  border-right:1px solid var(--ab-border);
  opacity:0; transform:translateY(18px);
  transition:opacity .45s ease, transform .45s ease;
}
.ab-val-item.ab-visible { opacity:1; transform:translateY(0); }
.ab-val-icon { font-size:20px; flex-shrink:0; margin-top:1px; }
.ab-val-title { font-size:14px; font-weight:700; color:var(--ab-text); margin:0 0 7px; }
.ab-val-desc  { font-size:13px; color:var(--ab-muted); line-height:1.75; margin:0; }

/* ======================================================
   5. FOUNDERS
   ====================================================== */
.ab-founders { padding:80px 0; }
.ab-founder-row {
  display:grid;
  grid-template-columns:280px 1fr;
  gap:60px;
  align-items:center;
  padding:56px 0;
  border-bottom:1px solid var(--ab-border);
  opacity:0; transform:translateY(28px);
  transition:opacity .6s ease, transform .6s ease;
}
.ab-founder-row.ab-reverse { grid-template-columns:1fr 280px; }
.ab-founder-row.ab-reverse .ab-founder-photo { order:2; }
.ab-founder-row.ab-reverse .ab-founder-text  { order:1; }
.ab-founder-row.ab-visible { opacity:1; transform:translateY(0); }
@media(max-width:760px){
  .ab-founder-row, .ab-founder-row.ab-reverse { grid-template-columns:1fr; }
  .ab-founder-row.ab-reverse .ab-founder-photo,
  .ab-founder-row.ab-reverse .ab-founder-text { order:unset; }
}
.ab-founder-photo { border-radius:20px; overflow:hidden; background:#f0f0f5; box-shadow:0 4px 28px rgba(0,0,0,.09); }
.ab-founder-photo img  { width:100%; height:300px; object-fit:cover; display:block; }
.ab-founder-photo-ph   { width:100%; height:300px; display:flex; align-items:center; justify-content:center; font-size:72px; color:#ccc; }
.ab-founder-name { font-size:clamp(1.5rem,3vw,2rem); font-weight:800; margin:0 0 14px; }
.ab-founder-bio  { font-size:14px; color:var(--ab-muted); line-height:1.85; margin:0 0 24px; }
.ab-founder-meta { display:flex; gap:36px; flex-wrap:wrap; }
.ab-founder-meta-grp { display:flex; flex-direction:column; gap:3px; }
.ab-founder-meta-lbl { font-size:12px; color:var(--ab-muted); }
.ab-founder-meta-val { font-size:14px; font-weight:700; }
.ab-founder-links { display:flex; gap:8px; margin-top:16px; }
.ab-founder-link {
  width:34px; height:34px; border:1px solid var(--ab-border); border-radius:7px;
  display:flex; align-items:center; justify-content:center;
  color:var(--ab-muted); text-decoration:none; transition:all .2s;
}
.ab-founder-link:hover { border-color:var(--ab-purple); color:var(--ab-purple); }
.ab-founder-link svg { width:15px; height:15px; }

/* ======================================================
   6. TEAM
   ====================================================== */
.ab-team-wrap { background:#f9f9f9; padding:80px 0; }
.ab-team-hd {
  display:flex; justify-content:space-between; align-items:flex-end;
  margin-bottom:40px; flex-wrap:wrap; gap:14px;
}
.ab-team-hd h2 { font-size:2.2rem; font-weight:800; margin:0; }
.ab-team-ghost {
  font-size:14px; font-weight:600; color:var(--ab-text);
  border:1px solid var(--ab-border); border-radius:8px;
  padding:9px 18px; text-decoration:none; transition:all .2s;
}
.ab-team-ghost:hover { border-color:var(--ab-purple); color:var(--ab-purple); }
.ab-team-grid {
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:18px;
}
@media(max-width:900px){ .ab-team-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:480px){ .ab-team-grid{ grid-template-columns:1fr 1fr; } }
.ab-team-card {
  background:#fff; border-radius:14px; overflow:hidden;
  box-shadow:0 1px 4px rgba(0,0,0,.06);
  transition:transform .3s ease, box-shadow .3s ease;
  opacity:0; transform:translateY(18px);
}
.ab-team-card.ab-visible { animation:ab-fadeup .5s ease forwards; }
.ab-team-card:hover { transform:translateY(-4px); box-shadow:0 14px 36px rgba(0,0,0,.13); }
.ab-team-thumb { aspect-ratio:1; background:#e9eaf0; overflow:hidden; }
.ab-team-thumb img { width:100%; height:100%; object-fit:cover; transition:transform .5s ease; }
.ab-team-card:hover .ab-team-thumb img { transform:scale(1.06); }
.ab-team-ph  { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#ccc; }
.ab-team-body{ padding:14px 16px; }
.ab-team-name{ font-weight:700; font-size:14px; color:var(--ab-text); }
.ab-team-pos { font-size:12px; color:var(--ab-muted); margin-top:2px; }
.ab-team-socs{ display:flex; gap:6px; margin-top:10px; }
.ab-team-soc {
  font-size:11px; font-weight:700; color:var(--ab-muted);
  border:1px solid var(--ab-border); border-radius:5px;
  padding:3px 8px; text-decoration:none; transition:all .2s;
}
.ab-team-soc:hover { color:var(--ab-purple); border-color:var(--ab-purple); }

/* ======================================================
   7. FAQ
   ====================================================== */
.ab-faq-wrap { padding:90px 0; text-align:center; }
.ab-faq-inner { max-width:740px; margin:0 auto; }
.ab-faq-list  { margin-top:44px; text-align:left; }
.ab-faq-item  {
  background:#f4f4f5; border-radius:11px; margin-bottom:7px; overflow:hidden;
  opacity:0; transform:translateY(14px);
  transition:opacity .4s ease, transform .4s ease;
}
.ab-faq-item.ab-visible { opacity:1; transform:translateY(0); }
.ab-faq-btn {
  width:100%; background:none; border:none; padding:20px 22px;
  display:flex; justify-content:space-between; align-items:center;
  font-size:14px; font-weight:600; color:var(--ab-text); cursor:pointer; text-align:left; gap:14px;
}
.ab-faq-btn:hover { color:var(--ab-purple); }
.ab-faq-chevron { flex-shrink:0; width:18px; height:18px; transition:transform .3s ease; color:var(--ab-muted); }
.ab-faq-item.ab-open .ab-faq-chevron { transform:rotate(180deg); }
.ab-faq-body {
  max-height:0; overflow:hidden; transition:max-height .38s ease, padding .3s ease;
  padding:0 22px; font-size:14px; color:var(--ab-muted); line-height:1.8;
}
.ab-faq-item.ab-open .ab-faq-body { max-height:280px; padding:0 22px 20px; }

/* ======================================================
   8. CTA
   ====================================================== */
.ab-cta-wrap { padding:48px 0 90px; }
.ab-cta-box {
  background:#0f0f0f; border-radius:22px; padding:68px 48px;
  text-align:center; position:relative; overflow:hidden;
}
.ab-cta-box::before {
  content:''; position:absolute; top:-80px; left:-80px;
  width:340px; height:340px;
  background:radial-gradient(circle,rgba(124,58,237,.28) 0%,transparent 65%);
  pointer-events:none;
}
.ab-cta-box::after {
  content:''; position:absolute; bottom:-80px; right:-80px;
  width:300px; height:300px;
  background:radial-gradient(circle,rgba(167,139,250,.15) 0%,transparent 65%);
  pointer-events:none;
}
.ab-cta-box h2 {
  font-size:clamp(1.9rem,4vw,2.9rem); font-weight:800; color:#fff; margin:0 0 12px; position:relative;
}
.ab-cta-box h2 span {
  background:linear-gradient(135deg,#c4b5fd,#7c3aed);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.ab-cta-box p { color:#9ca3af; font-size:15px; margin:0 0 30px; position:relative; }
.ab-cta-btn {
  display:inline-flex; align-items:center; gap:8px;
  background:#fff; color:#0f0f0f;
  padding:13px 26px; border-radius:9px;
  font-weight:700; font-size:14px; text-decoration:none;
  transition:transform .2s ease, box-shadow .2s ease;
  position:relative;
}
.ab-cta-btn:hover { transform:translateY(-2px); box-shadow:0 14px 36px rgba(0,0,0,.28); }
.ab-cta-btn svg { width:15px; height:15px; }
</style>

<div class="ab-page">

{{-- ============================================================
     1. HERO
     ============================================================ --}}
<section class="ab-hero">
  <div class="container">
    <span class="ab-hero-tag">{{ $about->hero_tag ?? 'About us' }}</span>
    <h1>{{ $about->hero_title ?? "Building Tomorrow's Digital Frontier" }}</h1>
    <p class="ab-hero-sub">{{ $about->hero_subtitle ?? 'We are a team of developers who are passionate about building beautiful and functional websites and mobile apps.' }}</p>
  </div>
</section>

{{-- ============================================================
     2. MISSION + PHOTOS
     ============================================================ --}}
<section class="ab-mission-wrap">
  <div class="container">
    <div class="ab-mission-grid">

      {{-- LEFT: text + stats --}}
      <div>
        <h2 class="ab-mission-title ab-anim">{{ $about->mission_title ?? 'Our mission' }}</h2>
        <p class="ab-mission-para ab-anim">{{ $about->mission_text_1 ?? "At SK Artistic, we're committed to revolutionizing the way businesses harness technology. Our mission is to arm our clients with a decisive advantage over their competition through innovative, custom-built software solutions that break the mold. We stop at nothing to deliver the tools and insights you need to lead your market." }}</p>
        <p class="ab-mission-para ab-anim">{{ $about->mission_text_2 ?? "We're fanatical about our craft — investing the time to decode every nuance of your business so we understand your challenges better than you do. We stand shoulder-to-shoulder with you, because in today's digital arena, every breakthrough is a shared secret. In our journey, we've built an unbreakable bond with our clients; when one of us triumphs, we all rise." }}</p>

        <div class="ab-nums-label ab-anim">{{ $about->stats_label ?? 'THE NUMBERS' }}</div>
        <div class="ab-nums-grid">
          <div class="ab-num-cell ab-anim">
            <div class="ab-num-big" data-count="{{ preg_replace('/\D/','',$about->stat_clients_num??'70') }}">{{ $about->stat_clients_num ?? '70+' }}</div>
            <div class="ab-num-lbl">{{ $about->stat_clients_label ?? 'Satisfied Clients' }}</div>
          </div>
          <div class="ab-num-cell ab-anim" style="transition-delay:.08s">
            <div class="ab-num-big" data-count="{{ preg_replace('/\D/','',$about->stat_projects_num??'65') }}">{{ $about->stat_projects_num ?? '65+' }}</div>
            <div class="ab-num-lbl">{{ $about->stat_projects_label ?? 'Projects' }}</div>
          </div>
          <div class="ab-num-cell ab-anim" style="transition-delay:.14s">
            <div class="ab-num-big">{{ $about->stat_satisfaction_num ?? '99.5%' }}</div>
            <div class="ab-num-lbl">{{ $about->stat_satisfaction_label ?? 'Satisfaction Rate' }}</div>
          </div>
          <div class="ab-num-cell ab-anim" style="transition-delay:.2s">
            <div class="ab-num-big" data-count="{{ preg_replace('/\D/','',$about->stat_experience_num??'5') }}">{{ $about->stat_experience_num ?? '5+' }}</div>
            <div class="ab-num-lbl">{{ $about->stat_experience_label ?? 'Years of Experience' }}</div>
          </div>
        </div>
      </div>

      {{-- RIGHT: photos collage --}}
      <div class="ab-photos-col">
        {{-- Photo 1 (wide, spans full width of right column) --}}
        <div class="ab-photo-item ab-anim">
          @if(!empty($about->photo_1))
            <img src="{{ asset('storage/'.$about->photo_1) }}" alt="Team">
          @else
            <div class="ab-photo-placeholder">📸</div>
          @endif
        </div>
        {{-- Photo 2 --}}
        <div class="ab-photo-item ab-anim" style="animation-delay:.12s">
          @if(!empty($about->photo_2))
            <img src="{{ asset('storage/'.$about->photo_2) }}" alt="Team">
          @else
            <div class="ab-photo-placeholder">🤝</div>
          @endif
        </div>
        {{-- Photo 3 --}}
        <div class="ab-photo-item ab-anim" style="animation-delay:.24s">
          @if(!empty($about->photo_3))
            <img src="{{ asset('storage/'.$about->photo_3) }}" alt="Team">
          @else
            <div class="ab-photo-placeholder">🚀</div>
          @endif
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ============================================================
     3. MILESTONES / AWARDS
     ============================================================ --}}
<section class="ab-milestones" style="background:#fff">
  <div class="container">
    <span class="ab-sec-tag ab-anim">{{ $about->milestones_tag ?? 'Milestones That Matter' }}</span>
    <h2 class="ab-sec-h ab-anim">{{ $about->milestones_title ?? 'Our Journey of Impact' }}</h2>
    <p class="ab-sec-sub ab-anim">{{ $about->milestones_subtitle ?? "From startups to enterprises, we've empowered businesses through innovative software solutions and transformative results." }}</p>

    <div class="ab-awards-card">
      @forelse($awards ?? [] as $award)
        <div class="ab-award-item ab-anim">
          <div class="ab-award-trophy">🏆</div>
          @if(!empty($award->logo_path))
            <img src="{{ asset('storage/'.$award->logo_path) }}" alt="{{ $award->platform }}" style="height:26px;filter:grayscale(1);opacity:.55">
          @else
            <div class="ab-award-brand">{{ $award->platform }}</div>
          @endif
          <div class="ab-award-achiev">{{ $award->achievement }}</div>
          <div class="ab-award-year">{{ $award->year }}</div>
        </div>
      @empty
        @foreach([
          ['Clutch','Making Waves on the World Stage','2024'],
          ['techreviewer','Top Rising Company','2025'],
          ['DESIGNRUSH','Where Vision Meets Code','2024'],
          ['TopDevelopers','Ranked Among the Top','2025'],
          ['GoodFirms','Enduring Excellence','2024'],
        ] as $i => $a)
        <div class="ab-award-item ab-anim" style="animation-delay:{{ $i * .1 }}s">
          <div class="ab-award-trophy">🏆</div>
          <div class="ab-award-brand">{{ $a[0] }}</div>
          <div class="ab-award-achiev">{{ $a[1] }}</div>
          <div class="ab-award-year">{{ $a[2] }}</div>
        </div>
        @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ============================================================
     4. CORE VALUES
     ============================================================ --}}
<section class="ab-values">
  <div class="container">
    <div class="ab-values-hd">
      <span class="ab-sec-tag ab-anim">{{ $about->values_tag ?? 'What Drives Us' }}</span>
      <h2 class="ab-sec-h ab-anim">{{ $about->values_title ?? 'Our Core Values' }}</h2>
      <p class="ab-sec-sub ab-anim">{{ $about->values_subtitle ?? 'At the heart of SK Artistic lies a commitment to innovation, integrity, collaboration, and client success — principles that guide every line of code we write.' }}</p>
    </div>

    <div class="ab-values-grid">
      @forelse($values ?? [] as $val)
        <div class="ab-val-item ab-anim">
          <div class="ab-val-icon">{!! $val->icon ?? '🎯' !!}</div>
          <div>
            <p class="ab-val-title"><strong>{{ $val->title }}.</strong></p>
            <p class="ab-val-desc">{{ $val->description }}</p>
          </div>
        </div>
      @empty
        @foreach([
          ['🚀','Be world-class','We hold ourselves to the highest standards in every project — delivering exceptional design, architecture, and performance to help our clients lead in their industries.'],
          ['🤝','Take responsibility','We own our work, honor our commitments, and proactively solve problems — ensuring reliability, trust, and excellence at every stage of development.'],
          ['👥','Be supportive','We thrive as a team. By uplifting each other and our clients, we create an environment where collaboration leads to innovation.'],
          ['📖','Always learning','Technology evolves fast — and so do we. Continuous learning and curiosity fuel our growth and keep us ahead of the curve.'],
          ['💡','Share everything you know','We believe in open knowledge. By sharing insights, we empower our team, uplift our partners, and strengthen the software community.'],
          ['☀️','Enjoy downtime','Great ideas need fresh minds. We respect balance and believe rest is essential for creativity, clarity, and sustainable excellence.'],
        ] as $i => $v)
        <div class="ab-val-item ab-anim" style="transition-delay:{{ $i * .07 }}s">
          <div class="ab-val-icon">{{ $v[0] }}</div>
          <div>
            <p class="ab-val-title"><strong>{{ $v[1] }}.</strong></p>
            <p class="ab-val-desc">{{ $v[2] }}</p>
          </div>
        </div>
        @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ============================================================
     5. FOUNDERS (alternating left / right)
     ============================================================ --}}
@if(isset($founders) && $founders->count())
<section class="ab-founders">
  <div class="container">
    @foreach($founders as $i => $founder)
    <div class="ab-founder-row ab-anim {{ $i % 2 !== 0 ? 'ab-reverse' : '' }}">
      <div class="ab-founder-photo">
        @if($founder->photo)
          <img src="{{ asset('storage/'.$founder->photo) }}" alt="{{ $founder->name }}">
        @else
          <div class="ab-founder-photo-ph">👤</div>
        @endif
      </div>
      <div class="ab-founder-text">
        <h3 class="ab-founder-name">Hi, I'm {{ $founder->name }}!</h3>
        <p class="ab-founder-bio">{{ $founder->bio }}</p>
        <div class="ab-founder-meta">
          @if(!empty($founder->company))
          <div class="ab-founder-meta-grp">
            <span class="ab-founder-meta-lbl">Co-founder of:</span>
            <span class="ab-founder-meta-val">{{ $founder->company }}</span>
          </div>
          @endif
          <div class="ab-founder-meta-grp">
            <span class="ab-founder-meta-lbl">Find me on:</span>
            <div class="ab-founder-links">
              @if(!empty($founder->website))
              <a href="{{ $founder->website }}" class="ab-founder-link" target="_blank" title="Website">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20M12 2a15.3 15.3 0 0 0 0 20"/></svg>
              </a>
              @endif
              @if(!empty($founder->linkedin))
              <a href="{{ $founder->linkedin }}" class="ab-founder-link" target="_blank" title="LinkedIn">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif

{{-- ============================================================
     6. TEAM (only if data exists)
     ============================================================ --}}
@if(isset($team) && $team->count())
<section class="ab-team-wrap">
  <div class="container">
    <div class="ab-team-hd">
      <div>
        <span class="ab-sec-tag" style="text-align:left">The Crew</span>
        <h2>Meet the Team.</h2>
      </div>
      <a href="{{ route('team') }}" class="ab-team-ghost">Full Team →</a>
    </div>
    <div class="ab-team-grid">
      @foreach($team->take(4) as $idx => $member)
      <div class="ab-team-card ab-anim" style="animation-delay:{{ $idx * .1 }}s">
        <div class="ab-team-thumb">
          @if($member->photo)
            <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}">
          @else
            <div class="ab-team-ph">👤</div>
          @endif
        </div>
        <div class="ab-team-body">
          <div class="ab-team-name">{{ $member->name }}</div>
          <div class="ab-team-pos">{{ $member->position }}</div>
          @if($member->linkedin || ($member->instagram ?? false))
          <div class="ab-team-socs">
            @if($member->linkedin)<a href="{{ $member->linkedin }}" class="ab-team-soc" target="_blank">in</a>@endif
            @if(!empty($member->instagram))<a href="{{ $member->instagram }}" class="ab-team-soc" target="_blank">ig</a>@endif
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ============================================================
     7. FAQ
     ============================================================ --}}
@if(isset($faqs) && $faqs->count())
<section class="ab-faq-wrap">
  <div class="container">
    <div class="ab-faq-inner">
      <span class="ab-sec-tag ab-anim">{{ $about->faq_tag ?? 'FAQ' }}</span>
      <h2 class="ab-sec-h ab-anim" style="font-size:clamp(1.9rem,5vw,3rem)">{{ $about->faq_title ?? 'Frequently Asked Questions About SK Artistic' }}</h2>
      <p class="ab-sec-sub ab-anim">{{ $about->faq_subtitle ?? 'Learn more about our agency, our services, and how we can help bring your digital vision to life.' }}</p>

      <div class="ab-faq-list">
        @foreach($faqs as $faq)
        <div class="ab-faq-item ab-anim">
          <button class="ab-faq-btn" onclick="abFaqToggle(this)">
            {{ $faq->question }}
            <svg class="ab-faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <div class="ab-faq-body">{{ $faq->answer }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif

{{-- ============================================================
     8. CTA
     ============================================================ --}}
<section class="ab-cta-wrap">
  <div class="container">
    <div class="ab-cta-box ab-anim">
      <h2>READY TO START<br><span>YOUR PROJECT?</span></h2>
      <p>Let's build something extraordinary together.</p>
      <a href="{{ route('contact') }}" class="ab-cta-btn">
        Get In Touch
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>

</div>{{-- .ab-page --}}

<script>
(function(){
  /* ---- Scroll-reveal via IntersectionObserver ---- */
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){
        e.target.classList.add('ab-visible');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.ab-anim').forEach(function(el){ io.observe(el); });

  /* ---- Counter animation ---- */
  var counterDone = false;
  var numObs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting && !counterDone){
        counterDone = true;
        e.target.querySelectorAll('[data-count]').forEach(function(el){
          var target = parseInt(el.dataset.count);
          var suffix = el.textContent.replace(/[\d]/g,'').trim();
          var current = 0;
          var step = Math.max(1, Math.ceil(target / 55));
          var t = setInterval(function(){
            current = Math.min(current + step, target);
            el.textContent = current + suffix;
            if(current >= target) clearInterval(t);
          }, 22);
        });
        numObs.unobserve(e.target);
      }
    });
  }, { threshold: 0.3 });

  var numGrid = document.querySelector('.ab-nums-grid');
  if(numGrid) numObs.observe(numGrid);

  /* ---- FAQ accordion ---- */
  window.abFaqToggle = function(btn){
    var item = btn.closest('.ab-faq-item');
    var isOpen = item.classList.contains('ab-open');
    document.querySelectorAll('.ab-faq-item.ab-open').forEach(function(i){ i.classList.remove('ab-open'); });
    if(!isOpen) item.classList.add('ab-open');
  };
})();
</script>
@endsection