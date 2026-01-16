@extends('layouts.guest')

@section('content')
    <h1 class="text-2xl font-semibold text-white">Create an account</h1>
    <p class="mt-2 text-sm text-slate-400">Join Tanda Blog with your email.</p>

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-400/60 bg-red-500/10 p-4 text-sm text-red-200">
            <ul class="list-disc space-y-1 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium text-slate-200" for="name">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                autocomplete="name"
                required
                value="{{ old('name') }}"
                class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white focus:border-indigo-400 focus:outline-none"
            >
        </div>
        <div>
            <label class="text-sm font-medium text-slate-200" for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                autocomplete="username"
                required
                value="{{ old('email') }}"
                class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white focus:border-indigo-400 focus:outline-none"
            >
        </div>
        <div>
            <label class="text-sm font-medium text-slate-200" for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
                class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white focus:border-indigo-400 focus:outline-none"
            >
        </div>
        <div>
            <label class="text-sm font-medium text-slate-200" for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
                class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white focus:border-indigo-400 focus:outline-none"
            >
        </div>
        <button
            type="submit"
            class="w-full rounded-lg bg-indigo-500 px-4 py-2 font-semibold text-white transition hover:bg-indigo-400"
        >
            Create account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-300 hover:text-indigo-200">Log in</a>
    </p>
@endsection
