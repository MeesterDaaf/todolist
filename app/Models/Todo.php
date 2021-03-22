<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function children()
    {
        return $this->hasMany('App\Models\Todo', 'parent', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Todo', 'id', 'parent');
    }

    //check if a child todo item is set to zero
    public function scopeIsNotDone($query, $parent)
    {
        return $query->where('done', 0)->where('parent', $parent);
    }
}
