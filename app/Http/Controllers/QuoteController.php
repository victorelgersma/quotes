<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $quotes = $request->user()->quotes()
            ->with('tags')
            ->inRandomOrder()
            ->get();

        return view('quotes.index', [
            'quotes' => $quotes,
            'availableTags' => $this->availableTags($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateQuote($request);

        $quote = $request->user()->quotes()->create($validated);

        $this->syncTags($request, $quote);

        return redirect()->route('quotes.index')->with('status', 'quote-saved');
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->user_id === $request->user()->id, 404);

        $quote->update($this->validateQuote($request));

        $this->syncTags($request, $quote);

        return back()->with('status', 'quote-updated');
    }

    public function destroy(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->user_id === $request->user()->id, 404);

        $quote->delete();

        return back()->with('status', 'quote-deleted');
    }

    private function validateQuote(Request $request): array
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
            'citation' => ['nullable', 'string', 'max:1000'],
            'comment' => ['nullable', 'string', 'max:10000'],
            'tags' => ['nullable', 'string', 'max:1000'],
        ]);

        return [
            'body' => $validated['body'],
            'citation' => $validated['citation'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ];
    }

    private function availableTags(Request $request)
    {
        return $request->user()->tags()->orderBy('name')->pluck('name');
    }

    /**
     * Splits the comma-separated "tags" input into individual tag names,
     * finds-or-creates each one scoped to the current user, and syncs
     * them onto the quote. Blank/duplicate names are dropped.
     */
    private function syncTags(Request $request, Quote $quote): void
    {
        $names = collect(explode(',', (string) $request->input('tags', '')))
            ->map(fn ($name) => Str::lower(trim($name)))
            ->filter()
            ->unique();

        $tagIds = $names->map(
            fn ($name) => Tag::firstOrCreate(
                ['user_id' => $request->user()->id, 'name' => $name]
            )->id
        );

        $quote->tags()->sync($tagIds);
    }
}
