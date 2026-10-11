<?php

namespace TheRiptide\LaravelDynamicText\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicText\Tests\TestCase;

class MultipleLocalesTest extends TestCase
{
    protected ?array $locales = ['en', 'nl'];

    // The texts table's locale columns are fixed at migration time; rebuild it for this class and after it.
    protected function setUp(): void
    {
        RefreshDatabaseState::$migrated = false;

        parent::setUp();
    }

    public static function tearDownAfterClass(): void
    {
        RefreshDatabaseState::$migrated = false;

        parent::tearDownAfterClass();
    }

    #[Test]
    public function a_new_key_is_created_in_every_locale_and_cached_per_locale(): void
    {
        __i('contact.send', 'Send message');

        $this->assertDatabaseHas('texts', ['key' => 'send', 'en' => 'Send message', 'nl' => 'Send message']);
        $this->assertSame('Send message', cache('translations.nl')['contact.send']);
    }

    #[Test]
    public function searching_matches_any_locale_and_saving_writes_each_locale(): void
    {
        $this->actingAs($this->user());
        $text = Text::create(['category' => 'contact', 'key' => 'send', 'en' => 'Send message', 'nl' => 'Verstuur bericht']);
        Text::create(['category' => 'contact', 'key' => 'name', 'en' => 'Name', 'nl' => 'Naam']);

        Livewire::test(TextIndex::class)
            ->set('search', 'Verstuur')
            ->assertSet('texts', fn ($texts) => $texts->pluck('key')->all() === ['send'])
            ->update(
                calls: [['method' => 'save', 'params' => [$text->id], 'path' => '']],
                updates: ["edits.{$text->id}.nl" => 'Stuur bericht'],
            );

        $this->assertDatabaseHas('texts', ['id' => $text->id, 'en' => 'Send message', 'nl' => 'Stuur bericht']);
        $this->assertSame('Stuur bericht', cache('translations.nl')['contact.send']);
        $this->assertFileExists(lang_path('nl/lang.php'));
    }
}
