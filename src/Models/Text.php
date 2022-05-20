<?php

namespace TheRiptide\LaravelDynamicText\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Text extends Model
{

    protected $guarded = [];

    use HasFactory;

    public function scopeCategoryFilter($query, $category){

        $query->when(
            $category, fn ($query) => $query->where($this->category, $category)
        );
    }

    public function scopeSearchFilter($query, $search) {

        $query->when($search, function ($query) use ($search) {
            
            return $query->where('de', 'like', '%' . $search . '%')
                ->orWhere('category', 'like', '%' . $search . '%');
        });
    }
}
