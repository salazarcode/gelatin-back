<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    public $timestamps = false;
    public function details()
    {
        return $this->belongsToMany('App\Detail');
    }
}
