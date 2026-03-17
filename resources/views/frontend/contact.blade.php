@extends('frontend.layouts.app')
@section('title','Contact — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Contact</span></div>
    <h1>LET'S <span class="g">TALK.</span></h1>
    <p>Tell us about your project — we'll get back within 24 hours.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start">
      <div class="reveal-l">
        <div class="s-tag">Get In Touch</div>
        <h2 class="s-h" style="font-size:clamp(2.5rem,4vw,4.5rem)">WE'D LOVE<br>TO HEAR <span class="g2">FROM YOU.</span></h2>
        <p style="font-size:16px;color:var(--muted);line-height:1.85;font-weight:300;margin-bottom:48px">Whether you're starting a new project, looking to scale, or just exploring — let's have a real conversation.</p>
        <div class="info-row" style="margin-bottom:48px">
          @if(!empty($settings['site_email']))
          <a href="mailto:{{ $settings['site_email'] }}" class="info-card">
            <div class="info-icon">📧</div>
            <div><div class="info-label">Email</div><div class="info-value">{{ $settings['site_email'] }}</div></div>
            <span class="info-arr">→</span>
          </a>
          @endif
          @if(!empty($settings['site_phone']))
          <a href="tel:{{ $settings['site_phone'] }}" class="info-card">
            <div class="info-icon">📞</div>
            <div><div class="info-label">Phone / WhatsApp</div><div class="info-value">{{ $settings['site_phone'] }}</div></div>
            <span class="info-arr">→</span>
          </a>
          @endif
          @if(!empty($settings['site_address']))
          <div class="info-card">
            <div class="info-icon">📍</div>
            <div><div class="info-label">Location</div><div class="info-value">{{ $settings['site_address'] }}</div></div>
          </div>
          @endif
        </div>
        <div class="footer-socials">
          @if(!empty($settings['social_instagram']))<a href="{{ $settings['social_instagram'] }}" class="f-soc" target="_blank">Instagram</a>@endif
          @if(!empty($settings['social_whatsapp']))<a href="{{ $settings['social_whatsapp'] }}" class="f-soc" target="_blank">WhatsApp</a>@endif
          @if(!empty($settings['social_youtube']))<a href="{{ $settings['social_youtube'] }}" class="f-soc" target="_blank">YouTube</a>@endif
          @if(!empty($settings['social_linkedin']))<a href="{{ $settings['social_linkedin'] }}" class="f-soc" target="_blank">LinkedIn</a>@endif
        </div>
      </div>
      <div class="reveal-r">
        <div style="background:var(--panel);border:1px solid var(--rim);border-top:2px solid var(--cyan);padding:48px">
          @if(session('success'))<div class="flash-success"><span class="icon">✓</span><span class="msg">{{ session('success') }}</span></div>@endif
          <form action="{{ route('contact.submit') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
              <div class="field-wrap"><label>Your Name</label><input type="text" name="name" class="field-input" placeholder="John Doe" value="{{ old('name') }}">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
              <div class="field-wrap"><label>Your Email</label><input type="email" name="email" class="field-input" placeholder="you@email.com" value="{{ old('email') }}">@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="field-wrap"><label>Phone (Optional)</label><input type="tel" name="phone" class="field-input" placeholder="+1 234 567 890" value="{{ old('phone') }}"></div>
            <div class="field-wrap">
              <label>Service Needed</label>
              <select name="service" class="field-input">
                <option value="">Select a Service</option>
                @foreach($services as $s)<option value="{{ $s->title }}" {{ old('service')==$s->title?'selected':'' }}>{{ $s->title }}</option>@endforeach
                <option value="Other">Other / Not sure yet</option>
              </select>
            </div>
            <div class="field-wrap">
              <label>Budget Range</label>
              <select name="budget" class="field-input">
                <option value="">Select a Budget</option>
                <option value="<$1k">Under $1,000</option>
                <option value="$1k-$3k">$1,000 – $3,000</option>
                <option value="$3k-$10k">$3,000 – $10,000</option>
                <option value="$10k+">$10,000+</option>
                <option value="discuss">Let's discuss</option>
              </select>
            </div>
            <div class="field-wrap"><label>Message</label><textarea name="message" class="field-input" rows="5" placeholder="Tell us about your project...">{{ old('message') }}</textarea>@error('message')<span class="field-error">{{ $message }}</span>@enderror</div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;display:flex"><span>Send Message</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection