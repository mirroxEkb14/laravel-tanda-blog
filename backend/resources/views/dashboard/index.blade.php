@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-semibold text-white">Published articles</h1>
        <p class="text-sm text-slate-400">Explore all published articles and open any story to read it in full.</p>
    </div>

    @if (session('verified'))
        <div class="mt-6 rounded-lg border border-emerald-400/60 bg-emerald-500/10 p-4 text-sm text-emerald-200">
            Your email has been verified successfully.
        </div>
    @endif

    <div class="mt-8 space-y-6">
        @forelse ($articles as $article)
            <article class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 shadow-lg">
                @if ($article->cover_image)
                    <img
                        src="{{ $article->cover_image }}"
                        alt="{{ $article->title }}"
                        class="h-48 w-full rounded-xl border border-slate-800 object-cover"
                    >
                @endif
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
                <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-slate-400">
                    <span>Author:</span>
                    @if ($article->author)
                        <a href="{{ route('dashboard.authors.show', $article->author) }}" class="font-semibold text-indigo-300 hover:text-indigo-200">
                            {{ $article->author->name }}
                        </a>
                    @else
                        <span class="text-slate-500">Unknown</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-sm text-slate-400">
                There are no published articles yet.
            </div>
        @endforelse
    </div>
@endsection
