@extends('layouts.guest')

@section('content')
    <h1 class="text-2xl font-semibold text-white">Verify your email</h1>
    <p class="mt-2 text-sm text-slate-400">
        We have sent a verification link to your email address. Please click the link to activate your account.
    </p>

    @if (session('status') === 'verification-link-sent')
        <div class="mt-4 rounded-lg border border-emerald-400/60 bg-emerald-500/10 p-4 text-sm text-emerald-200">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button
            type="submit"
            class="w-full rounded-lg bg-indigo-500 px-4 py-2 font-semibold text-white transition hover:bg-indigo-400"
        >
            Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button
            type="submit"
            class="w-full rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-slate-500"
        >
            Log out
        </button>
    </form>
@endsection
