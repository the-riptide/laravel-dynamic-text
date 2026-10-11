<?php

namespace TheRiptide\LaravelDynamicText\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicText\Tests\TestCase;

class HelperTest extends TestCase
{
    #[Test]
    public function a_missing_key_with_a_default_is_created_and_the_default_returned(): void
    {
        $this->assertSame('Send message', __i('contact.send', 'Send message'));

        $this->assertDatabaseHas('texts', ['category' => 'contact', 'key' => 'send', 'en' => 'Send message']);
        $this->assertDatabaseCount('texts', 1);
    }

    #[Test]
    public function an_existing_key_returns_the_stored_text_whatever_the_default(): void
    {
        Text::create(['category' => 'contact', 'key' => 'send', 'en' => 'Submit']);

        $this->assertSame('Submit', __i('contact.send', 'Send message'));
        $this->assertSame('Submit', __i('contact.send'));
        $this->assertDatabaseCount('texts', 1);
    }

    #[Test]
    public function a_missing_key_without_a_default_returns_the_translation_key_and_creates_nothing(): void
    {
        $this->assertSame('lang.cta.contact us', __i('cta.contact us'));

        $this->assertDatabaseCount('texts', 0);
    }

    #[Test]
    public function keys_split_on_the_dot_into_category_and_key_and_may_contain_spaces(): void
    {
        __i('home.experience neck', 'Our timing adventure started in 2007.');

        $this->assertDatabaseHas('texts', ['category' => 'home', 'key' => 'experience neck']);

        // The translator loaded the "lang" group before the text existed; the next request sees it.
        $this->assertSame('lang.home.experience neck', trans('lang.home.experience neck'));
        app('translator')->setLoaded([]);
        $this->assertSame('Our timing adventure started in 2007.', trans('lang.home.experience neck'));
    }

    #[Test]
    public function texts_are_read_through_the_cache_and_refreshed_on_write(): void
    {
        __i('contact.send', 'Send message');
        $this->assertSame(['contact.send' => 'Send message'], cache('translations.en')->all());

        Text::where('key', 'send')->update(['en' => 'Submit']);
        $this->assertSame('Send message', cache('translations.en')['contact.send']);

        __i('contact.other', 'Other');
        $this->assertSame('Submit', cache('translations.en')['contact.send']);
    }

    #[Test]
    public function the_setup_command_writes_a_lang_file_per_locale_into_the_lang_path(): void
    {
        $this->assertFileExists(lang_path('en/lang.php'));
        $this->assertStringContainsString("new PrepText('en')", file_get_contents(lang_path('en/lang.php')));
    }
}
