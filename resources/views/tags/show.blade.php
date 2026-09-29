@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10" x-data="{ search: '' }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tags.index') }}" class="text-sm text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            ← Tags
        </a>
        <h1 class="text-2xl font-semibold">{{ $tag->name }}</h1>
    </div>

    @if (session('status') === 'quote-updated')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Updated.</div>
    @elseif (session('status') === 'quote-deleted')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Deleted.</div>
    @endif

    @if ($quotes->isEmpty())
        <div class="text-center text-gray-400 dark:text-gray-600 py-20">
            No quotes tagged "{{ $tag->name }}" yet.
        </div>
    @else
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-4">
            @foreach ($quotes as $quote)
                @include('quotes._card', ['quote' => $quote, 'availableTags' => $availableTags])
            @endforeach
        </div>
    @endif
</div>
@endsection
