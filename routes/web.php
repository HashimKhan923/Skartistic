<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLeadController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\AboutController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/services', [FrontendController::class, 'services'])->name('services');
Route::get('/services/{slug}', [FrontendController::class, 'serviceDetail'])->name('service.detail');
Route::get('/about-us', [FrontendController::class, 'about'])->name('about');
Route::get('/meet-the-team', [FrontendController::class, 'team'])->name('team');
Route::get('/portfolio', [FrontendController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{slug}', [FrontendController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [FrontendController::class, 'blogPost'])->name('blog.post');
Route::get('/careers', [FrontendController::class, 'careers'])->name('careers');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'submitContact'])->name('contact.submit');
Route::get('/free-audit', [FrontendController::class, 'freeAudit'])->name('free-audit');
Route::post('/free-audit', [FrontendController::class, 'submitAudit'])->name('audit.submit');

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ur'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Static pages — MUST be last frontend route (catch-all)
Route::get('/{slug}', [FrontendController::class, 'page'])->name('page');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // ── Public admin routes (no auth required) ────────────────────────
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // ── Protected admin routes (requires admin middleware) ────────────
    Route::middleware(['admin'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Settings & Theme
        Route::get('/settings',  [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/theme',     [ThemeController::class, 'index'])->name('theme');
        Route::post('/theme',    [ThemeController::class, 'update'])->name('theme.update');

        // SEO Manager
        Route::get('/seo',  [SeoController::class, 'index'])->name('seo.index');
        Route::post('/seo', [SeoController::class, 'update'])->name('seo.update');

        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

        // Media Library
        Route::get('/media',                     [MediaController::class, 'index'])->name('media.index');
        Route::post('/media',                    [MediaController::class, 'store'])->name('media.store');
        Route::post('/media/upload',             [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('/media/{media}',          [MediaController::class, 'destroy'])->name('media.destroy');
        Route::post('/media/{media}/alt',        [MediaController::class, 'updateAlt'])->name('media.alt');

        // Audit Leads
        Route::get('/audit-leads',               [AuditLeadController::class, 'index'])->name('audit-leads.index');
        Route::get('/audit-leads/{lead}',        [AuditLeadController::class, 'show'])->name('audit-leads.show');
        Route::delete('/audit-leads/{lead}',     [AuditLeadController::class, 'destroy'])->name('audit-leads.destroy');

        // Portfolio
        Route::resource('portfolio', PortfolioController::class)->except(['show']);
        Route::get('/portfolio/{portfolio}/preview', [PortfolioController::class, 'show'])->name('portfolio.show');

        // Client Logos
        Route::resource('client-logos', ClientLogoController::class)->except(['show']);

        // Pages (manual routes to avoid resource show conflict)
        Route::get('/pages',              [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create',       [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages',             [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}',       [PageController::class, 'show'])->name('pages.show');
        Route::get('/pages/{page}/edit',  [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}',       [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}',    [PageController::class, 'destroy'])->name('pages.destroy');

        // Content Resources
        Route::resource('services',     ServiceController::class)->except(['show']);
    Route::get ('about',                  [App\Http\Controllers\Admin\AboutController::class, 'edit']          )->name('about');
    Route::put ('about',                  [App\Http\Controllers\Admin\AboutController::class, 'update']        )->name('about.update');
    Route::delete('about/award',          [App\Http\Controllers\Admin\AboutController::class, 'deleteAward']   )->name('about.award.delete');
    Route::delete('about/value',          [App\Http\Controllers\Admin\AboutController::class, 'deleteValue']   )->name('about.value.delete');
    Route::delete('about/founder',        [App\Http\Controllers\Admin\AboutController::class, 'deleteFounder'] )->name('about.founder.delete');
    Route::delete('about/faq',            [App\Http\Controllers\Admin\AboutController::class, 'deleteFaq']     )->name('about.faq.delete');
        Route::resource('blog',         BlogController::class)->except(['show']);
        Route::resource('team',         TeamController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);
        Route::resource('faqs',         FaqController::class)->except(['show']);
        Route::resource('pricing',      PricingController::class)->except(['show']);
        Route::resource('careers',      CareerController::class)->except(['show']);

        // Contact Submissions
        Route::get('/contacts',              [ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}',    [ContactController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    }); // end admin middleware group

}); // end admin prefix group