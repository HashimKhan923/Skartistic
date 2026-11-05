@extends('frontend.layouts.app')
@section('title','Contact — '.($settings['site_name']??'SK Artistic'))
@section('content')
<div class="page-hero">
  <div class="page-hero-grid"></div><div class="page-hero-orb"></div>
  <div class="container">
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><span>Contact</span></div>
    <h1>Let's <span class="g">talk.</span></h1>
    <p>Tell us about your project — we'll get back within 24 hours.</p>
  </div>
</div>
<section class="section">
  <div class="container">
    <div class="reveal" style="margin-bottom:36px"><div class="s-tag">Get In Touch</div><h2 class="s-h">We'd love to hear <span class="g2">from you.</span></h2></div>
    <div class="split">
      <div class="reveal-l">
<p style="font-size:16px;color:var(--muted);line-height:1.85;font-weight:400;margin-bottom:48px">Whether you're starting a new project, looking to scale, or just exploring — let's have a real conversation.</p>
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
        @include('frontend.partials.social-links', ['links' => [
          'instagram' => $settings['social_instagram'] ?? null,
          'whatsapp'  => $settings['social_whatsapp'] ?? null,
          'youtube'   => $settings['social_youtube'] ?? null,
          'linkedin'  => $settings['social_linkedin'] ?? null,
          'twitter'   => $settings['social_twitter'] ?? null,
        ], 'variant' => 'on-light'])
      </div>
      <div class="reveal-r">
        <div class="form-card">
          @if(session('success'))<div class="flash-success"><span class="icon">✓</span><span class="msg">{{ session('success') }}</span></div>@endif
          <form action="{{ route('contact.submit') }}" method="POST" class="m-form" data-busy="Sending…">
            @csrf
            <div class="hp-field" aria-hidden="true"><label>Leave this empty<input type="text" name="company_site" tabindex="-1" autocomplete="off"></label></div>
            <div class="form-2">
              <div class="field-wrap"><label for="c-name">Your Name</label><input id="c-name" type="text" name="name" required autocomplete="name" class="field-input" placeholder="John Doe" value="{{ old('name') }}">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
              <div class="field-wrap"><label for="c-email">Your Email</label><input id="c-email" type="email" name="email" required autocomplete="email" class="field-input" placeholder="you@email.com" value="{{ old('email') }}">@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="field-wrap"><label for="c-phone">Phone (Optional)</label><input id="c-phone" type="tel" name="phone" autocomplete="tel" class="field-input" placeholder="+1 234 567 890" value="{{ old('phone') }}"></div>
            <div class="field-wrap">
              <label for="c-service">Service Needed</label>
              <select id="c-service" name="service" class="field-input">
                <option value="">Select a Service</option>
                @foreach($services as $s)<option value="{{ $s->title }}" {{ old('service')==$s->title?'selected':'' }}>{{ $s->title }}</option>@endforeach
                <option value="Other">Other / Not sure yet</option>
              </select>
            </div>
            <div class="field-wrap">
              <label for="c-budget">Budget Range</label>
              <select id="c-budget" name="budget" class="field-input">
                <option value="">Select a Budget</option>
                <option value="<$1k" {{ old('budget')=='<$1k'?'selected':'' }}>Under $1,000</option>
                <option value="$1k-$3k" {{ old('budget')=='$1k-$3k'?'selected':'' }}>$1,000 – $3,000</option>
                <option value="$3k-$10k" {{ old('budget')=='$3k-$10k'?'selected':'' }}>$3,000 – $10,000</option>
                <option value="$10k+" {{ old('budget')=='$10k+'?'selected':'' }}>$10,000+</option>
                <option value="discuss" {{ old('budget')=='discuss'?'selected':'' }}>Let's discuss</option>
              </select>
            </div>
            <div class="field-wrap"><label for="c-message">Message</label><textarea id="c-message" name="message" required maxlength="5000" class="field-input" rows="5" placeholder="Tell us about your project...">{{ old('message', is_string(request('plan')) ? "I'm interested in: ".request('plan')."." : '') }}</textarea>@error('message')<span class="field-error">{{ $message }}</span>@enderror</div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;display:flex"><span>Send Message</span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection