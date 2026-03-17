@extends('admin.layouts.app')
@section('title', 'Careers')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0">💼 Careers</h2>
        <p style="font-size:13px;color:#64748b;margin:2px 0 0">{{ $careers->count() }} job posting{{ $careers->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.careers.create') }}" class="btn btn-primary">+ Post Job</a>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:600">
    ✓ {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body" style="padding:0">
        @if($careers->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:#94a3b8">
            <div style="font-size:3rem;margin-bottom:12px">💼</div>
            <p style="font-weight:600;margin:0">No job postings yet</p>
            <p style="font-size:13px;margin:6px 0 20px">Post your first job opening</p>
            <a href="{{ route('admin.careers.create') }}" class="btn btn-primary">+ Post Job</a>
        </div>
        @else
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-bottom:2px solid #f1f5f9">
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px">Position</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;width:120px">Department</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;width:100px">Type</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;width:100px">Deadline</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;width:100px">Status</th>
                    <th style="padding:12px 20px;text-align:right;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($careers as $career)
                <tr style="border-bottom:1px solid #f8fafc;transition:background .15s" onmouseenter="this.style.background='#fafbff'" onmouseleave="this.style.background=''">
                    <td style="padding:14px 20px">
                        <div style="font-size:14px;font-weight:700;color:#0f172a">{{ $career->title }}</div>
                        <div style="display:flex;gap:8px;margin-top:4px;flex-wrap:wrap">
                            @if($career->location)
                            <span style="font-size:12px;color:#64748b">📍 {{ $career->location }}</span>
                            @endif
                            @if($career->experience)
                            <span style="font-size:12px;color:#64748b">⏱ {{ $career->experience }}</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:14px 20px">
                        @if($career->department)
                        <span style="display:inline-block;padding:3px 10px;background:#f1f5f9;color:#475569;border-radius:6px;font-size:12px;font-weight:600">{{ $career->department }}</span>
                        @else
                        <span style="color:#cbd5e1;font-size:13px">—</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px">
                        <span style="display:inline-block;padding:3px 10px;background:#eff6ff;color:#3b82f6;border-radius:6px;font-size:12px;font-weight:600">{{ $career->type }}</span>
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#64748b">
                        @if($career->deadline)
                            @if($career->deadline->isPast())
                            <span style="color:#ef4444;font-weight:600">{{ $career->deadline->format('M d, Y') }}</span>
                            @else
                            {{ $career->deadline->format('M d, Y') }}
                            @endif
                        @else
                        <span style="color:#cbd5e1">Open</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px">
                        @if($career->is_published)
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#f0fdf4;color:#16a34a;border-radius:20px;font-size:12px;font-weight:700">
                            <span style="width:6px;height:6px;border-radius:50%;background:#22c55e"></span> Live
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#f1f5f9;color:#64748b;border-radius:20px;font-size:12px;font-weight:700">
                            <span style="width:6px;height:6px;border-radius:50%;background:#cbd5e1"></span> Draft
                        </span>
                        @endif
                    </td>
                    <td style="padding:14px 20px;text-align:right">
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('admin.careers.edit', $career) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.careers.destroy', $career) }}" onsubmit="return confirm('Delete this job posting?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection