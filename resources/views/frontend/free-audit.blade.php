@extends('frontend.layouts.app')
@section('title','Free Website Audit — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Free Audit</span></div>
    <h1>Free <span class="g">audit.</span></h1>
    <p>We'll review your site and tell you exactly what's holding it back — for free, no strings.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div class="reveal" style="margin-bottom:36px"><div class="s-tag">What's Included</div><h2 class="s-h">Your digital presence, <span class="g2">reviewed.</span></h2></div>
    <div class="split">
      <div class="reveal-l">
<p style="font-size:16px;color:var(--muted);line-height:1.85;font-weight:400;margin-bottom:48px">Not an automated report — a real human reviews your site and gives you actionable, personalised feedback within 48 hours.</p>
        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:48px">
          <div class="audit-item"><div class="audit-item-icon">🎨</div><div><h4>Design & UX Review</h4><p>Is your site compelling? Does it guide users toward conversion effectively?</p></div></div>
          <div class="audit-item"><div class="audit-item-icon">⚡</div><div><h4>Performance Analysis</h4><p>Load speed, Core Web Vitals, and technical health checks that matter for rankings.</p></div></div>
          <div class="audit-item"><div class="audit-item-icon">🔍</div><div><h4>SEO Overview</h4><p>How visible are you on Google? On-page SEO assessment with real recommendations.</p></div></div>
          <div class="audit-item"><div class="audit-item-icon">📱</div><div><h4>Mobile Experience</h4><p>How your site looks and performs on every screen size and device.</p></div></div>
          <div class="audit-item"><div class="audit-item-icon">🎯</div><div><h4>Conversion Audit</h4><p>Where are you losing visitors? CTA clarity, form UX, and trust signal review.</p></div></div>
        </div>
        <div style="background:rgba(var(--accent-rgb),.04);border:1px solid rgba(var(--accent-rgb),.15);padding:20px 24px;display:flex;gap:14px;align-items:flex-start">
          <span style="color:var(--cyan);font-size:1.2rem;flex-shrink:0">✦</span>
          <p style="font-size:14px;color:var(--muted);line-height:1.7;font-weight:400"><strong style="color:var(--text)">100% free, zero obligation.</strong> Personalised report delivered within 48 hours. No sales pressure, no strings attached.</p>
        </div>
      </div>
      <div class="reveal-r">
        <div class="form-card">
          @if(session('success'))<div class="flash-success"><span class="icon">✓</span><span class="msg">{{ session('success') }}</span></div>@endif
          <div style="font-family:var(--font-d);font-size:1.8rem;letter-spacing:1px;color:var(--text);margin-bottom:6px">REQUEST YOUR <span style="color:var(--accent)">FREE AUDIT</span></div>
          <p style="font-size:13.5px;color:var(--dim);margin-bottom:32px;font-weight:400">Fill in your details — we'll respond within 48 hours.</p>
          <form action="{{ route('audit.submit') }}" method="POST" class="m-form" data-busy="Submitting…">
            @csrf
            <div class="hp-field" aria-hidden="true"><label>Leave this empty<input type="text" name="company_site" tabindex="-1" autocomplete="off"></label></div>
            <div class="form-2">
              <div class="field-wrap"><label for="a-name">Your Name</label><input id="a-name" type="text" name="name" required autocomplete="name" class="field-input" placeholder="John Doe" value="{{ old('name') }}">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
              <div class="field-wrap"><label for="a-email">Your Email</label><input id="a-email" type="email" name="email" required autocomplete="email" class="field-input" placeholder="you@email.com" value="{{ old('email') }}">@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="form-2">
              <div class="field-wrap"><label for="a-phone">Phone (Optional)</label><input id="a-phone" type="tel" name="phone" autocomplete="tel" class="field-input" placeholder="+1 234 567 890" value="{{ old('phone') }}"></div>
              <div class="field-wrap"><label for="a-url">Website URL</label><input id="a-url" type="url" name="website_url" class="field-input" placeholder="https://yourwebsite.com" value="{{ old('website_url') }}">@error('website_url')<span class="field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="field-wrap">
              <label for="a-type">Business Type</label>
              <select id="a-type" name="business_type" class="field-input">
                <option value="">Select a business type</option>
                <option value="E-commerce" {{ old('business_type')=='E-commerce'?'selected':'' }}>E-commerce</option>
                <option value="Service Business" {{ old('business_type')=='Service Business'?'selected':'' }}>Service Business</option>
                <option value="SaaS / Startup" {{ old('business_type')=='SaaS / Startup'?'selected':'' }}>SaaS / Startup</option>
                <option value="Portfolio / Blog" {{ old('business_type')=='Portfolio / Blog'?'selected':'' }}>Portfolio / Blog</option>
                <option value="Other" {{ old('business_type')=='Other'?'selected':'' }}>Other</option>
              </select>
            </div>
            <div class="field-wrap">
              <label for="a-budget">Budget Range</label>
              <select id="a-budget" name="budget_range" class="field-input">
                <option value="">Select a budget</option>
                <option value="<$1k" {{ old('budget_range')=='<$1k'?'selected':'' }}>Under $1,000</option>
                <option value="$1k-$3k" {{ old('budget_range')=='$1k-$3k'?'selected':'' }}>$1,000 – $3,000</option>
                <option value="$3k-$10k" {{ old('budget_range')=='$3k-$10k'?'selected':'' }}>$3,000 – $10,000</option>
                <option value="$10k+" {{ old('budget_range')=='$10k+'?'selected':'' }}>$10,000+</option>
                <option value="discuss" {{ old('budget_range')=='discuss'?'selected':'' }}>Let's discuss</option>
              </select>
            </div>
            @if(isset($services_nav) && $services_nav->count())
            <div class="field-wrap">
              <label>Services You're Interested In</label>
              <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px">
                @foreach($services_nav as $s)
                <label style="display:flex;align-items:center;gap:7px;font-size:13px;color:var(--muted);font-weight:400;border:1.5px solid var(--rim2);border-radius:100px;padding:8px 16px;cursor:pointer">
                  <input type="checkbox" name="services_needed[]" value="{{ $s->title }}" {{ in_array($s->title, old('services_needed', [])) ? 'checked' : '' }} style="accent-color:var(--brand)">
                  {{ $s->title }}
                </label>
                @endforeach
              </div>
            </div>
            @endif
            <div class="field-wrap"><label for="a-goals">Goals & Focus Areas (Optional)</label><textarea id="a-goals" maxlength="5000" name="goals" class="field-input" rows="3" placeholder="What are you hoping to achieve? Any specific concerns?">{{ old('goals') }}</textarea></div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;display:flex"><span>Get My Free Audit</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection