<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Ixudra\Curl\Facades\Curl;

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

    public function getTheJoke()
    {
        return Curl::to('https://official-joke-api.appspot.com/random_joke')->get();
    }
}
