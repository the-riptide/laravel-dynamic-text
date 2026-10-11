<?php

namespace TheRiptide\LaravelDynamicText\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    protected $guarded = [];

    use HasFactory;

    public function scopeCategoryFilter($query, $category)
    {
        $query->when(
            $category, fn ($query) => $query->where('category', $category)
        );
    }

    /** Searches the text in every configured locale, the category and the key. */
    public function scopeSearchFilter($query, $search)
    {
        $columns = [...(config('app.locales') ?? [config('app.locale')]), 'category', 'key'];

        $query->when($search, fn ($query) => $query->where(function ($query) use ($columns, $search) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%'.$search.'%');
            }
        }));
    }
}
