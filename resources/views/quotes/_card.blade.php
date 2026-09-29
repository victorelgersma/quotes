@php
    $copyText = $quote->citation
        ? "“{$quote->body}” — {$quote->citation}"
        : "“{$quote->body}”";
@endphp

<div
    x-data="{ editing: false, copied: false }"
    data-quote-card
    data-search="{{ $quote->searchText() }}"
    x-show="search.trim() === '' || $el.dataset.search.includes(search.trim().toLowerCase())"
    tabindex="0"
    class="group relative mb-4 break-inside-avoid rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 transition-all duration-150 hover:shadow-lg hover:z-10 focus:shadow-lg focus:z-10 focus:outline-none focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-100"
>
    {{-- View mode --}}
    <div x-show="!editing">
        <div class="absolute top-3 right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity bg-white/90 dark:bg-gray-900/90 rounded-md">
            {{-- Copy quote + citation --}}
            <button
                type="button"
                title="Copy quote"
                @click="navigator.clipboard.writeText(@js($copyText)); copied = true; setTimeout(() => copied = false, 1200)"
                class="p-1.5 rounded-md text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path d="M7 3.5A1.5 1.5 0 0 1 8.5 2h4A1.5 1.5 0 0 1 14 3.5v1h.75A2.25 2.25 0 0 1 17 6.75v9.5A2.25 2.25 0 0 1 14.75 18.5h-6.5A2.25 2.25 0 0 1 6 16.25v-9.5A2.25 2.25 0 0 1 8.25 4.5H9v-1Z"/>
                    <path d="M3 6.25A2.25 2.25 0 0 1 5.25 4H6v10.25A2.75 2.75 0 0 0 8.75 17H13v.25A2.25 2.25 0 0 1 10.75 19.5h-5.5A2.25 2.25 0 0 1 3 17.25v-11Z"/>
                </svg>
            </button>

            {{-- Edit --}}
            <button
                type="button"
                title="Edit"
                @click="editing = true"
                class="p-1.5 rounded-md text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path d="m17.414 2.586-1.414 1.414a1 1 0 0 0 0 1.414L18.586 8l1.414-1.414a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0Z"/>
                    <path d="M16 6 4 18v3h3L19 9l-3-3Z"/>
                </svg>
            </button>
        </div>

        <blockquote class="font-serif text-lg leading-relaxed whitespace-pre-line break-words">
            <span class="text-gray-300 dark:text-gray-700">“</span>{{ $quote->body }}<span class="text-gray-300 dark:text-gray-700">”</span>
        </blockquote>

        @if ($quote->citation)
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">— {{ $quote->citation }}</p>
        @endif

        @if ($quote->comment)
            <p class="mt-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line break-words">{{ $quote->comment }}</p>
        @endif

        @if ($quote->tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($quote->tags as $tag)
                    <a
                        href="{{ route('tags.show', $tag) }}"
                        class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
                    >
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <p x-show="copied" x-transition x-cloak class="mt-2 text-xs text-green-600 dark:text-green-400">Copied!</p>
    </div>

    {{-- Edit mode --}}
    <div x-show="editing" x-cloak>
        <form method="POST" action="{{ route('quotes.update', $quote) }}" class="space-y-2">
            @csrf
            @method('PUT')
            <textarea name="body" rows="4" required placeholder="Quote"
                class="w-full px-3 py-2 text-sm font-serif rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">{{ $quote->body }}</textarea>
            <input type="text" name="citation" value="{{ $quote->citation }}" placeholder="Citation"
                class="w-full px-3 py-2 text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">
            <textarea name="comment" rows="2" placeholder="Comment"
                class="w-full px-3 py-2 text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100">{{ $quote->comment }}</textarea>

            @include('quotes._tag-input', [
                'initialTags' => $quote->tags->pluck('name')->all(),
                'availableTags' => $availableTags ?? [],
            ])

            <div class="flex items-center justify-between pt-1">
                <button type="button" @click="editing = false" class="text-xs text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    Cancel
                </button>
                <button type="submit" class="text-xs font-medium text-gray-900 dark:text-gray-100 hover:underline">
                    Save
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('quotes.destroy', $quote) }}" onsubmit="return confirm('Delete this quote?')" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-red-500 hover:text-red-700">Delete quote</button>
        </form>
    </div>
</div>
