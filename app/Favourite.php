<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    public function user()
    {
        return $this->belongsto(\App\User::class,'user_id');
    }
    public function place()
    {
        return $this->belongsto(\App\Place::class,'place_id');
    }
    ##### Protected
    protected $fillable = ['user_id','place_id',];
    protected $hidden = ['updated_at',];
}
