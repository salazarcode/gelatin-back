<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato extends Model
{
    public function datatype()
    {
        return $this->belongsTo('App\Datatype');
    }    
    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
