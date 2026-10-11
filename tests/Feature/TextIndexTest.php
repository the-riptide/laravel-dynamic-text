<?php

namespace TheRiptide\LaravelDynamicText\Tests\Feature;

use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicText\Tests\TestCase;

class TextIndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        __i('contact.name', 'Your Name*');
        __i('navbar.Services', 'Services');
    }

    #[Test]
    public function the_texts_page_lists_every_text_by_category_under_its_title(): void
    {
        $this->actingAs($this->user());

        $this->get('/dashboard/texts/edit')
            ->assertOk()
            ->assertSeeInOrder(['<h3', 'Texts', '</h3>'], false)
            ->assertSee('contact')
            ->assertSee('navbar')
            ->assertSee('Your Name*')
            ->assertSee('wire:model.live="search"', false)
            ->assertSee('wire:model="edits.', false);
    }

    #[Test]
    public function guests_and_users_off_the_allow_list_are_refused(): void
    {
        $this->get('/dashboard/texts/edit')->assertForbidden();

        $this->actingAs($this->user('stranger@example.com'));
        $this->get('/dashboard/texts/edit')->assertForbidden();
    }

    #[Test]
    public function filtering_by_category_narrows_the_list(): void
    {
        $this->actingAs($this->user());

        Livewire::test(TextIndex::class)
            ->set('category', 'navbar')
            ->assertSet('texts', fn ($texts) => $texts->pluck('category')->unique()->values()->all() === ['navbar'])
            ->assertDontSee('Your Name*');
    }

    #[Test]
    public function searching_matches_the_text_the_category_and_the_key(): void
    {
        $this->actingAs($this->user());
        $page = Livewire::test(TextIndex::class);

        $page->set('search', 'Your Name')
            ->assertSet('texts', fn ($texts) => $texts->pluck('key')->all() === ['name']);
        $page->set('search', 'navbar')
            ->assertSet('texts', fn ($texts) => $texts->pluck('key')->all() === ['Services']);
        $page->set('search', 'name')
            ->assertSet('texts', fn ($texts) => $texts->pluck('key')->all() === ['name']);
    }

    #[Test]
    public function searching_within_a_category_keeps_to_that_category(): void
    {
        $this->actingAs($this->user());

        // "Services" is in navbar only; the search must not reach past the category filter.
        Livewire::test(TextIndex::class)
            ->set('category', 'contact')
            ->set('search', 'Services')
            ->assertSet('texts', fn ($texts) => $texts->isEmpty());
    }

    #[Test]
    public function a_search_without_matches_keeps_the_search_box(): void
    {
        $this->actingAs($this->user());

        Livewire::test(TextIndex::class)
            ->set('search', 'no such text')
            ->assertSee('placeholder="search"', false)
            ->assertDontSee('There are currently no texts to show.');
    }

    #[Test]
    public function an_empty_texts_table_shows_the_empty_state(): void
    {
        Text::query()->delete();
        $this->actingAs($this->user());

        Livewire::test(TextIndex::class)
            ->assertSee('There are currently no texts to show.')
            ->assertDontSee('placeholder="search"', false);
    }

    #[Test]
    public function an_edit_saved_from_the_page_reaches_the_site(): void
    {
        $this->actingAs($this->user());
        $text = Text::where('key', 'name')->first();

        // In the browser the deferred edit and the save arrive in one request.
        Livewire::test(TextIndex::class)
            ->assertSet("edits.{$text->id}.en", 'Your Name*')
            ->update(
                calls: [['method' => 'save', 'params' => [$text->id], 'path' => '']],
                updates: ["edits.{$text->id}.en" => 'Your full name*'],
            );

        $this->assertDatabaseHas('texts', ['id' => $text->id, 'en' => 'Your full name*']);
        $this->assertSame('Your full name*', cache('translations.en')['contact.name']);
        app('translator')->setLoaded([]);
        $this->assertSame('Your full name*', __i('contact.name', 'Your Name*'));
    }

    #[Test]
    public function saving_writes_only_the_locale_columns(): void
    {
        $this->actingAs($this->user());
        $text = Text::where('key', 'name')->first();

        Livewire::test(TextIndex::class)->update(
            calls: [['method' => 'save', 'params' => [$text->id], 'path' => '']],
            updates: ["edits.{$text->id}.category" => 'navbar', "edits.{$text->id}.en" => 'Name'],
        );

        $this->assertDatabaseHas('texts', ['id' => $text->id, 'category' => 'contact', 'en' => 'Name']);
    }

    #[Test]
    public function an_unsaved_edit_is_replaced_by_the_stored_text_on_the_next_render(): void
    {
        $this->actingAs($this->user());
        $text = Text::where('key', 'name')->first();

        // As before the upgrade: every render shows what is stored.
        Livewire::test(TextIndex::class)
            ->set("edits.{$text->id}.en", 'Draft')
            ->set('category', 'contact')
            ->assertSet("edits.{$text->id}.en", 'Your Name*');

        $this->assertDatabaseHas('texts', ['id' => $text->id, 'en' => 'Your Name*']);
    }
}
