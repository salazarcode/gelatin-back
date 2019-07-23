<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $timestamps = false;
    public function user(){
        return $this->belongsTo("App\User");
    }
    public function objectives()
    {
        return $this->belongsToMany('App\Objective');
    }
    public function habits()
    {
        return $this->belongsToMany('App\Habit');
    }
}
