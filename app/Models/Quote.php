<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Quote extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'citation',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Everything the live search should match against, lower-cased:
     * the quote itself, who/where it's from, your comment, and tags.
     */
    public function searchText(): string
    {
        return Str::lower(implode(' ', [
            $this->body,
            $this->citation ?? '',
            $this->comment ?? '',
            $this->tags->pluck('name')->implode(' '),
        ]));
    }
}
