@extends('admin.layouts.app')
@section('title','Pages')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">📄 Custom Pages</div>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">+ Add Page</a>
    </div>
    <table>
        <thead><tr><th>Title</th><th>Slug</th><th>In Menu</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($pages as $p)
        <tr>
            <td><strong>{{ $p->title }}</strong></td>
            <td>/{{ $p->slug }}</td>
            <td><span class="badge {{ $p->show_in_menu ? 'badge-blue' : 'badge-red' }}">{{ $p->show_in_menu ? 'Yes' : 'No' }}</span></td>
            <td><span class="badge {{ $p->is_published ? 'badge-green' : 'badge-red' }}">{{ $p->is_published ? 'Published' : 'Draft' }}</span></td>
            <td>
                <a href="{{ route('admin.pages.show', $p->slug) }}" target="_blank" class="btn btn-secondary btn-sm">View</a>
                <a href="{{ route('admin.pages.edit', $p) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.pages.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection