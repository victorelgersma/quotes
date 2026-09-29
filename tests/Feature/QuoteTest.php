<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::create(['email' => fake()->unique()->safeEmail()]);
    }

    public function test_index_lists_quotes_with_citation_comment_and_tags(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post(route('quotes.store'), [
            'body' => 'Normal science does not aim at novelties of fact or theory.',
            'citation' => 'Kuhn, Structure, p. 52',
            'comment' => 'Key line for the HPS essay',
            'tags' => 'kuhn, Paradigms, kuhn',
        ])->assertRedirect(route('quotes.index'));

        $quote = $user->quotes()->with('tags')->first();
        $this->assertSame(['kuhn', 'paradigms'], $quote->tags->pluck('name')->sort()->values()->all());

        $this->actingAs($user)->get(route('quotes.index'))
            ->assertOk()
            ->assertSee('Normal science does not aim')
            ->assertSee('Kuhn, Structure, p. 52')
            ->assertSee('Key line for the HPS essay')
            ->assertSee('paradigms');
    }

    public function test_body_is_required(): void
    {
        $this->actingAs($this->user())
            ->post(route('quotes.store'), ['citation' => 'Nobody'])
            ->assertSessionHasErrors('body');
    }

    public function test_update_and_delete(): void
    {
        $user = $this->user();
        $quote = $user->quotes()->create(['body' => 'Old']);

        $this->actingAs($user)->put(route('quotes.update', $quote), [
            'body' => 'New', 'citation' => 'Me', 'comment' => '', 'tags' => 'a',
        ]);
        $quote->refresh();
        $this->assertSame('New', $quote->body);
        $this->assertSame(['a'], $quote->tags->pluck('name')->all());

        $this->actingAs($user)->delete(route('quotes.destroy', $quote));
        $this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
    }

    public function test_users_cannot_touch_each_others_quotes(): void
    {
        $owner = $this->user();
        $quote = $owner->quotes()->create(['body' => 'Mine']);

        $this->actingAs($this->user())
            ->put(route('quotes.update', $quote), ['body' => 'Stolen'])
            ->assertNotFound();
        $this->actingAs($this->user())
            ->delete(route('quotes.destroy', $quote))
            ->assertNotFound();

        $this->assertSame('Mine', $quote->fresh()->body);
    }

    public function test_tag_pages(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post(route('quotes.store'), [
            'body' => 'Tagged quote', 'tags' => 'science',
        ]);
        $tag = $user->tags()->first();

        $this->actingAs($user)->get(route('tags.index'))->assertOk()->assertSee('science');
        $this->actingAs($user)->get(route('tags.show', $tag))->assertOk()->assertSee('Tagged quote');
        $this->actingAs($this->user())->get(route('tags.show', $tag))->assertNotFound();
    }
}
