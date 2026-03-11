@extends('admin.layouts.app')
@section('title','SEO Manager')

@section('content')
<form method="POST" action="{{ route('admin.seo.update') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:22px">

        {{-- Default Meta Tags --}}
        <div class="card">
            <div class="card-head"><div class="card-title">🔍 Default Meta Tags</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Default SEO Title</label>
                    <input class="form-control" type="text" name="seo_title" value="{{ $settings['seo_title'] ?? '' }}" placeholder="SK Artistic — Designing without borders">
                    <div class="form-hint">Shown in search results. Aim for 50–60 characters.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Default Meta Description</label>
                    <textarea class="form-control" name="seo_description" rows="3" placeholder="Full-cycle digital agency specializing in...">{{ $settings['seo_description'] ?? '' }}</textarea>
                    <div class="form-hint">Shown in search snippets. Aim for 150–160 characters.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Focus Keywords</label>
                    <input class="form-control" type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? '' }}" placeholder="web design, mobile app, UI/UX, Pakistan">
                    <div class="form-hint">Comma-separated keywords</div>
                </div>
            </div>
        </div>

        {{-- Open Graph --}}
        <div class="card">
            <div class="card-head"><div class="card-title">📱 Open Graph (Social Sharing)</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">OG Title</label>
                    <input class="form-control" type="text" name="og_title" value="{{ $settings['og_title'] ?? '' }}" placeholder="Leave blank to use SEO Title">
                </div>
                <div class="form-group">
                    <label class="form-label">OG Description</label>
                    <textarea class="form-control" name="og_description" rows="3" placeholder="Leave blank to use meta description">{{ $settings['og_description'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">OG Image</label>
                    @if(!empty($settings['og_image']))
                    <img src="{{ $settings['og_image'] }}" alt="OG Image" style="width:100%;height:120px;object-fit:cover;border-radius:10px;margin-bottom:10px">
                    @endif
                    <input type="file" name="og_image_upload" accept="image/*" class="form-control">
                    <div class="form-hint">Recommended: 1200×630px. Shown when shared on Facebook, Twitter, WhatsApp.</div>
                </div>
            </div>
        </div>

        {{-- Analytics --}}
        <div class="card">
            <div class="card-head"><div class="card-title">📊 Analytics & Tracking</div></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Google Analytics 4 Measurement ID</label>
                    <input class="form-control" type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}" placeholder="G-XXXXXXXXXX">
                    <div class="form-hint">Paste your GA4 Measurement ID. Auto-injected in &lt;head&gt;.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Tag Manager ID</label>
                    <input class="form-control" type="text" name="google_tag_manager_id" value="{{ $settings['google_tag_manager_id'] ?? '' }}" placeholder="GTM-XXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook Pixel ID</label>
                    <input class="form-control" type="text" name="facebook_pixel_id" value="{{ $settings['facebook_pixel_id'] ?? '' }}" placeholder="123456789012345">
                </div>
            </div>
        </div>

        {{-- Robots.txt --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">🤖 robots.txt</div>
                <a href="/robots.txt" target="_blank" class="btn btn-ghost btn-sm">View Live</a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">robots.txt Content</label>
                    <textarea class="form-control" name="robots_txt" rows="10" style="font-family:monospace;font-size:13px"
                    >{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml') }}</textarea>
                    <div class="form-hint">Controls what search engines can crawl.</div>
                </div>
            </div>
        </div>

        {{-- SEO Checklist --}}
        <div class="card" style="grid-column:1/-1">
            <div class="card-head"><div class="card-title">✅ SEO Checklist</div></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px">
                    @php
                    $checks = [
                        ['label'=>'SEO Title set','done'=>!empty($settings['seo_title']),'tip'=>'Set a default SEO title above'],
                        ['label'=>'Meta Description set','done'=>!empty($settings['seo_description']),'tip'=>'Set a default meta description above'],
                        ['label'=>'OG Image uploaded','done'=>!empty($settings['og_image']),'tip'=>'Upload an OG image for social sharing'],
                        ['label'=>'Google Analytics connected','done'=>!empty($settings['google_analytics_id']),'tip'=>'Add your GA4 ID above'],
                        ['label'=>'Site has blog content','done'=>\App\Models\BlogPost::where('is_published',true)->exists(),'tip'=>'Publish blog posts for SEO'],
                        ['label'=>'Portfolio published','done'=>\App\Models\Portfolio::where('is_published',true)->exists(),'tip'=>'Add portfolio projects'],
                        ['label'=>'Contact info set','done'=>!empty($settings['site_email']),'tip'=>'Add contact info in Settings'],
                        ['label'=>'Social links set','done'=>!empty($settings['social_instagram']),'tip'=>'Add social links in Settings'],
                    ];
                    @endphp
                    @foreach($checks as $check)
                    <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:10px;background:{{ $check['done'] ? 'rgba(16,185,129,.06)' : 'rgba(245,158,11,.06)' }};border:1px solid {{ $check['done'] ? 'rgba(16,185,129,.2)' : 'rgba(245,158,11,.2)' }}">
                        <span style="font-size:1.2rem">{{ $check['done'] ? '✅' : '⚠️' }}</span>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:{{ $check['done'] ? '#065f46' : '#92400e' }}">{{ $check['label'] }}</div>
                            @if(!$check['done'])<div style="font-size:11px;color:#9ca3af;margin-top:2px">{{ $check['tip'] }}</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:22px;display:flex;gap:12px">
        <button type="submit" class="btn btn-primary" style="padding:13px 32px;font-size:15px">💾 Save SEO Settings</button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-ghost">🌐 Preview Site</a>
    </div>
</form>
@endsection