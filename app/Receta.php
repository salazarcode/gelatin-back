<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    public function foodtype()
    {
        return $this->belongsTo('App\Foodtype');
    }
    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
