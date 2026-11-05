<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PricingPlan;
use App\Models\BlogPost;
use App\Models\TeamMember;
use App\Models\Page;
use App\Models\Career;
use App\Models\Contact;
use App\Models\Portfolio;
use App\Models\ClientLogo;
use App\Models\AuditLead;
use App\Mail\NewContactMail;
use App\Mail\NewAuditMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    protected function sharedData(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $services_nav = Service::where('is_published', true)->orderBy('sort_order')->get();
        $menu_pages = Page::where('is_published', true)->where('show_in_menu', true)->orderBy('menu_order')->get();
        $footer_partners = [
            $this->footerPartner($settings, 'associate_partner', 'Associate Partner', 'Kuvvet'),
            $this->footerPartner($settings, 'creative_partner', 'Creative Partner', 'Sindh Food Awards'),
        ];
        // Page view tracking is handled globally by the TrackPageView middleware.
        return compact('settings', 'services_nav', 'menu_pages', 'footer_partners');
    }

    /** Uses the logo from Settings if uploaded, otherwise the matching Client Logo. */
    protected function footerPartner(array $settings, string $key, string $label, string $defaultName): array
    {
        $name = ($settings[$key.'_name'] ?? '') ?: $defaultName;
        $client = ClientLogo::where('name', 'like', '%'.$name.'%')->first();
        $logo = !empty($settings[$key.'_logo']) ? asset($settings[$key.'_logo']) : ($client ? asset('storage/'.$client->logo) : null);
        return ['label' => $label, 'name' => $name, 'logo' => $logo, 'url' => $client->website_url ?? null];
    }

    /** Drafts stay private; a logged-in admin may still preview them. */
    protected function publishedOrPreview($query)
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->role, ['admin', 'super_admin']);
        return $isAdmin ? $query : $query->where('is_published', true);
    }

    /** Ignores malformed filters such as ?category[]=x. */
    protected function categoryFilter(Request $request): ?string
    {
        $category = $request->query('category');
        return is_string($category) && $category !== '' ? $category : null;
    }

    public function home()
    {
        $data = $this->sharedData();
        $services = Service::where('is_published', true)->orderBy('sort_order')->get();
        $testimonials = Testimonial::where('is_published', true)->orderBy('sort_order')->get();
        $faqs = Faq::where('is_published', true)->orderBy('sort_order')->get();
        $pricing_plans = PricingPlan::where('is_published', true)->orderBy('sort_order')->get();
        $featured_portfolios = Portfolio::where('is_published', true)->where('is_featured', true)->orderBy('sort_order')->take(6)->get();
        $client_logos = ClientLogo::where('is_published', true)->orderBy('sort_order')->get();
        return view('frontend.home', array_merge($data, compact('services','testimonials','faqs','pricing_plans','featured_portfolios','client_logos')));
    }

    public function services()
    {
        $data = $this->sharedData();
        $services = Service::where('is_published', true)->orderBy('sort_order')->get();
        return view('frontend.services', array_merge($data, compact('services')));
    }

    public function serviceDetail($slug)
    {
        $data = $this->sharedData();
        $service = $this->publishedOrPreview(Service::where('slug', $slug))->firstOrFail();
        return view('frontend.service-detail', array_merge($data, compact('service')));
    }

    public function about()
    {
        $data     = $this->sharedData();
        $about    = \App\Models\About::firstOrCreate([]);
        $awards   = \App\Models\Award::where('is_active', true)->orderBy('sort_order')->get();
        $values   = \App\Models\CoreValue::where('is_active', true)->orderBy('sort_order')->get();
        $founders = \App\Models\Founder::where('is_active', true)->orderBy('sort_order')->get();
        $faqs     = \App\Models\Faq::where('is_published', true)->orderBy('sort_order')->get();
        $team     = \App\Models\TeamMember::where('is_published', true)->orderBy('sort_order')->take(8)->get();

        return view('frontend.about', array_merge($data, compact(
            'about', 'awards', 'values', 'founders', 'faqs', 'team'
        )));
    }

    public function teamMember($slug)
    {
        $data = $this->sharedData();
        $member = $this->publishedOrPreview(TeamMember::where('slug', $slug))->firstOrFail();
        return view('frontend.team-member', array_merge($data, compact('member')));
    }

    public function team()
    {
        $data = $this->sharedData();
        $team = TeamMember::where('is_published', true)->orderBy('sort_order')->get();
        return view('frontend.team', array_merge($data, compact('team')));
    }

public function portfolio(Request $request)
{
    $data = $this->sharedData();

    $query = Portfolio::where('is_published', true)
        ->orderBy('sort_order');

    if ($category = $this->categoryFilter($request)) {
        $query->where('category', $category);
    }

    $portfolios = $query->paginate(12)->withQueryString();

    $categories = Portfolio::where('is_published', true)
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->pluck('category');

    return view('frontend.portfolio', array_merge($data, compact('portfolios','categories')));
}

    public function portfolioDetail($slug)
    {
        $data = $this->sharedData();
        $project = $this->publishedOrPreview(Portfolio::where('slug', $slug))->firstOrFail();
        $related = Portfolio::where('is_published', true)->where('id', '!=', $project->id)->where('category', $project->category)->take(3)->get();
        return view('frontend.portfolio-detail', array_merge($data, ['project' => $project, 'related_projects' => $related]));
    }

    public function blog(Request $request)
    {
        $data = $this->sharedData();

        $query = BlogPost::where('is_published', true);
        if ($category = $this->categoryFilter($request)) {
            $query->where('category', $category);
        }
        $posts = $query->latest('published_at')->paginate(9)->withQueryString();

        $categories = BlogPost::where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('frontend.blog', array_merge($data, compact('posts', 'categories')));
    }

    public function blogPost($slug)
    {
        $data = $this->sharedData();
        $post = $this->publishedOrPreview(BlogPost::where('slug', $slug))->firstOrFail();
        $related = BlogPost::where('is_published', true)->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();
        return view('frontend.blog-post', array_merge($data, compact('post','related')));
    }


    public function pricing()
    {
        $data = $this->sharedData();
        $pricing_plans = PricingPlan::where('is_published', true)->orderBy('sort_order')->get();
        $faqs = Faq::where('is_published', true)->orderBy('sort_order')->get();
        return view('frontend.pricing', array_merge($data, compact('pricing_plans', 'faqs')));
    }

    public function careers()
    {
        $data = $this->sharedData();
        $jobs = Career::where('is_published', true)->orderBy('sort_order')->latest()->get();
        return view('frontend.careers', array_merge($data, compact('jobs')));
    }

    public function contact()
    {
        $data = $this->sharedData();

        $data['services'] = Service::where('is_published', true)->orderBy('sort_order')->get();

        return view('frontend.contact', $data);
    }

    public function submitContact(Request $request)
    {
        if ($request->filled('company_site')) { // honeypot: bots only
            return back()->with('success', 'Message sent! We\'ll get back to you within 24 hours.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);
        $contact = Contact::create($validated);
        try {
            $adminEmail = Setting::get('site_email');
            if ($adminEmail) Mail::to($adminEmail)->send(new NewContactMail($contact));
        } catch (\Exception $e) {
            \Log::error('Contact mail failed: ' . $e->getMessage());
        }
        return back()->with('success', 'Message sent! We\'ll get back to you within 24 hours.');
    }

    public function freeAudit()
    {
        $data = $this->sharedData();
        return view('frontend.free-audit', $data);
    }

    public function submitAudit(Request $request)
    {
        if ($request->filled('company_site')) { // honeypot: bots only
            return back()->with('success', 'Audit request submitted! We\'ll analyze your website and get back to you within 48 hours.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website_url' => 'nullable|max:255',
            'business_type' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'goals' => 'nullable|string|max:5000',
            'services_needed' => 'nullable|array',
            'services_needed.*' => 'string|max:255',
        ]);
        $lead = AuditLead::create($validated);
        try {
            $adminEmail = Setting::get('site_email');
            if ($adminEmail) Mail::to($adminEmail)->send(new NewAuditMail($lead));
        } catch (\Exception $e) {
            \Log::error('Audit mail failed: ' . $e->getMessage());
        }
        return back()->with('success', 'Audit request submitted! We\'ll analyze your website and get back to you within 48 hours.');
    }

    public function page($slug)
    {
        $data = $this->sharedData();
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('frontend.page', array_merge($data, compact('page')));
    }

    /** Served from the SEO Manager's robots_txt setting. */
    public function robots()
    {
        $body = trim((string) Setting::get('robots_txt', '')) ?: "User-agent: *\nDisallow: /admin/\n";
        return response($body, 200)->header('Content-Type', 'text/plain');
    }
}