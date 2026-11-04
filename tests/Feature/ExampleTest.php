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
use App\Models\ThemeSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

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

it('applies the theme panel to the page', function () {
    ThemeSetting::set('primary_color', '#112233');
    ThemeSetting::set('secondary_color', '#445566');
    ThemeSetting::set('bg_color', '#fefefe');
    $this->get('/')
        ->assertSee('--brand: #112233;', false)
        ->assertSee('--brand-rgb: 17,34,51;', false)
        ->assertSee('--accent-rgb: 68,85,102;', false)
        ->assertSee('--void: #fefefe;', false);
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

it('redirects bare /admin and guards the panel', function () {
    $this->get('/admin')->assertRedirect('/admin/dashboard');
    $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    $this->actingAs(User::factory()->create(['role' => 'user']))->get('/admin/dashboard')->assertRedirect('/admin/login');
});

it('renders every admin screen', function (string $url) {
    seedSite();
    $this->actingAs(admin())->get($url)->assertOk();
})->with([
    '/admin/dashboard', '/admin/settings', '/admin/theme', '/admin/seo', '/admin/analytics', '/admin/media',
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
    $this->get('/terms-conditions')->assertOk()->assertSee('LEGAL TERMS');
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
