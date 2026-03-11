@extends('admin.layouts.app')
@section('title', 'Audit Lead — ' . $lead->name)

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start;max-width:1000px">
    <div>
        <div class="card">
            <div class="card-head">
                <div class="card-title">🔍 Audit Request Details</div>
                <a href="{{ route('admin.audit-leads.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div style="display:grid;gap:0">
                    @foreach([
                        ['label'=>'Name','value'=>$lead->name,'bold'=>true],
                        ['label'=>'Email','value'=>$lead->email,'link'=>'mailto:'.$lead->email],
                        ['label'=>'Phone','value'=>$lead->phone,'link'=>'tel:'.$lead->phone],
                        ['label'=>'Website to Audit','value'=>$lead->website_url,'link'=>$lead->website_url,'ext'=>true],
                        ['label'=>'Business Type','value'=>$lead->business_type],
                        ['label'=>'Budget Range','value'=>$lead->budget_range],
                        ['label'=>'Submitted','value'=>$lead->created_at->format('F d, Y \a\t H:i')],
                    ] as $row)
                    @if(!empty($row['value']))
                    <div style="display:flex;align-items:baseline;gap:16px;padding:12px 0;border-bottom:1px solid #f8fafc">
                        <span style="width:120px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;flex-shrink:0">{{ $row['label'] }}</span>
                        @if(!empty($row['link']) && $row['value'])
                            <a href="{{ $row['link'] }}" style="color:var(--p);font-weight:600" {{ !empty($row['ext']) ? 'target="_blank"' : '' }}>{{ $row['value'] }}</a>
                        @elseif(!empty($row['bold']))
                            <span style="font-weight:700;font-size:1.1rem;color:#1e293b">{{ $row['value'] }}</span>
                        @else
                            <span style="color:#374151">{{ $row['value'] }}</span>
                        @endif
                    </div>
                    @endif
                    @endforeach

                    @if($lead->services_needed && count((array)$lead->services_needed))
                    <div style="padding:12px 0;border-bottom:1px solid #f8fafc">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-bottom:10px">Services Needed</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px">
                            @foreach((array)$lead->services_needed as $s)
                            <span style="background:#ede9fe;color:#5b21b6;padding:5px 12px;border-radius:6px;font-size:13px;font-weight:600">{{ $s }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($lead->goals)
                    <div style="padding:16px 0">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-bottom:10px">Goals & Requirements</div>
                        <div style="background:#f8fafc;border-radius:12px;padding:20px;line-height:1.8;color:#374151;font-size:15px;border-left:4px solid var(--p)">{{ $lead->goals }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚡ Quick Actions</div></div>
            <div class="card-body">
                <a href="mailto:{{ $lead->email }}?subject=Re: Your Free Website Audit Request" class="btn btn-primary" style="display:flex;width:100%;justify-content:center;margin-bottom:10px">
                    ✉️ Send Audit Email
                </a>
                @if($lead->phone)
                <a href="tel:{{ $lead->phone }}" class="btn btn-ghost" style="display:flex;width:100%;justify-content:center;margin-bottom:10px">
                    📞 {{ $lead->phone }}
                </a>
                @endif
                @if($lead->website_url)
                <a href="{{ $lead->website_url }}" target="_blank" class="btn btn-ghost" style="display:flex;width:100%;justify-content:center;font-family:monospace;font-size:12px">
                    🌐 Visit Website
                </a>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title" style="color:var(--danger)">🗑️ Danger Zone</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.audit-leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">Delete Lead</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection