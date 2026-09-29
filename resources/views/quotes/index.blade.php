@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10" x-data="{
    formOpen: {{ $errors->any() ? 'true' : 'false' }},
    search: '',
    quotes: @js($quotes->map(fn ($q) => $q->searchText())),
    get visibleCount() {
        const q = this.search.trim().toLowerCase();
        return this.quotes.filter(text => q === '' || text.includes(q)).length;
    }
}">

    @if (session('status') === 'quote-saved')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Saved.</div>
    @elseif (session('status') === 'quote-updated')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Updated.</div>
    @elseif (session('status') === 'quote-deleted')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Deleted.</div>
    @endif

    {{-- Big, central search — filters instantly as you type, no reload --}}
    <div class="mb-10">
        <input
            type="text"
            x-model="search"
            placeholder="Search your quotes…"
            class="w-full text-2xl sm:text-3xl font-medium text-center bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-800 focus:border-gray-900 dark:focus:border-gray-100 focus:ring-0 outline-none px-1 py-4 placeholder:text-gray-300 dark:placeholder:text-gray-700 transition-colors"
            autofocus
        >
    </div>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span x-text="visibleCount"></span>
            <span x-text="visibleCount === 1 ? 'quote' : 'quotes'"></span>
        </p>

        <button
            type="button"
            @click="formOpen = !formOpen; if (formOpen) $nextTick(() => $refs.body.focus())"
            class="text-sm font-medium px-4 py-2 rounded-full bg-gray-900 text-white hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white transition-colors"
        >
            <span x-show="!formOpen">+ New Quote</span>
            <span x-show="formOpen" x-cloak>Cancel</span>
        </button>
    </div>

    {{-- New quote form — hidden until "New Quote" is pressed --}}
    <div x-show="formOpen" x-cloak x-transition class="mb-10 p-5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
        <form method="POST" action="{{ route('quotes.store') }}" class="grid gap-3">
            @csrf
            <textarea name="body" x-ref="body" rows="4" placeholder="Quote" required
                class="px-3 py-2 font-serif text-lg rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">{{ old('body') }}</textarea>
            <input type="text" name="citation" placeholder="Citation (e.g. Kuhn, Structure of Scientific Revolutions, p. 52)" value="{{ old('citation') }}"
                class="px-3 py-2 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">

            @include('quotes._tag-input', ['initialTags' => [], 'availableTags' => $availableTags])

            <textarea name="comment" rows="2" placeholder="Comment (optional)"
                class="px-3 py-2 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">{{ old('comment') }}</textarea>

            @error('body')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('citation')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('comment')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('tags')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

            <button type="submit" class="justify-self-start px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white transition-colors">
                Save quote
            </button>
        </form>
    </div>

    {{-- Masonry columns so long and short quotes pack together.
         All are rendered; the search just toggles visibility. --}}
    @if ($quotes->isEmpty())
        <div class="text-center text-gray-400 dark:text-gray-600 py-20">
            No quotes yet — add your first one above.
        </div>
    @else
        <div x-show="visibleCount === 0" x-cloak class="text-center text-gray-400 dark:text-gray-600 py-20">
            No quotes match that search.
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 gap-4">
            @foreach ($quotes as $quote)
                @include('quotes._card', ['quote' => $quote, 'availableTags' => $availableTags])
            @endforeach
        </div>
    @endif
</div>
@endsection
