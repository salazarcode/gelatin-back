<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['pool_id', "role_id", "email", "password", "session_token", "session_recovery"];
    public function pool()
    {
        return $this->belongsTo("App\Pool");
    }
    public function role()
    {
        return $this->belongsTo("App\Role");
    }
    public function chats()
    {
        return $this->belongsToMany('App\Chat');
    }
    public function chat(){
        return $this->hasMany('App\Chat');
    }
    public function messages(){
        return $this->hasMany('App\Message');
    }
    public function recetas(){
        return $this->hasMany('App\Receta');
    }
    public function datos(){
        return $this->hasMany('App\Dato');
    }
    public function detail()
    {
        return $this->hasOne("App\Detail");
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
