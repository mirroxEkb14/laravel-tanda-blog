<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen">
        <header class="border-b border-slate-800 bg-slate-950/80">
            <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-6">
                <a href="{{ route('dashboard') }}" class="text-2xl font-semibold text-white">
                    {{ config('app.name') }}
                </a>
                <div class="flex items-center gap-4 text-sm text-slate-200">
                    <span>Welcome, {{ auth()->user()->name ?? 'friend' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg border border-slate-700 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 transition hover:border-slate-500"
                        >
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <main class="mx-auto w-full max-w-6xl px-6 py-10">
            @yield('content')
        </main>
    </div>
</body>
</html>
