<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PricingPlan;
use App\Models\PricingFeature;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@skartistic.com',
            'password' => Hash::make('password'),
        ]);

        // General settings
        $settings = [

            // Site Information
            'site_name'        => 'SK Artistic',
            'site_tagline'     => 'Designing without borders',
            'site_email'       => 'info@skartistic.com',
            'site_phone'       => '+92 334 2247807',
            'site_address'     => 'Pakistan',
            'site_logo'        => '',
            'footer_copyright' => '© 2026 Sk Artistic (SMC-PVT-LTD)',

            // Hero Section
            'hero_line1'          => 'Transforming Visions Into',
            'hero_animated_words' => 'Seamless Designs,Digital Creation,Innovative Ideas,Impactful Solutions',
            'hero_subtitle'       => 'From idea to execution, we deliver consistent, smart, and beautifully crafted design work.',
            'hero_btn1'           => 'Schedule a Meeting',
            'hero_btn2'           => 'Our Work',
            'hero_trust_text'     => 'clients worldwide.',

            // Promo Video Section
            'promo_video'      => '',
            'promo_video_file' => '',
            'promo_image'      => '',

            // Marquee
            'marquee_words' => 'Web Development,UI / UX Design,Mobile Apps,Brand Identity,Backend APIs,Motion Design,Graphic Design,Integrations',

            // Statistics
            'stats_clients'  => '70+',
            'stats_projects' => '65+',
            'stats_reviews'  => '60+',
            'stats_revenue'  => '46+',

            // Social Media
            'social_instagram' => 'https://www.instagram.com/sk_artistic/',
            'social_whatsapp'  => 'https://chat.whatsapp.com/DtIJAyavExEJ1k98hNpxBa',
            'social_pinterest' => 'http://www.pinterest.com/Sk_Artistic/',
            'social_youtube'   => 'http://www.youtube.com/@SkArtisticc',
            'social_linkedin'  => '',
            'social_twitter'   => '',

            // SEO
            'meta_title'       => 'SK Artistic — Beyond Ordinary',
            'meta_description' => 'Full-cycle digital agency crafting websites, apps, and brands that dominate.',
            'og_image'         => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }


        // Theme defaults
        $theme = [
            'primary_color'   => '#6c2bd9',
            'secondary_color' => '#a855f7',
            'accent_color'    => '#f59e0b',
            'text_color'      => '#1f2937',
            'bg_color'        => '#ffffff',
            'dark_bg'         => '#0f0f1a',
            'font_family'     => 'Inter',
            'border_radius'   => '8px',
        ];

        foreach ($theme as $key => $value) {
            ThemeSetting::set($key, $value);
        }


        // Services
        $services = [
            ['title' => 'Website Development', 'slug' => 'website-development', 'icon' => '🌐', 'short_description' => 'Modern, responsive, and high-performing websites tailored to your business goals.'],
            ['title' => 'Mobile App Development', 'slug' => 'mobile-app-development', 'icon' => '📱', 'short_description' => 'User-friendly mobile applications for Android and iOS.'],
            ['title' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'icon' => '🎨', 'short_description' => 'Interfaces that look great and provide seamless user experiences.'],
            ['title' => 'Graphic Designing', 'slug' => 'graphic-designing', 'icon' => '✏️', 'short_description' => 'Stunning visuals that speak your brand\'s language.'],
            ['title' => 'Backend API Design', 'slug' => 'backend-api-design', 'icon' => '⚙️', 'short_description' => 'Robust, scalable backend systems and REST APIs.'],
            ['title' => 'Software Integration', 'slug' => 'software-integration', 'icon' => '🔗', 'short_description' => 'Seamless integration of third-party services and software.'],
            ['title' => 'Prototyping & Wireframing', 'slug' => 'prototyping-wireframing', 'icon' => '📐', 'short_description' => 'Rapid prototypes and wireframes to validate ideas.'],
            ['title' => 'Front-End Frameworks', 'slug' => 'front-end-frameworks', 'icon' => '💻', 'short_description' => 'React, Vue, Angular — we build fast frontends.'],
        ];

        foreach ($services as $i => $s) {
            Service::create(array_merge($s, [
                'is_published' => true,
                'sort_order'   => $i
            ]));
        }


        // Testimonials
        $testimonials = [
            ['client_name' => 'Ayesha Khan', 'client_position' => 'Marketing Manager', 'review' => 'SK Artistic completely elevated our brand presence. Their designs are innovative and reflect true professionalism.', 'rating' => 5],
            ['client_name' => 'Ahmed Raza', 'client_position' => 'Entrepreneur', 'review' => 'SK Artistic provided us with designs that truly stand out. The quality of their work is outstanding.', 'rating' => 5],
            ['client_name' => 'Hina Ali', 'client_position' => 'Business Owner', 'review' => 'Working with SK Artistic was effortless. They understood my requirements and delivered beyond my expectations.', 'rating' => 5],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create(array_merge($t, [
                'is_published' => true
            ]));
        }


        // FAQs
        $faqs = [
            ['question' => 'What services does SK Artistic offer?', 'answer' => 'SK Artistic provides full-service software development and graphic design solutions, including mobile apps, websites, branding, UI/UX design, and marketing materials.'],
            ['question' => 'Can SK Artistic build custom software for my business?', 'answer' => 'Absolutely! We specialize in creating custom software tailored to your business needs, from enterprise applications to small-scale solutions.'],
            ['question' => 'Do you provide graphic design and branding services?', 'answer' => 'Yes! Our team offers complete branding solutions including logos, brochures, social media graphics, UI/UX design, and visual identity packages.'],
            ['question' => 'How long does it take to complete a project?', 'answer' => 'Project timelines vary depending on complexity. Simple projects can take a few weeks, while larger projects may take several months.'],
            ['question' => 'Do you support clients after project completion?', 'answer' => 'Yes, SK Artistic offers ongoing support and maintenance to ensure your software and design assets remain up-to-date and fully functional.'],
        ];

        foreach ($faqs as $i => $f) {
            Faq::create(array_merge($f, [
                'is_published' => true,
                'sort_order'   => $i
            ]));
        }


        // Pricing Plans
        $plans = [
            ['name' => 'Starter Plan', 'badge' => 'Wireframe', 'tagline' => 'Beginner Web Development', 'price' => null, 'is_featured' => false, 'sort_order' => 0],
            ['name' => 'Most Popular', 'badge' => 'Prototype', 'tagline' => 'Advanced Web & App', 'price' => null, 'is_featured' => true, 'sort_order' => 1],
            ['name' => 'Professional', 'badge' => 'PixelMaster', 'tagline' => 'Expert Web & App', 'price' => null, 'is_featured' => false, 'sort_order' => 2],
        ];

        $planFeatures = [
            0 => ['Basic website development (5 pages)', 'Responsive UI/UX design', 'Basic graphic design', 'Basic SEO setup', 'Contact & inquiry forms', 'One website revision', 'Email support'],
            1 => ['Enterprise website development', 'Advanced interactive UI/UX', 'Mobile apps with API integration', 'Complete branding package', 'Advanced SEO & analytics', 'Multi-language support', 'Unlimited revisions', 'Priority support'],
            2 => ['Full website development (15 pages)', 'Advanced UI/UX & prototyping', 'Mobile app development', 'Custom branding & design', 'SEO optimization', 'Payment gateway integration', 'CMS integration', 'Two revisions', 'Email & chat support'],
        ];

        foreach ($plans as $i => $plan) {

            $p = PricingPlan::create(array_merge($plan, [
                'is_published' => true
            ]));

            foreach ($planFeatures[$i] as $j => $feature) {

                PricingFeature::create([
                    'pricing_plan_id' => $p->id,
                    'feature'         => $feature,
                    'included'        => true,
                    'sort_order'      => $j
                ]);

            }
        }

    }
}