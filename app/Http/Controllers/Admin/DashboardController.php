<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Service;
use App\Models\BlogPost;
use App\Models\TeamMember;

class DashboardController extends Controller {
    public function index() {
        return view('admin.dashboard', [
            'contacts_count' => Contact::count(),
            'unread_contacts' => Contact::where('is_read', false)->count(),
            'services_count' => Service::count(),
            'posts_count' => BlogPost::count(),
            'team_count' => TeamMember::count(),
            'recent_contacts' => Contact::latest()->take(5)->get(),
        ]);
    }
}