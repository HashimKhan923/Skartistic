<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PricingPlan;
use App\Models\PricingFeature;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // ADMIN USER
        // ================================================================
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@skartistic.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);


        // ================================================================
        // GENERAL SETTINGS
        // ================================================================
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


        // ================================================================
        // THEME DEFAULTS
        // ================================================================
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


        // ================================================================
        // SERVICES
        // ================================================================
Service::create([
    'title'             => 'Web Development',
    'slug'              => 'web-development',
    'icon'              => '🌐',
    'tag_label'         => 'Web Development',
    'hero_headline'     => 'Look no further for your web development needs',
    'hero_subtitle'     => 'We build fast, secure, and scalable websites using the latest technologies. From design to deployment, SK Artistic crafts digital experiences that drive results and delight users.',
    'hero_cta_primary'  => 'Get in touch',
    'hero_cta_secondary'=> 'Learn more',
 
    'offer_tag'      => 'What we offer',
    'offer_title'    => 'Tailored Web Solutions That Drive Results',
    'offer_subtitle' => 'From sleek websites to robust web applications, we deliver solutions crafted around your vision.',
    'offer_features' => [
        ['title' => 'Mobile Friendly',   'description' => 'Built for every screen size. Our web apps adapt perfectly to ensure smooth and intuitive user experiences on the go.', 'emoji' => '📱'],
        ['title' => 'Performance',       'description' => 'Blazing fast and optimized for speed — our code ensures seamless navigation and instant load times.', 'emoji' => '⚡'],
        ['title' => 'Search Engine Optimization', 'description' => 'Structured, optimized, and keyword-aware — we help your product get discovered and stay visible online.', 'emoji' => '🔍'],
        ['title' => 'Security',          'description' => 'Your data is safe with us. We build with strong encryption and best practices to ensure total peace of mind.', 'emoji' => '🛡️'],
    ],
 
    'techstack_tag'      => 'Tech Stack',
    'techstack_title'    => 'Technology Behind Every Pixel and Logic',
    'techstack_subtitle' => 'From battle-tested libraries to innovative frameworks, we build with technologies that bring efficiency to development and excellence to the user experience.',
    'tech_categories' => [
        ['name' => 'Frontend', 'items' => [
            ['name' => 'React.js', 'emoji' => '⚛️'],
            ['name' => 'Vue.js',   'emoji' => '💚'],
            ['name' => 'Next.js',  'emoji' => '▲'],
            ['name' => 'TypeScript', 'emoji' => '🔷'],
            ['name' => 'Tailwind CSS', 'emoji' => '🎨'],
        ]],
        ['name' => 'Backend', 'items' => [
            ['name' => 'Laravel',  'emoji' => '🔴'],
            ['name' => 'Node.js',  'emoji' => '🟢'],
            ['name' => 'Django',   'emoji' => '🐍'],
            ['name' => 'FastAPI',  'emoji' => '🚀'],
        ]],
        ['name' => 'Database', 'items' => [
            ['name' => 'MySQL',      'emoji' => '🗄️'],
            ['name' => 'PostgreSQL', 'emoji' => '🐘'],
            ['name' => 'MongoDB',    'emoji' => '🍃'],
            ['name' => 'Redis',      'emoji' => '🔴'],
        ]],
    ],
 
    'process_tag'      => 'Work Process',
    'process_title'    => 'Blueprints to Browsers — Our Web-Making Ritual',
    'process_subtitle' => 'We follow a clear, collaborative process — from discovery to deployment — to build modern, scalable websites that meet your goals and exceed expectations.',
    'process_steps' => [
        [
            'title'       => 'Discovery & Planning',
            'description' => 'We start by understanding your goals, challenges, and target audience. This phase involves stakeholder meetings, user research, and setting clear objectives.',
            'features'    => [
                ['icon' => '🎯', 'label' => 'Requirement Analysis'],
                ['icon' => '📋', 'label' => 'Project Scope Definition'],
                ['icon' => '📅', 'label' => 'Timeline & Milestone Planning'],
                ['icon' => '🤝', 'label' => 'Kickoff Meeting'],
            ],
        ],
        [
            'title'       => 'UI/UX Design',
            'description' => 'Our designers craft intuitive and accessible user experiences that reflect your brand identity while enhancing usability across devices.',
            'features'    => [
                ['icon' => '✏️', 'label' => 'Wireframing & Prototyping'],
                ['icon' => '📱', 'label' => 'Responsive Layouts'],
                ['icon' => '🎨', 'label' => 'Brand-consistent Design'],
                ['icon' => '🔄', 'label' => 'User Testing Feedback Loops'],
            ],
        ],
        [
            'title'       => 'Development',
            'description' => 'Using modern tech stacks, we bring your vision to life with clean, scalable, and high-performance code.',
            'features'    => [
                ['icon' => '🔗', 'label' => 'Frontend & Backend Integration'],
                ['icon' => '🔐', 'label' => 'Authentication & Authorization'],
                ['icon' => '🌐', 'label' => 'CMS Integration'],
                ['icon' => '⚡', 'label' => 'Performance Optimization'],
            ],
        ],
        [
            'title'       => 'Testing & QA',
            'description' => 'We rigorously test every component and flow to ensure cross-browser compatibility, accessibility, and bug-free performance.',
            'features'    => [
                ['icon' => '🧪', 'label' => 'Unit & Integration Testing'],
                ['icon' => '🌐', 'label' => 'Cross-browser Testing'],
                ['icon' => '♿', 'label' => 'Accessibility Audit'],
                ['icon' => '🐛', 'label' => 'Bug Fixing & QA'],
            ],
        ],
    ],
 
    'work_tag'      => 'Featured Work',
    'work_title'    => 'From First Sketch to Final Launch — Our Magic in Motion',
    'work_subtitle' => 'Our web development process blends creativity, strategy, and engineering to turn your ideas into fast, scalable, and user-loved digital products.',
    'featured_projects' => [
        [
            'title'           => 'A Community Hub For AI Enthusiasts',
            'description'     => 'The platform was designed to democratize AI education and foster innovation through enhanced student engagement and collaborative opportunities.',
            'client'          => 'Stanford University AI Club',
            'role'            => 'UX/UI Designer & Frontend Developer',
            'year'            => '2024',
            'duration'        => '6 months',
            'status'          => 'Completed',
            'features'        => ['Admin Dashboard', 'Events Management', 'Club Member Management', 'Student Engagement'],
            'live_url'        => '#',
            'case_study_url'  => '#',
        ],
    ],
 
    'cta_title'    => 'Ready to Start Your Web Project?',
    'cta_subtitle' => "Let's build something extraordinary together.",
    'is_published' => true,
    'sort_order'   => 1,
]);


        // ================================================================
        // TESTIMONIALS
        // ================================================================
        $testimonials = [
            ['client_name' => 'Ayesha Khan',  'client_position' => 'Marketing Manager', 'review' => 'SK Artistic completely elevated our brand presence. Their designs are innovative and reflect true professionalism.', 'rating' => 5],
            ['client_name' => 'Ahmed Raza',   'client_position' => 'Entrepreneur',       'review' => 'SK Artistic provided us with designs that truly stand out. The quality of their work is outstanding.',            'rating' => 5],
            ['client_name' => 'Hina Ali',     'client_position' => 'Business Owner',     'review' => 'Working with SK Artistic was effortless. They understood my requirements and delivered beyond my expectations.', 'rating' => 5],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create(array_merge($t, ['is_published' => true]));
        }


        // ================================================================
        // FAQs
        // Your existing Faq model uses: is_published, sort_order (no section column)
        // About-page FAQs are stored separately via the faqs migration that adds
        // a nullable `section` column — if you run that migration they auto-filter;
        // if not, all FAQs show everywhere and that's fine too.
        // ================================================================
        $faqs = [
            // ----- General / Home FAQs (your originals) -----
            [
                'question'     => 'What services does SK Artistic offer?',
                'answer'       => 'SK Artistic provides full-service software development and graphic design solutions, including mobile apps, websites, branding, UI/UX design, and marketing materials.',
                'is_published' => true,
                'sort_order'   => 0,
            ],
            [
                'question'     => 'Can SK Artistic build custom software for my business?',
                'answer'       => 'Absolutely! We specialize in creating custom software tailored to your business needs, from enterprise applications to small-scale solutions.',
                'is_published' => true,
                'sort_order'   => 1,
            ],
            [
                'question'     => 'Do you provide graphic design and branding services?',
                'answer'       => 'Yes! Our team offers complete branding solutions including logos, brochures, social media graphics, UI/UX design, and visual identity packages.',
                'is_published' => true,
                'sort_order'   => 2,
            ],
            [
                'question'     => 'How long does it take to complete a project?',
                'answer'       => 'Project timelines vary depending on complexity. Simple projects can take a few weeks, while larger projects may take several months.',
                'is_published' => true,
                'sort_order'   => 3,
            ],
            [
                'question'     => 'Do you support clients after project completion?',
                'answer'       => 'Yes, SK Artistic offers ongoing support and maintenance to ensure your software and design assets remain up-to-date and fully functional.',
                'is_published' => true,
                'sort_order'   => 4,
            ],

            // ----- About-Page FAQs (new) -----
            [
                'question'     => 'What is SK Artistic?',
                'answer'       => 'SK Artistic is a full-cycle digital agency specializing in web design, development, branding, and software solutions. We help businesses of all sizes build powerful digital products.',
                'is_published' => true,
                'sort_order'   => 5,
            ],
            [
                'question'     => 'How does SK Artistic approach projects?',
                'answer'       => 'We follow a structured process: Discovery → Strategy → Design → Development → QA → Launch → Support. Every project starts with understanding your goals and ends with measurable results.',
                'is_published' => true,
                'sort_order'   => 6,
            ],
            [
                'question'     => 'What makes SK Artistic different from other agencies?',
                'answer'       => 'We treat your business like our own. We combine strategy, design, and technology into one seamless process — and we never ship something we wouldn\'t put our name on.',
                'is_published' => true,
                'sort_order'   => 7,
            ],
            [
                'question'     => 'How long does a typical project take?',
                'answer'       => 'A standard website takes 4–8 weeks, while larger applications can take 3–6 months. We provide a clear timeline during the discovery phase.',
                'is_published' => true,
                'sort_order'   => 8,
            ],
            [
                'question'     => 'Do you offer ongoing support after launch?',
                'answer'       => 'Yes. We offer monthly maintenance and support packages to keep your product updated, secure, and performing at its best.',
                'is_published' => true,
                'sort_order'   => 9,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::create($f);
        }


        // ================================================================
        // PRICING PLANS
        // ================================================================
        $plans = [
            ['name' => 'Starter Plan',  'badge' => 'Wireframe',   'tagline' => 'Beginner Web Development', 'price' => null, 'is_featured' => false, 'sort_order' => 0],
            ['name' => 'Most Popular',  'badge' => 'Prototype',   'tagline' => 'Advanced Web & App',       'price' => null, 'is_featured' => true,  'sort_order' => 1],
            ['name' => 'Professional',  'badge' => 'PixelMaster', 'tagline' => 'Expert Web & App',         'price' => null, 'is_featured' => false, 'sort_order' => 2],
        ];

        $planFeatures = [
            0 => ['Basic website development (5 pages)', 'Responsive UI/UX design', 'Basic graphic design', 'Basic SEO setup', 'Contact & inquiry forms', 'One website revision', 'Email support'],
            1 => ['Enterprise website development', 'Advanced interactive UI/UX', 'Mobile apps with API integration', 'Complete branding package', 'Advanced SEO & analytics', 'Multi-language support', 'Unlimited revisions', 'Priority support'],
            2 => ['Full website development (15 pages)', 'Advanced UI/UX & prototyping', 'Mobile app development', 'Custom branding & design', 'SEO optimization', 'Payment gateway integration', 'CMS integration', 'Two revisions', 'Email & chat support'],
        ];

        foreach ($plans as $i => $plan) {
            $p = PricingPlan::create(array_merge($plan, ['is_published' => true]));

            foreach ($planFeatures[$i] as $j => $feature) {
                PricingFeature::create([
                    'pricing_plan_id' => $p->id,
                    'feature'         => $feature,
                    'included'        => true,
                    'sort_order'      => $j,
                ]);
            }
        }


        // ================================================================
        // ABOUT PAGE — single row
        // ================================================================
        DB::table('abouts')->insertOrIgnore([
            // Hero
            'hero_tag'               => 'About us',
            'hero_title'             => "Building Tomorrow's Digital Frontier",
            'hero_subtitle'          => 'We are a team of developers who are passionate about building beautiful and functional websites and mobile apps.',

            // Mission
            'mission_title'          => 'Our mission',
            'mission_text_1'         => "At SK Artistic, we're committed to revolutionizing the way businesses harness technology. Our mission is to arm our clients with a decisive advantage over their competition through innovative, custom-built software solutions that break the mold. We stop at nothing to deliver the tools and insights you need to lead your market.",
            'mission_text_2'         => "We're fanatical about our craft — investing the time to decode every nuance of your business so we understand your challenges better than you do. We stand shoulder-to-shoulder with you, because in today's digital arena, every breakthrough is a shared secret. In our journey, we've built an unbreakable bond with our clients; when one of us triumphs, we all rise.",

            // Stats
            'stats_label'            => 'THE NUMBERS',
            'stat_clients_num'       => '70+',
            'stat_clients_label'     => 'Satisfied Clients',
            'stat_projects_num'      => '65+',
            'stat_projects_label'    => 'Projects',
            'stat_satisfaction_num'  => '99.5%',
            'stat_satisfaction_label'=> 'Satisfaction Rate',
            'stat_experience_num'    => '5+',
            'stat_experience_label'  => 'Years of Experience',

            // Milestones
            'milestones_tag'         => 'Milestones That Matter',
            'milestones_title'       => 'Our Journey of Impact',
            'milestones_subtitle'    => "From startups to enterprises, we've empowered businesses through innovative software solutions and transformative results.",

            // Core Values
            'values_tag'             => 'What Drives Us',
            'values_title'           => 'Our Core Values',
            'values_subtitle'        => 'At the heart of SK Artistic lies a commitment to innovation, integrity, collaboration, and client success — principles that guide every line of code we write.',

            // FAQ
            'faq_tag'                => 'FAQ',
            'faq_title'              => 'Frequently Asked Questions About SK Artistic',
            'faq_subtitle'           => 'Learn more about our design agency, our services, and how we can help bring your digital vision to life.',

            'created_at'             => now(),
            'updated_at'             => now(),
        ]);


        // ================================================================
        // AWARDS
        // ================================================================
        $awards = [
            ['Clutch',        'Making Waves on the World Stage', '2024', 1],
            ['techreviewer',  'Top Rising Company',              '2025', 2],
            ['DESIGNRUSH',    'Where Vision Meets Code',         '2024', 3],
            ['TopDevelopers', 'Ranked Among the Top',            '2025', 4],
            ['GoodFirms',     'Enduring Excellence',             '2024', 5],
        ];

        foreach ($awards as $a) {
            DB::table('awards')->insertOrIgnore([
                'platform'    => $a[0],
                'achievement' => $a[1],
                'year'        => $a[2],
                'sort_order'  => $a[3],
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }


        // ================================================================
        // CORE VALUES
        // ================================================================
        $values = [
            ['🚀', 'Be world-class',            'We hold ourselves to the highest standards in every project — delivering exceptional design, architecture, and performance to help our clients lead in their industries.',  1],
            ['🤝', 'Take responsibility',        'We own our work, honor our commitments, and proactively solve problems — ensuring reliability, trust, and excellence at every stage of development.',                      2],
            ['👥', 'Be supportive',              'We thrive as a team. By uplifting each other and our clients, we create an environment where collaboration leads to innovation.',                                          3],
            ['📖', 'Always learning',            'Technology evolves fast — and so do we. Continuous learning and curiosity fuel our growth and keep us ahead of the curve.',                                               4],
            ['💡', 'Share everything you know',  'We believe in open knowledge. By sharing insights, we empower our team, uplift our partners, and strengthen the software community.',                                    5],
            ['☀️', 'Enjoy downtime',             'Great ideas need fresh minds. We respect balance and believe rest is essential for creativity, clarity, and sustainable excellence.',                                     6],
        ];

        foreach ($values as $v) {
            DB::table('core_values')->insertOrIgnore([
                'icon'        => $v[0],
                'title'       => $v[1],
                'description' => $v[2],
                'sort_order'  => $v[3],
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}