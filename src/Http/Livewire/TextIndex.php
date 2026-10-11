<?php

namespace TheRiptide\LaravelDynamicText\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Component;
use TheRiptide\LaravelDynamicDashboard\DynamicDashboardServiceProvider;
use TheRiptide\LaravelDynamicDashboard\Objects\Menu as DashMenu;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicText\Objects\Menu as TextMenu;
use TheRiptide\LaravelDynamicText\PrepText;

class TextIndex extends Component
{
    public $texts;

    public $categories;

    public $category;

    public $search;

    public $exists;

    public $type = 'Texts';

    public $locales;

    /** The edited values, keyed by text id and locale. Livewire 3 does not bind to Eloquent attributes. */
    public array $edits = [];

    protected function rules()
    {
        return collect($this->locales)->mapWithKeys(fn ($locale) => ['edits.*.'.$locale => 'required|string'])->all();
    }

    public function boot()
    {
        $this->locales = config('app.locales') ?? [App::getLocale()];
        $this->exists = class_exists(DynamicDashboardServiceProvider::class);
        $this->categories = Text::pluck('category')->unique()->mapWithKeys(fn ($item) => [$item => Str::ucfirst($item)]);
    }

    public function render()
    {
        $this->texts = Text::query()
            ->searchFilter($this->search)
            ->categoryFilter($this->category)
            ->get();

        // Every render shows what is stored, as the texts themselves are reloaded.
        $this->edits = $this->texts->mapWithKeys(
            fn ($text) => [$text->id => collect($this->locales)->mapWithKeys(fn ($locale) => [$locale => $text->$locale])->all()]
        )->all();

        return view('dyntext::index', [
            'categories' => $this->categories,

        ])->extends('dashcomp::layout',
            [
                'menuItems' => $this->exists
                    ? (new DashMenu)->items
                    : (new TextMenu)->items,
            ])
            ->section('body');
    }

    public function save($id)
    {
        Text::findOrFail($id)->update(Arr::only($this->edits[$id] ?? [], $this->locales));

        if (config('app.locales')) {
            foreach (config('app.locales') as $locale) {
                (new PrepText($locale))->updateCache();
            }
        } else {
            (new PrepText(config('app.locale')))->updateCache();
        }
    }
}
