<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodtype extends Model
{
    public function recetas()
    {
        return $this->hasMany('App\Receta');
    }
}
