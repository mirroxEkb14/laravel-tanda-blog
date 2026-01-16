<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="flex min-h-screen items-center justify-center px-6 py-12">
        <div class="w-full max-w-md rounded-2xl border border-slate-800 bg-slate-900/80 p-8 shadow-xl">
            <a href="/" class="text-2xl font-semibold text-white">
                {{ config('app.name') }}
            </a>
            <div class="mt-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
