@extends('admin.layouts.app')
@section('title','Media Library')

@section('styles')
<style>
.media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px}
.media-card{border:1.5px solid #e8ecf0;border-radius:12px;overflow:hidden;cursor:pointer;transition:all .2s;position:relative;background:#fff}
.media-card:hover{border-color:rgba(124,58,237,.4);box-shadow:0 8px 25px rgba(124,58,237,.1);transform:translateY(-2px)}
.media-card.selected{border-color:var(--p);box-shadow:0 0 0 3px rgba(124,58,237,.15)}
.media-thumb{height:130px;overflow:hidden;background:#f8fafc;display:flex;align-items:center;justify-content:center;position:relative}
.media-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.media-card:hover .media-thumb img{transform:scale(1.05)}
.media-check{position:absolute;top:8px;right:8px;width:22px;height:22px;border-radius:50%;background:var(--p);display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;opacity:0;transition:opacity .2s}
.media-card.selected .media-check{opacity:1}
.media-info{padding:10px 12px}
.media-name{font-size:11px;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.media-size{font-size:10px;color:#94a3b8;margin-top:2px}
.media-type-icon{font-size:2.5rem}
.upload-zone{border:2px dashed #e2e8f0;border-radius:16px;padding:48px;text-align:center;transition:all .3s;cursor:pointer;background:#fafafa}
.upload-zone:hover,.upload-zone.dragover{border-color:var(--p);background:rgba(124,58,237,.04)}
.upload-zone-icon{font-size:3rem;margin-bottom:12px;display:block}
.folder-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.folder-tab{padding:7px 18px;border-radius:999px;font-size:13px;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:#fff;color:#64748b;transition:all .2s}
.folder-tab.active{background:linear-gradient(135deg,var(--p),var(--s));color:#fff;border-color:transparent}
.copy-url-btn{font-size:10px;padding:3px 8px;border-radius:5px;background:rgba(124,58,237,.1);color:var(--p);border:none;cursor:pointer;font-weight:600;transition:background .2s;display:block;width:100%;margin-top:4px;text-align:center}
.copy-url-btn:hover{background:rgba(124,58,237,.2)}
</style>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 280px;gap:24px;align-items:start">
    <div>
        {{-- Upload zone --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-head"><div class="card-title">📤 Upload Files</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                        <span class="upload-zone-icon">☁️</span>
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#374151;margin-bottom:6px">Drop files here or click to browse</div>
                        <div style="font-size:13px;color:#94a3b8">Supports: JPG, PNG, GIF, SVG, WebP, PDF (Max 10MB)</div>
                        <input type="file" id="fileInput" name="files[]" multiple accept="image/*,.pdf,.svg" style="display:none" onchange="previewFiles(this)">
                    </div>
                    <div id="preview-list" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:16px"></div>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:16px">
                        <select name="folder" class="form-control" style="max-width:200px">
                            <option value="general">📁 General</option>
                            <option value="services">⚡ Services</option>
                            <option value="blog">✏️ Blog</option>
                            <option value="team">👥 Team</option>
                            <option value="portfolio">🎨 Portfolio</option>
                            <option value="logos">🏢 Client Logos</option>
                        </select>
                        <button type="submit" class="btn btn-primary" id="uploadBtn" style="display:none">
                            ⬆️ Upload Files
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Folder filter --}}
        <div class="folder-tabs">
            <button class="folder-tab {{ !request('folder')?'active':'' }}" onclick="location='{{ route('admin.media.index') }}'">All Files</button>
            @foreach(['general','services','blog','team','portfolio','logos'] as $folder)
            <button class="folder-tab {{ request('folder')===$folder?'active':'' }}" onclick="location='{{ route('admin.media.index') }}?folder={{ $folder }}'">
                {{ ucfirst($folder) }}
            </button>
            @endforeach
        </div>

        {{-- Media Grid --}}
        @if($files->count())
        <div class="card">
            <div class="card-head">
                <div class="card-title">🖼️ Files <span style="background:#ede9fe;color:#5b21b6;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;margin-left:8px">{{ $files->total() }}</span></div>
                <div style="display:flex;gap:8px">
                    <input type="text" placeholder="Search files..." style="padding:8px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;outline:none;font-family:'DM Sans',sans-serif;width:200px" oninput="filterMedia(this.value)">
                </div>
            </div>
            <div class="card-body">
                <div class="media-grid" id="mediaGrid">
                    @foreach($files as $file)
                    <div class="media-card" data-name="{{ strtolower($file->name) }}" onclick="selectMedia(this, '{{ $file->url }}', '{{ $file->name }}')">
                        <div class="media-check">✓</div>
                        <div class="media-thumb">
                            @if(str_starts_with($file->mime_type, 'image/'))
                                <img src="{{ $file->url }}" alt="{{ $file->name }}" loading="lazy">
                            @elseif($file->mime_type === 'application/pdf')
                                <div class="media-type-icon">📄</div>
                            @else
                                <div class="media-type-icon">📁</div>
                            @endif
                        </div>
                        <div class="media-info">
                            <div class="media-name" title="{{ $file->name }}">{{ $file->name }}</div>
                            <div class="media-size">{{ $file->size_formatted }}</div>
                            <button class="copy-url-btn" onclick="event.stopPropagation();copyUrl('{{ $file->url }}',this)">Copy URL</button>
                        </div>
                        <form method="POST" action="{{ route('admin.media.destroy', $file) }}" onsubmit="return confirm('Delete this file?')" style="position:absolute;top:8px;left:8px;opacity:0;transition:opacity .2s" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:22px;height:22px;border-radius:50%;background:rgba(239,68,68,.9);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center">×</button>
                        </form>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:20px">{{ $files->appends(request()->query())->links() }}</div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="empty-state">
                <span class="empty-state-icon">🖼️</span>
                <p>No files uploaded yet. Use the upload zone above to add your first files.</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Selected file panel --}}
    <div style="position:sticky;top:92px">
        <div class="card" id="selectedPanel" style="display:none">
            <div class="card-head"><div class="card-title">📋 File Details</div></div>
            <div class="card-body">
                <div id="selectedPreview" style="width:100%;height:180px;border-radius:10px;overflow:hidden;background:#f8fafc;margin-bottom:16px;display:flex;align-items:center;justify-content:center;font-size:4rem"></div>
                <div id="selectedName" style="font-weight:600;font-size:15px;color:#1e293b;margin-bottom:4px;word-break:break-all"></div>
                <div id="selectedSize" style="font-size:12px;color:#94a3b8;margin-bottom:16px"></div>
                <div style="background:#f8fafc;border-radius:10px;padding:12px;font-size:12px;color:#374151;word-break:break-all;font-family:monospace;margin-bottom:14px" id="selectedUrl"></div>
                <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="copySelectedUrl()">
                    📋 Copy URL
                </button>
            </div>
        </div>
        <div class="card" style="margin-top:16px">
            <div class="card-head"><div class="card-title">📊 Storage</div></div>
            <div class="card-body">
                <div style="font-family:'Syne',sans-serif;font-size:2rem;font-weight:800;color:var(--p)">{{ $files->total() }}</div>
                <div style="font-size:13px;color:#64748b;margin-top:4px">Total files</div>
                <div style="margin-top:16px;font-size:13px;color:#374151">
                    @foreach($folderCounts as $folder=>$count)
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f8fafc">
                        <span>{{ ucfirst($folder) }}</span>
                        <span style="font-weight:600;color:var(--p)">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectMedia(card, url, name) {
    document.querySelectorAll('.media-card').forEach(c=>c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('selectedPanel').style.display='block';
    const preview=document.getElementById('selectedPreview');
    if(url.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)){
        preview.innerHTML='<img src="'+url+'" style="width:100%;height:100%;object-fit:contain">';
    } else {
        preview.innerHTML='<span style="font-size:4rem">📄</span>';
    }
    document.getElementById('selectedName').textContent=name;
    document.getElementById('selectedUrl').textContent=url;
    window._selectedUrl=url;
}
function copySelectedUrl(){
    if(window._selectedUrl){navigator.clipboard.writeText(window._selectedUrl).then(()=>alert('URL copied!'));}
}
function copyUrl(url,btn){
    navigator.clipboard.writeText(url).then(()=>{btn.textContent='Copied!';setTimeout(()=>btn.textContent='Copy URL',2000);});
}
function filterMedia(q){
    document.querySelectorAll('.media-card').forEach(c=>{
        c.style.display=c.dataset.name.includes(q.toLowerCase())?'':'none';
    });
}
function previewFiles(input){
    const list=document.getElementById('preview-list');
    list.innerHTML='';
    document.getElementById('uploadBtn').style.display='inline-flex';
    Array.from(input.files).forEach(f=>{
        const div=document.createElement('div');
        div.style='border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px;background:#f8fafc';
        div.innerHTML='<span>📎</span>'+f.name.substring(0,30)+(f.name.length>30?'...':'')+'<span style="color:#94a3b8">'+(f.size>1048576?(f.size/1048576).toFixed(1)+'MB':(f.size/1024).toFixed(0)+'KB')+'</span>';
        list.appendChild(div);
    });
}
const zone=document.getElementById('uploadZone');
['dragover','dragenter'].forEach(e=>zone.addEventListener(e,ev=>{ev.preventDefault();zone.classList.add('dragover');}));
['dragleave','drop'].forEach(e=>zone.addEventListener(e,ev=>{ev.preventDefault();zone.classList.remove('dragover');if(ev.type==='drop'&&ev.dataTransfer.files.length){document.getElementById('fileInput').files=ev.dataTransfer.files;previewFiles(document.getElementById('fileInput'));}}));
document.querySelectorAll('.media-card').forEach(c=>{c.addEventListener('mouseenter',()=>c.querySelector('.delete-form').style.opacity='1');c.addEventListener('mouseleave',()=>c.querySelector('.delete-form').style.opacity='0');});
</script>
@endsection