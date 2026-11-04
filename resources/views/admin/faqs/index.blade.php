@extends('admin.layouts.app')
@section('title', 'FAQs')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
    <div>
        <p style="font-size:13px;color:var(--a-muted);margin:2px 0 0">{{ $faqs->count() }} question{{ $faqs->count() !== 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Add FAQ</a>
</div>


<div class="card">
    <div class="card-body" style="padding:0">
        @if($faqs->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:var(--a-dim)">
            <div style="font-size:3rem;margin-bottom:12px">❓</div>
            <p style="font-weight:600;margin:0">No FAQs yet</p>
            <p style="font-size:13px;margin:6px 0 20px">Add your first FAQ to get started</p>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Add FAQ</a>
        </div>
        @else
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-bottom:2px solid var(--a-soft)">
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--a-dim);text-transform:uppercase;letter-spacing:.5px;width:50px">#</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--a-dim);text-transform:uppercase;letter-spacing:.5px">Question</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--a-dim);text-transform:uppercase;letter-spacing:.5px;width:100px">Status</th>
                    <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--a-dim);text-transform:uppercase;letter-spacing:.5px;width:80px">Order</th>
                    <th style="padding:12px 20px;text-align:right;font-size:11px;font-weight:700;color:var(--a-dim);text-transform:uppercase;letter-spacing:.5px;width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr style="border-bottom:1px solid var(--a-soft);transition:background .15s" onmouseenter="this.style.background='var(--a-soft)'" onmouseleave="this.style.background=''">
                    <td style="padding:14px 20px;font-size:13px;color:var(--a-dim);font-weight:600">{{ $faq->sort_order }}</td>
                    <td style="padding:14px 20px">
                        <div style="font-size:14px;font-weight:600;color:var(--a-text);margin-bottom:3px">{{ Str::limit($faq->question, 80) }}</div>
                        <div style="font-size:12px;color:var(--a-dim)">{{ Str::limit(strip_tags($faq->answer), 100) }}</div>
                    </td>
                    <td style="padding:14px 20px">
                        @if($faq->is_published)
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--a-tint);color:var(--p);border-radius:20px;font-size:12px;font-weight:700">
                            <span style="width:6px;height:6px;border-radius:50%;background:var(--p)"></span> Published
                        </span>
                        @else
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--a-soft);color:var(--a-muted);border-radius:20px;font-size:12px;font-weight:700">
                            <span style="width:6px;height:6px;border-radius:50%;background:var(--a-line)"></span> Draft
                        </span>
                        @endif
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:var(--a-muted)">{{ $faq->sort_order }}</td>
                    <td style="padding:14px 20px;text-align:right">
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
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