<?php

use App\Mail\NewAuditMail;
use App\Mail\NewContactMail;
use App\Models\AuditLead;
use App\Models\BlogPost;
use App\Models\Career;
use App\Models\ClientLogo;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\GalleryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function seedSite(): void
{
    Setting::set('site_name', 'SK Artistic');
    Setting::set('site_email', 'hello@example.com');
    Service::create(['title' => 'Web Development', 'slug' => 'web-development', 'short_description' => 'Sites', 'is_published' => true]);
    Service::create(['title' => 'Secret Service', 'slug' => 'secret-service', 'is_published' => false]);
    Portfolio::create(['title' => 'Atlas', 'slug' => 'atlas', 'category' => 'web', 'is_published' => true, 'is_featured' => true, 'tags' => 'Laravel, GSAP', 'content' => '<p>Deep dive</p>']);
    Portfolio::create(['title' => 'Boreal', 'slug' => 'boreal', 'category' => 'web', 'is_published' => true]);
    Portfolio::create(['title' => 'Hidden Project', 'slug' => 'hidden-project', 'is_published' => false]);
    BlogPost::create(['title' => 'First Post', 'slug' => 'first-post', 'content' => '<p>Hello</p>', 'category' => 'news', 'is_published' => true, 'published_at' => now()]);
    BlogPost::create(['title' => 'Draft Post', 'slug' => 'draft-post', 'content' => '<p>Soon</p>', 'is_published' => false]);
    Page::create(['title' => 'Terms', 'slug' => 'terms-conditions', 'content' => '<p>Terms</p>', 'is_published' => true]);
    Page::create(['title' => 'Privacy', 'slug' => 'privacy-policy', 'content' => '<p>Privacy</p>', 'is_published' => true]);
    Career::create(['title' => 'Senior Frontend Developer', 'summary' => 'Build things', 'is_published' => true, 'responsibilities' => ['Ship'], 'requirements' => ['Skill'], 'benefits' => ['Remote']]);
    Testimonial::create(['client_name' => 'Ayesha', 'review' => 'Great', 'rating' => 5, 'is_published' => true]);
    Faq::create(['question' => 'Why?', 'answer' => 'Because.', 'is_published' => true]);
    PricingPlan::create(['name' => 'Starter', 'features' => ['One'], 'cta_url' => '/contact', 'is_published' => true]);
    TeamMember::create(['name' => 'Sam', 'position' => 'Dev', 'is_published' => true]);
    ClientLogo::create(['name' => 'Acme', 'logo' => 'clients/acme.png', 'is_published' => true]);
}

function admin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

/* ── Public pages ─────────────────────────────────────────── */

it('renders every public page with content', function (string $url) {
    seedSite();
    $this->get($url)->assertOk()->assertSee('</html>', false);
})->with([
    '/', '/services', '/services/web-development', '/pricing', '/about-us', '/meet-the-team',
    '/portfolio', '/portfolio/atlas', '/blog', '/blog/first-post', '/careers', '/contact', '/free-audit',
    '/terms-conditions', '/privacy-policy',
]);

it('renders every public listing with an empty database', function (string $url) {
    $this->get($url)->assertOk();
})->with(['/', '/services', '/pricing', '/about-us', '/meet-the-team', '/portfolio', '/blog', '/careers', '/contact', '/free-audit']);

it('shows published jobs on the careers page', function () {
    seedSite();
    $this->get('/careers')->assertSee('Senior Frontend Developer')->assertDontSee('NO OPENINGS RIGHT NOW');
});

it('renders featured work and client logos on the home page', function () {
    seedSite();
    $this->get('/')->assertSee('Atlas')->assertSee('clients/acme.png');
});

it('links related projects and shows project content and tags', function () {
    seedSite();
    $this->get('/portfolio/atlas')->assertSee('Boreal')->assertSee('Deep dive', false)->assertSee('GSAP');
});

it('hides drafts from visitors but lets admins preview them', function (string $url) {
    seedSite();
    $this->get($url)->assertNotFound();
    $this->actingAs(admin())->get($url)->assertOk();
})->with(['/services/secret-service', '/portfolio/hidden-project', '/blog/draft-post']);

it('keeps the category filter across pagination', function () {
    foreach (range(1, 13) as $i) {
        Portfolio::create(['title' => "P$i", 'slug' => "p$i", 'category' => 'web', 'is_published' => true]);
    }
    $this->get('/portfolio?category=web')->assertSee('category=web&amp;page=2', false);
});

it('ignores a malformed category filter', function () {
    $this->get('/portfolio?category[]=x')->assertOk();
    $this->get('/blog?category[]=x')->assertOk();
});

it('prefills the contact form from a chosen plan', function () {
    seedSite();
    $this->get('/pricing')->assertSee('contact?plan=Starter', false);
    $this->get('/contact?plan=Starter')->assertSee('interested in: Starter.', false);
});

it('serves branded error pages', function () {
    $this->get('/definitely-not-a-page')->assertNotFound()->assertSee('This page wandered off');
});

/* ── SEO + theme wiring ───────────────────────────────────── */

it('uses SEO Manager values in the page head', function () {
    Setting::set('seo_title', 'Custom SEO Title');
    Setting::set('seo_description', 'Custom description');
    Setting::set('og_image', 'https://cdn.example.com/og.png');
    Setting::set('google_analytics_id', 'G-ABC123');
    $this->get('/')
        ->assertSee('<title>Custom SEO Title</title>', false)
        ->assertSee('content="Custom description"', false)
        ->assertSee('https://cdn.example.com/og.png', false)
        ->assertSee('G-ABC123', false);
});

it('refuses an unsafe analytics id', function () {
    Setting::set('google_analytics_id', 'x"></script><script>alert(1)</script>');
    $this->get('/')->assertDontSee('alert(1)', false);
});

it('serves robots.txt from settings', function () {
    $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin/');
    Setting::set('robots_txt', "User-agent: *\nDisallow: /private/");
    $this->get('/robots.txt')->assertSee('Disallow: /private/');
});

it('offers a dark mode toggle on public and error pages', function () {
    $this->get('/')->assertSee('id="theme-btn"', false)->assertSee('html[data-theme="dark"]', false);
    $this->get('/definitely-not-a-page')->assertSee('html[data-theme="dark"]', false);
});

it('uses the one fixed purple theme and has no theme panel', function () {
    $this->get('/')->assertSee('--brand:   #6655e7;', false)->assertSee('--magenta: #8578ee;', false);
    $this->actingAs(admin())->get('/admin/theme')->assertNotFound();
    $this->get('/admin/dashboard')->assertOk()->assertDontSee('Edit Theme');
});

it('does not wipe the og image when SEO settings are saved', function () {
    Setting::set('og_image', 'uploads/settings/seo/og.png');
    $this->actingAs(admin())->post('/admin/seo', ['seo_title' => 'x'])->assertRedirect();
    expect(Setting::get('og_image'))->toBe('uploads/settings/seo/og.png');
});

/* ── Forms ────────────────────────────────────────────────── */

it('stores a contact message and emails the site owner', function () {
    seedSite();
    Mail::fake();
    $this->post('/contact', ['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hi', 'service' => 'Web Development', 'budget' => '$10k+'])
        ->assertRedirect()->assertSessionHas('success');
    expect(Contact::where('email', 'jo@example.com')->where('budget', '$10k+')->exists())->toBeTrue();
    Mail::assertSent(NewContactMail::class);
});

it('stores an audit request and emails the site owner', function () {
    seedSite();
    Mail::fake();
    $this->post('/free-audit', ['name' => 'Jo', 'email' => 'jo@example.com', 'website_url' => 'https://a.test', 'services_needed' => ['Web Development']])
        ->assertRedirect()->assertSessionHas('success');
    expect(AuditLead::first()->services_needed)->toBe(['Web Development']);
    Mail::assertSent(NewAuditMail::class);
});

it('renders both notification emails', function () {
    $contact = Contact::create(['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hi']);
    $lead = AuditLead::create(['name' => 'Jo', 'email' => 'jo@example.com', 'services_needed' => ['Web']]);
    expect((new NewContactMail($contact))->render())->toContain('Jo');
    expect((new NewAuditMail($lead))->render())->toContain('Jo');
});

it('validates the forms and silently drops honeypot hits', function () {
    $this->post('/contact', [])->assertSessionHasErrors(['name', 'email', 'message']);
    $this->post('/free-audit', [])->assertSessionHasErrors(['name', 'email']);
    $this->post('/contact', ['company_site' => 'spam', 'name' => 'Bot', 'email' => 'b@b.co', 'message' => 'x'])->assertRedirect();
    expect(Contact::count())->toBe(0);
});

it('throttles contact and login abuse', function () {
    foreach (range(1, 10) as $i) {
        $this->post('/contact', []);
    }
    $this->post('/contact', [])->assertStatus(429);
    foreach (range(1, 5) as $i) {
        $this->post('/admin/login', ['email' => 'a@b.co', 'password' => 'nope']);
    }
    $this->post('/admin/login', ['email' => 'a@b.co', 'password' => 'nope'])->assertStatus(429);
});

/* ── Admin ────────────────────────────────────────────────── */

it('gives the admin panel and login the same theme and toggle', function () {
    $this->get('/admin/login')->assertOk()->assertSee('id="theme-btn"', false)->assertSee('--p:#6655e7', false);
    $this->withSession(['error' => 'Access denied.'])->get('/admin/login')->assertSee('Access denied.');
    $this->actingAs(admin())->get('/admin/dashboard')->assertOk()
        ->assertSee('id="theme-btn"', false)->assertSee('id="menu-btn"', false)->assertDontSee('#06b6d4');
});

it('shows a service description even when only the hero subtitle is set', function () {
    Service::create(['title' => 'Motion', 'slug' => 'motion', 'hero_subtitle' => 'Fallback words here', 'is_published' => true]);
    $this->get('/')->assertSee('Fallback words here');
    $this->get('/services')->assertSee('Fallback words here');
});

it('renders each client logo once and lets the motion layer loop them', function () {
    ClientLogo::create(['name' => 'Acme', 'logo' => 'clients/acme.png', 'is_published' => true]);
    expect(substr_count($this->get('/')->getContent(), 'clients/acme.png'))->toBe(1);
});

it('shows a full team profile and keeps drafts private', function () {
    $member = TeamMember::create(['name' => 'Sultan Khan', 'position' => 'CEO', 'bio' => 'Founder story', 'is_published' => true,
        'expertise' => ['Strategy'], 'education' => ['BS Computing'], 'achievements' => ['Certified PM'], 'gallery' => ['team/gallery/a.png'], 'instagram' => 'https://instagram.com/x']);
    expect($member->slug)->toBe('sultan-khan');
    $this->get('/meet-the-team')->assertSee('/meet-the-team/sultan-khan', false);
    $this->get('/meet-the-team/sultan-khan')->assertOk()->assertSee('Founder story')->assertSee('Strategy')->assertSee('BS Computing')->assertSee('Certified PM')->assertSee('team/gallery/a.png')->assertSee('aria-label="Instagram"', false);
    $member->update(['is_published' => false]);
    $this->get('/meet-the-team/sultan-khan')->assertNotFound();
});

it('saves team profile fields and a gallery from the admin form', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    $image = \Illuminate\Http\UploadedFile::fake()->image('win.png');
    $this->actingAs(admin())->post('/admin/team', ['name' => 'Sam Lee', 'position' => 'Dev', 'expertise_raw' => "Laravel\n\nGSAP", 'education_raw' => 'BS', 'achievements_raw' => 'Award', 'github' => 'https://github.com/sam', 'gallery' => [$image], 'is_published' => '1'])->assertRedirect();
    $member = TeamMember::first();
    expect($member->expertise)->toBe(['Laravel', 'GSAP'])->and($member->github)->toBe('https://github.com/sam')->and($member->gallery)->toHaveCount(1);
    $path = $member->gallery[0];
    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($path);
    $this->put("/admin/team/{$member->id}", ['name' => 'Sam Lee', 'position' => 'Dev', 'remove_gallery' => [$path, '../../.env'], 'is_published' => '1'])->assertRedirect();
    expect($member->fresh()->gallery)->toBe([])->and($member->fresh()->slug)->toBe('sam-lee');
    \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($path);
});

it('uses the editable statement text on the home page', function () {
    $this->get('/')->assertSee('obsessive attention to craft');
    Setting::set('statement_text', 'A brand new statement');
    $this->get('/')->assertSee('A brand new statement')->assertDontSee('obsessive attention to craft');
});

it('shows footer partners, trademark and google badges', function () {
    ClientLogo::create(['name' => 'Kuvvet', 'logo' => 'clients/kuvvet.png', 'is_published' => true]);
    $this->get('/')->assertSee('Associate Partner')->assertSee('Creative Partner')->assertSee('Sindh Food Awards')->assertSee('clients/kuvvet.png')->assertSee('Trademark')->assertSee('Reviews');
});

it('stores an uploaded tech logo and drops emojis from the service page', function () {
    $this->actingAs(admin())->post('/admin/services', [
        'title' => 'Tech Test',
        'tech_categories' => [['name' => 'Frontend', 'items' => [['name' => 'React', 'emoji' => '⚛️'], ['name' => 'Vue']]]],
        'tech_icons' => [[0 => \Illuminate\Http\UploadedFile::fake()->image('react.png')]],
        'is_published' => '1',
    ])->assertRedirect();
    $service = Service::where('title', 'Tech Test')->first();
    $path = $service->tech_categories[0]['items'][0]['icon_image'];
    expect($path)->toStartWith('uploads/services/tech/');
    if (file_exists(public_path($path))) unlink(public_path($path));
    $this->get('/services/'.$service->slug)->assertOk()->assertSee($path)->assertDontSee('⚛️')->assertSee('Vue');
});

it('never shows an emoji icon on service cards', function () {
    Service::create(['title' => 'Mobile Apps', 'slug' => 'mobile-apps', 'icon' => '📱', 'short_description' => 'x', 'is_published' => true]);
    $this->get('/')->assertSee('Mobile Apps')->assertDontSee('📱');
    $this->get('/services')->assertDontSee('📱');
});

it('shows a custom quote when a plan has no price', function () {
    PricingPlan::create(['name' => 'Starter', 'features' => ['One'], 'is_published' => true]);
    $this->get('/pricing')->assertSee('Custom Quote');
    PricingPlan::first()->update(['price' => 500, 'price_suffix' => '/mo']);
    $this->get('/pricing')->assertSee('$500')->assertDontSee('Custom Quote');
});

it('redirects bare /admin and guards the panel', function () {
    $this->get('/admin')->assertRedirect('/admin/dashboard');
    $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    $this->actingAs(User::factory()->create(['role' => 'user']))->get('/admin/dashboard')->assertRedirect('/admin/login');
});

it('renders every admin screen', function (string $url) {
    seedSite();
    $this->actingAs(admin())->get($url)->assertOk();
})->with([
    '/admin/dashboard', '/admin/settings', '/admin/seo', '/admin/analytics', '/admin/media',
    '/admin/audit-leads', '/admin/portfolio', '/admin/portfolio/create', '/admin/client-logos', '/admin/client-logos/create',
    '/admin/pages', '/admin/pages/create', '/admin/services', '/admin/services/create', '/admin/about',
    '/admin/blog', '/admin/blog/create', '/admin/team', '/admin/team/create', '/admin/testimonials', '/admin/testimonials/create',
    '/admin/faqs', '/admin/faqs/create', '/admin/pricing', '/admin/pricing/create', '/admin/careers', '/admin/careers/create',
    '/admin/contacts',
]);

it('renders admin edit screens for real records', function () {
    seedSite();
    $contact = Contact::create(['name' => 'Jo', 'email' => 'jo@example.com', 'message' => 'Hi']);
    $lead = AuditLead::create(['name' => 'Jo', 'email' => 'jo@example.com']);
    $this->actingAs(admin());
    foreach ([
        '/admin/portfolio/'.Portfolio::first()->id.'/edit',
        '/admin/client-logos/'.ClientLogo::first()->id.'/edit',
        '/admin/pages/'.Page::first()->id.'/edit',
        '/admin/services/'.Service::first()->slug.'/edit',
        '/admin/blog/'.BlogPost::first()->id.'/edit',
        '/admin/team/'.TeamMember::first()->id.'/edit',
        '/admin/testimonials/'.Testimonial::first()->id.'/edit',
        '/admin/faqs/'.Faq::first()->id.'/edit',
        '/admin/pricing/'.PricingPlan::first()->id.'/edit',
        '/admin/careers/'.Career::first()->id.'/edit',
        '/admin/contacts/'.$contact->id,
        '/admin/audit-leads/'.$lead->id,
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

it('gives duplicate titles unique slugs instead of crashing', function () {
    $this->actingAs(admin());
    $this->post('/admin/blog', ['title' => 'Same', 'content' => 'x'])->assertRedirect();
    $this->post('/admin/blog', ['title' => 'Same', 'content' => 'y'])->assertRedirect();
    expect(BlogPost::pluck('slug')->all())->toBe(['same', 'same-2']);
    $this->post('/admin/pages', ['title' => 'Same'])->assertRedirect();
    $this->post('/admin/pages', ['title' => 'Same'])->assertRedirect();
    expect(Page::pluck('slug')->all())->toBe(['same', 'same-2']);
    $this->post('/admin/services', ['title' => 'Same'])->assertRedirect();
    $this->post('/admin/services', ['title' => 'Same'])->assertRedirect();
    expect(Service::pluck('slug')->all())->toBe(['same', 'same-2']);
});

it('keeps a page URL stable when its title is edited', function () {
    seedSite();
    $page = Page::where('slug', 'terms-conditions')->first();
    $this->actingAs(admin())->put("/admin/pages/{$page->id}", ['title' => 'Legal Terms', 'is_published' => '1'])->assertRedirect();
    $this->get('/terms-conditions')->assertOk()->assertSee('Legal Terms');
});

it('accepts blank optional fields on career, pricing and service forms', function () {
    $this->actingAs(admin());
    $this->post('/admin/careers', ['title' => 'Designer'])->assertRedirect();
    $this->post('/admin/pricing', ['name' => 'Basic', 'price_suffix' => '', 'cta_label' => ''])->assertRedirect();
    $this->post('/admin/services', ['title' => 'Motion', 'hero_cta_primary' => '', 'hero_cta_secondary' => '', 'sort_order' => ''])->assertRedirect();
    expect(Service::where('title', 'Motion')->first()->hero_cta_primary)->toBe('Get in touch');
});

it('saves team sort order', function () {
    $this->actingAs(admin())->post('/admin/team', ['name' => 'Sam', 'position' => 'Dev', 'sort_order' => 7])->assertRedirect();
    expect(TeamMember::first()->sort_order)->toBe(7);
});

it('rejects executable uploads to public folders', function () {
    $file = \Illuminate\Http\UploadedFile::fake()->create('shell.php', 1);
    $this->actingAs(admin())->post('/admin/settings', ['site_logo' => $file])->assertStatus(422);
});

/*
|--------------------------------------------------------------------------
| Gallery, Instagram, WhatsApp and portfolio image removal
|--------------------------------------------------------------------------
*/
function adminUser(): User
{
    return User::create(['name' => 'Admin', 'email' => 'gallery-admin@example.com', 'password' => 'secret-pass-123', 'role' => 'admin']);
}

it('accepts 4:5 gallery images and rejects other sizes', function () {
    Storage::fake('public');
    $admin = adminUser();

    $this->actingAs($admin)->post(route('admin.gallery.store'), ['images' => [UploadedFile::fake()->image('ok.jpg', 1080, 1350)]])->assertSessionHasNoErrors();
    expect(GalleryImage::count())->toBe(1);
    Storage::disk('public')->assertExists(GalleryImage::first()->image);

    $this->actingAs($admin)->post(route('admin.gallery.store'), ['images' => [UploadedFile::fake()->image('wide.jpg', 1600, 900)]])->assertSessionHasErrors('images.0');
    expect(GalleryImage::count())->toBe(1);
});

it('shows only published images on the gallery page and deletes the file with the record', function () {
    Storage::fake('public');
    seedSite();
    Storage::disk('public')->put('gallery/a.jpg', 'x');
    $shown = GalleryImage::create(['image' => 'gallery/a.jpg', 'caption' => 'Shown caption']);
    GalleryImage::create(['image' => 'gallery/b.jpg', 'caption' => 'Hidden caption', 'is_published' => false]);

    $this->get('/portfolio/gallery')->assertOk()->assertSee('Shown caption')->assertDontSee('Hidden caption');

    $this->actingAs(adminUser())->delete(route('admin.gallery.destroy', $shown))->assertRedirect();
    expect(GalleryImage::find($shown->id))->toBeNull();
    Storage::disk('public')->assertMissing('gallery/a.jpg');
});

it('blocks guests from the gallery admin', function () {
    $this->get(route('admin.gallery.index'))->assertRedirect();
    $this->post(route('admin.gallery.store'))->assertRedirect();
    expect(GalleryImage::count())->toBe(0);
});

it('removes only images that belong to the project', function () {
    Storage::fake('public');
    Storage::disk('public')->put('portfolio/gallery/a.jpg', 'x');
    Storage::disk('public')->put('portfolio/gallery/b.jpg', 'x');
    Storage::disk('public')->put('secret.txt', 'x');
    $project = Portfolio::create(['title' => 'Atlas', 'slug' => 'atlas-x', 'is_published' => true, 'images' => ['portfolio/gallery/a.jpg', 'portfolio/gallery/b.jpg']]);

    $this->actingAs(adminUser())->put(route('admin.portfolio.update', $project), [
        'title' => 'Atlas', 'is_published' => 1,
        'remove_images' => ['portfolio/gallery/a.jpg', 'secret.txt'],
    ])->assertRedirect();

    expect($project->fresh()->images)->toBe(['portfolio/gallery/b.jpg']);
    Storage::disk('public')->assertMissing('portfolio/gallery/a.jpg');
    Storage::disk('public')->assertExists('secret.txt');
});

it('keeps project images when nothing is removed', function () {
    $project = Portfolio::create(['title' => 'Atlas', 'slug' => 'atlas-y', 'images' => ['portfolio/gallery/a.jpg']]);
    $this->actingAs(adminUser())->put(route('admin.portfolio.update', $project), ['title' => 'Atlas'])->assertRedirect();
    expect($project->fresh()->images)->toBe(['portfolio/gallery/a.jpg']);
});

it('shows Instagram API posts on the homepage without exposing the token', function () {
    seedSite();
    Setting::set('social_instagram', 'https://www.instagram.com/sk_artistic/');
    Setting::set('instagram_access_token', 'SECRET-TOKEN');
    Setting::set('instagram_token_refreshed_at', (string) now()->timestamp);
    Http::fake(['graph.instagram.com/me/media*' => Http::response(['data' => [
        ['id' => '1', 'media_type' => 'IMAGE', 'media_url' => 'https://cdn.example.com/1.jpg', 'permalink' => 'https://www.instagram.com/p/AAA/', 'caption' => 'Hello'],
        ['id' => '2', 'media_type' => 'VIDEO', 'media_url' => 'https://cdn.example.com/2.mp4', 'thumbnail_url' => 'https://cdn.example.com/2.jpg', 'permalink' => 'https://www.instagram.com/p/BBB/'],
    ]])]);

    $this->get('/')->assertOk()->assertSee('@sk_artistic')->assertSee('https://cdn.example.com/1.jpg')->assertSee('https://cdn.example.com/2.jpg')->assertDontSee('SECRET-TOKEN');
    $this->get('/')->assertOk();
    Http::assertSentCount(1); // the second view comes from the cache
});

it('falls back to pasted post links when the Instagram API fails', function () {
    seedSite();
    Setting::set('instagram_access_token', 'BAD');
    Setting::set('instagram_token_refreshed_at', (string) now()->timestamp);
    Setting::set('instagram_posts', "https://www.instagram.com/p/ABC123/\nhttps://evil.example.com/x\nhttps://www.instagram.com/sk_artistic/reel/XYZ_9/");
    Http::fake(['*' => Http::response(['error' => ['message' => 'bad token']], 400)]);

    $this->get('/')->assertOk()
        ->assertSee('https://www.instagram.com/p/ABC123/embed/')
        ->assertSee('https://www.instagram.com/reel/XYZ_9/embed/')
        ->assertDontSee('evil.example.com');
});

it('links the WhatsApp button to the business number', function () {
    seedSite();
    Setting::set('site_phone', '+92 334 2247807');
    $this->get('/')->assertOk()->assertSee('https://wa.me/923342247807');
});

it('shows the new hero badge and no fake client claim', function () {
    seedSite();
    $this->get('/')->assertOk()->assertSee('Creative Technology Company')->assertDontSee('Trusted by <strong>', false);
});
