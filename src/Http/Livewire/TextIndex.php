<?php

namespace TheRiptide\LaravelDynamicText\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicText\Objects\Menu as TextMenu;

use TheRiptide\LaravelDynamicDashboard\Objects\Menu as DashMenu;
use TheRiptide\LaravelDynamicDashboard\DynamicDashboardServiceProvider;

class TextIndex extends Component
{
    public $field = 'de';

    public $texts;
    public $categories;
    public $category;
    public $search;
    public $exists;
    public $type = 'Texts';
    public $locales;
    
    protected $rules = [
        'texts.*.de' => 'required|string',
    ];
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


        return view('dyntext::index', [
            'texts' => $this->texts,
            'categories' => $this->categories,

        ])->extends($this->exists 
            ? 'dyndash::layout' 
            : 'dyntext::layout', 
            [
                'menuItems' => $this->exists  
                    ? (new DashMenu)->items 
                    : (new TextMenu)->items,
            ])
            ->section('body');
    }

    public function searchAction($id) {

        
    }

    public function save($id) {
    
        $this->texts[$id]->save();
    
        // foreach (config('app.locales') as $locale) {

        //     Cache::forget('translations.' . $locale);
        //     (New PrepText($locale))->updateCache();
        // }
    }
}

