<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Admin' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
        @if (session('admin_authenticated'))
            <header class="border-b border-gray-200 bg-white">
                <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4">
                    <a href="{{ route('admin.responses.index') }}" class="text-sm font-semibold">Admin &middot; Survey Responses</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-900">Keluar</button>
                    </form>
                </div>
            </header>
        @endif

        <main class="mx-auto max-w-5xl px-4 py-8">
            @yield('content')
        </main>
    </body>
</html>
