<?php

namespace TheRiptide\LaravelDynamicText\Http\Livewire;

use Livewire\Component;
use TheRiptide\LaravelDynamicText\Models\Text;
use TheRiptide\LaravelDynamicDashboard\Objects\Menu;

class TextIndex extends Component
{
    public $field = 'de';

    public $texts;
    public $categories;
    public $category;
    public $search;

    
    protected $rules = [
        'texts.*.de' => 'required|string',
    ];
    public function render()
    {

        $this->texts = Text::query()
            // ->searchFilter($this->search)
            // ->categoryFilter($this->category)
            ->get();

        $this->categories = Text::pluck('category')->unique()->sortby('category');

        return view('dyntext::index', [
            'texts' => $this->texts,

        ])->extends('dyntext::layout', [
            // 'menuItems' => (new Menu)->items
            'menuItems' => [],
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

