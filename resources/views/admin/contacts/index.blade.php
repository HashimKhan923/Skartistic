@extends('admin.layouts.app')
@section('title', 'Message from ' . $contact->name)

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start;max-width:900px">
    <div>
        <div class="card">
            <div class="card-head">
                <div class="card-title">📬 Message Details</div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <div style="display:grid;gap:12px;margin-bottom:28px">
                    @foreach([['label'=>'From','value'=>$contact->name,'bold'=>true],['label'=>'Email','value'=>$contact->email,'link'=>'mailto:'.$contact->email],['label'=>'Phone','value'=>$contact->phone],['label'=>'Subject','value'=>$contact->subject],['label'=>'Received','value'=>$contact->created_at->format('F d, Y \a\t H:i')]] as $row)
                    @if($row['value'])
                    <div style="display:flex;align-items:baseline;gap:16px;padding:10px 0;border-bottom:1px solid #f8fafc">
                        <span style="width:80px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;flex-shrink:0">{{ $row['label'] }}</span>
                        @if(isset($row['link']))<a href="{{ $row['link'] }}" style="color:var(--p);font-weight:600">{{ $row['value'] }}</a>@elseif(isset($row['bold']))<span style="font-weight:700;color:#1e293b;font-size:16px">{{ $row['value'] }}</span>@else<span style="color:#374151">{{ $row['value'] }}</span>@endif
                    </div>
                    @endif
                    @endforeach
                </div>
                <div style="background:#f8fafc;border:1px solid #e8ecf0;border-radius:12px;padding:24px;line-height:1.85;color:#374151;font-size:15px">
                    {{ $contact->message }}
                </div>
            </div>
        </div>
    </div>
    <div style="position:sticky;top:92px;display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-head"><div class="card-title">⚡ Quick Reply</div></div>
            <div class="card-body">
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject ?? 'Your inquiry' }}" class="btn btn-primary" style="display:flex;width:100%;justify-content:center;margin-bottom:10px">
                    ✉️ Reply via Email
                </a>
                @if($contact->phone)
                <a href="tel:{{ $contact->phone }}" class="btn btn-ghost" style="display:flex;width:100%;justify-content:center">
                    📞 Call {{ $contact->phone }}
                </a>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-head"><div class="card-title">🗑️ Danger Zone</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Permanently delete this message?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">Delete Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection