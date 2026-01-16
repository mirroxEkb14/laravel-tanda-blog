@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-300 hover:text-indigo-200">
            ← Back to articles
        </a>
        @if ($article->publish_at)
            <span class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $article->publish_at->format('M d, Y') }}</span>
        @endif
    </div>

    <div class="mt-6">
        <h1 class="text-4xl font-semibold text-white">{{ $article->title }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-300">
            <span>By</span>
            @if ($article->author)
                <a href="{{ route('dashboard.authors.show', $article->author) }}" class="font-semibold text-indigo-300 hover:text-indigo-200">
                    {{ $article->author->name }}
                </a>
            @else
                <span class="text-slate-500">Unknown</span>
            @endif
        </div>
    </div>

    @if ($article->cover_image)
        <img
            src="{{ $article->cover_image }}"
            alt="{{ $article->title }}"
            class="mt-8 h-72 w-full rounded-2xl border border-slate-800 object-cover"
        >
    @endif

    <div class="article-content mt-8">
        {!! $article->content !!}
    </div>
@endsection
