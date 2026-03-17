@extends('admin.layouts.app')
@section('title', 'Pricing Plans')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0">💰 Pricing Plans</h2>
        <p style="font-size:13px;color:#64748b;margin:2px 0 0">{{ $plans->count() }} plan{{ $plans->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.pricing.create') }}" class="btn btn-primary">+ Add Plan</a>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:600">
    ✓ {{ session('success') }}
</div>
@endif

@if($plans->isEmpty())
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px 20px;color:#94a3b8">
        <div style="font-size:3rem;margin-bottom:12px">💰</div>
        <p style="font-weight:600;margin:0">No pricing plans yet</p>
        <p style="font-size:13px;margin:6px 0 20px">Create your first pricing plan</p>
        <a href="{{ route('admin.pricing.create') }}" class="btn btn-primary">+ Add Plan</a>
    </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
    @foreach($plans as $plan)
    <div class="card" style="position:relative;{{ $plan->is_featured ? 'border:2px solid #7c3aed;' : '' }}">
        @if($plan->is_featured)
        <div style="position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:#7c3aed;color:#fff;font-size:11px;font-weight:700;padding:3px 14px;border-radius:0 0 8px 8px;letter-spacing:.5px;white-space:nowrap">
            ⭐ FEATURED
        </div>
        @endif
        <div class="card-body" style="padding-top:{{ $plan->is_featured ? '28px' : '20px' }}">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px">
                <div>
                    <div style="font-size:16px;font-weight:800;color:#0f172a">{{ $plan->name }}</div>
                    @if($plan->badge)
                    <span style="display:inline-block;padding:2px 8px;background:#f3f0ff;color:#7c3aed;border-radius:20px;font-size:11px;font-weight:700;margin-top:4px">{{ $plan->badge }}</span>
                    @endif
                </div>
                @if($plan->is_published)
                <span style="padding:3px 10px;background:#f0fdf4;color:#16a34a;border-radius:20px;font-size:11px;font-weight:700">Live</span>
                @else
                <span style="padding:3px 10px;background:#f1f5f9;color:#64748b;border-radius:20px;font-size:11px;font-weight:700">Draft</span>
                @endif
            </div>

            <div style="font-size:26px;font-weight:900;color:#0f172a;margin-bottom:4px">
                @if($plan->price !== null)
                    ${{ number_format($plan->price, 0) }}<span style="font-size:14px;font-weight:500;color:#64748b">{{ $plan->price_suffix }}</span>
                @else
                    <span style="font-size:18px">Custom</span>
                @endif
            </div>
            @if($plan->description)
            <p style="font-size:13px;color:#64748b;margin:0 0 14px;line-height:1.5">{{ $plan->description }}</p>
            @endif

            <div style="border-top:1px solid #f1f5f9;padding-top:12px;margin-bottom:14px">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Features ({{ count($plan->features ?? []) }})</div>
                @foreach(array_slice($plan->features ?? [], 0, 4) as $feat)
                <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#334155;margin-bottom:4px">
                    <span style="color:#22c55e;font-size:12px">✓</span> {{ $feat }}
                </div>
                @endforeach
                @if(count($plan->features ?? []) > 4)
                <div style="font-size:12px;color:#94a3b8;margin-top:4px">+{{ count($plan->features) - 4 }} more</div>
                @endif
            </div>

            <div style="display:flex;gap:8px">
                <a href="{{ route('admin.pricing.edit', $plan) }}" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center">Edit</a>
                <form method="POST" action="{{ route('admin.pricing.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection