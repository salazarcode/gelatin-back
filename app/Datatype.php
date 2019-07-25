<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datatype extends Model
{
    public function datos()
    {
        return $this->hasMany('App\Dato');
    }
}
