@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-300 hover:text-indigo-200">
            ← Back to articles
        </a>
        <span class="text-xs uppercase tracking-[0.2em] text-slate-400">Author profile</span>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
        <h1 class="text-3xl font-semibold text-white">{{ $author->name }}</h1>
        <p class="mt-2 text-sm text-slate-400">Published articles by {{ $author->name }}.</p>
    </div>

    <div class="mt-8 space-y-6">
        @forelse ($articles as $article)
            <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 shadow-lg">
                <div class="flex flex-wrap items-center gap-3 text-xs uppercase tracking-[0.2em] text-slate-400">
                    <span>Published</span>
                    @if ($article->publish_at)
                        <span>• {{ $article->publish_at->format('M d, Y') }}</span>
                    @endif
                </div>
                <h2 class="mt-3 text-2xl font-semibold text-white">
                    <a href="{{ route('dashboard.articles.show', $article->slug) }}" class="transition hover:text-indigo-300">
                        {{ $article->title }}
                    </a>
                </h2>
                <p class="mt-2 text-sm text-slate-300">
                    {{ $article->excerpt }}
                </p>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-sm text-slate-400">
                This author has no published articles yet.
            </div>
        @endforelse
    </div>
@endsection
