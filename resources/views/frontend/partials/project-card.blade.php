<a href="{{ route('portfolio.detail', $project->slug) }}" class="project-card" data-cursor="View">
  <div class="project-media">
    @if($project->thumbnail)
      <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}" loading="lazy">
    @else
      <div class="project-ph" aria-hidden="true">{{ strtoupper(substr($project->title, 0, 1)) }}</div>
    @endif
  </div>
  <div class="project-body">
    @if($project->category)<span class="project-cat">{{ $project->category }}</span>@endif
    <h3 class="project-title">{{ $project->title }}</h3>
    @if($project->description)<p class="project-desc">{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 110) }}</p>@endif
    <span class="project-go">View project <i aria-hidden="true"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg></i></span>
  </div>
</a>
