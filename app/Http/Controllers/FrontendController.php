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
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    protected function sharedData(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $services_nav = Service::where('is_published', true)->orderBy('sort_order')->get();
        try { \App\Models\PageView::create(["page_url"=>request()->path(),"ip_address"=>request()->ip(),"user_agent"=>request()->userAgent(),"referrer"=>request()->headers->get("referer")]); } catch (\Exception $e) {}
        return compact('settings', 'services_nav');
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
        $service = Service::where('slug', $slug)->firstOrFail();
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

    if ($request->category) {
        $query->where('category', $request->category);
    }

    $portfolios = $query->paginate(12); // IMPORTANT

    $categories = Portfolio::where('is_published', true)
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category');

    return view('frontend.portfolio', array_merge($data, compact('portfolios','categories')));
}

    public function portfolioDetail($slug)
    {
        $data = $this->sharedData();
        $project = Portfolio::where('slug', $slug)->firstOrFail();
        $related = Portfolio::where('is_published', true)->where('id', '!=', $project->id)->where('category', $project->category)->take(3)->get();
        return view('frontend.portfolio-detail', array_merge($data, compact('project','related')));
    }

    public function blog(Request $request)
    {
        $data = $this->sharedData();
        $posts = BlogPost::where('is_published', true)->latest('published_at')->paginate(9);
        return view('frontend.blog', array_merge($data, compact('posts')));
    }

    public function blogPost($slug)
    {
        $data = $this->sharedData();
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $related = BlogPost::where('is_published', true)->where('id', '!=', $post->id)->latest()->take(3)->get();
        return view('frontend.blog-post', array_merge($data, compact('post','related')));
    }


    public function careers()
    {
        $data = $this->sharedData();
        $careers = Career::where('is_published', true)->latest()->get();
        return view('frontend.careers', array_merge($data, compact('careers')));
    }

    public function contact()
    {
        $data = $this->sharedData();

        $data['services'] = Service::all(); // adjust condition if needed

        return view('frontend.contact', $data);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website_url' => 'nullable|max:255',
            'business_type' => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'goals' => 'nullable|string',
            'services_needed' => 'nullable|array',
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
        return view('frontend.pages.dynamic', array_merge($data, compact('page')));
    }
}