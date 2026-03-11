<style>

:root {
    --primary: {{ $theme['primary_color'] ?? '#6c2bd9' }};
    --secondary: {{ $theme['secondary_color'] ?? '#a855f7' }};
    --accent: {{ $theme['accent_color'] ?? '#f59e0b' }};
    --text: {{ $theme['text_color'] ?? '#1f2937' }};
    --bg: {{ $theme['bg_color'] ?? '#ffffff' }};
    --dark-bg: {{ $theme['dark_bg'] ?? '#0f0f1a' }};
    --radius: {{ $theme['border_radius'] ?? '8px' }};
    --font: '{{ $theme['font_family'] ?? 'Inter' }}', sans-serif;
}

</style>