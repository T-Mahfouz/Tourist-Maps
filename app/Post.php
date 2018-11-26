<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	public function user()
	{
		return $this->belongsTo(\App\User::class,'user_id');
	}
	public function place()
	{
		return $this->belongsTo(\App\Place::class,'place_id');
	}
	##### Protected
    protected $fillable = ['user_id','place_id','content',];
    protected $dates = ['created_at','updated_at',];
    protected $hidden = ['updated_at',];

    
}
