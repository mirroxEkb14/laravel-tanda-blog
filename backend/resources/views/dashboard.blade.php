@extends('layouts.guest')

@section('content')
    <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
    <p class="mt-2 text-sm text-slate-400">
        Welcome back, {{ auth()->user()->name ?? 'friend' }}.
    </p>

    @if (session('verified'))
        <div class="mt-4 rounded-lg border border-emerald-400/60 bg-emerald-500/10 p-4 text-sm text-emerald-200">
            Your email has been verified successfully.
        </div>
    @endif

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button
            type="submit"
            class="w-full rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-slate-500"
        >
            Log out
        </button>
    </form>
@endsection
