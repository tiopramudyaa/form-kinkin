<?php
    $palette = \App\Support\SurveyTheme::palette($progress ?? 0);
    $floatEmoji = $floatEmoji ?? '⭐';
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Survey' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if ($palette['formal'])
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        @elseif ($palette['cute'])
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
        @endif
    </head>
    <body
        class="min-h-screen antialiased transition-colors duration-700"
        style="
            background: linear-gradient(135deg, {{ $palette['bg1'] }}, {{ $palette['bg2'] }});
            color: {{ $palette['text'] }};
            --accent: {{ $palette['accent'] }};
            --radius: {{ $palette['radius'] }}px;
            {{ $palette['formal'] ? "font-family: 'Roboto', ui-sans-serif, system-ui, sans-serif;" : ($palette['cute'] ? "font-family: 'Fredoka', ui-sans-serif, system-ui, sans-serif;" : '') }}
        "
    >
        @if (($progress ?? 0) > 0)
            <div class="fixed left-0 right-0 top-0 z-20 h-1 bg-black/5">
                <div class="h-full transition-all duration-500" style="width: {{ $progress }}%; background: var(--accent);"></div>
            </div>
        @endif

        @if ($palette['hearts'] > 0)
            <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
                @for ($i = 0; $i < $palette['hearts']; $i++)
                    <span
                        class="absolute select-none opacity-30"
                        style="left: {{ ($i * 37) % 100 }}%; top: {{ ($i * 53) % 100 }}%; font-size: {{ 18 + ($i % 3) * 8 }}px;"
                    >{{ $floatEmoji }}</span>
                @endfor
            </div>
        @endif

        <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            @stack('decor')
        </div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-xl">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:bg-white/90 sm:backdrop-blur"
                    style="border-radius: var(--radius); border: 1px solid color-mix(in srgb, var(--accent) 15%, {{ $palette['formal'] ? '#dadce0' : 'white' }});"
                >
                    @if ($palette['formal'])
                        <div style="height: 10px; background: var(--accent);"></div>
                    @endif

                    <div class="p-6 sm:p-10">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
