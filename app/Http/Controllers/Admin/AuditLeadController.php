<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLead;

class AuditLeadController extends Controller
{
    public function index()
    {
        $leads = AuditLead::latest()->paginate(25);
        return view('admin.audit-leads.index', compact('leads'));
    }

    public function show(AuditLead $lead)
    {
        $lead->update(['is_read' => true]);
        return view('admin.audit-leads.show', compact('lead'));
    }

    public function destroy(AuditLead $lead)
    {
        $lead->delete();
        return back()->with('success', 'Lead deleted!');
    }
}