<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $tags = $request->user()->tags()
            ->withCount('quotes')
            ->orderBy('name')
            ->get();

        return view('tags.index', [
            'tags' => $tags,
        ]);
    }

    public function show(Request $request, Tag $tag): View
    {
        abort_unless($tag->user_id === $request->user()->id, 404);

        $quotes = $tag->quotes()
            ->with('tags')
            ->latest()
            ->get();

        $availableTags = $request->user()->tags()
            ->orderBy('name')
            ->pluck('name');

        return view('tags.show', [
            'tag' => $tag,
            'quotes' => $quotes,
            'availableTags' => $availableTags,
        ]);
    }
}
