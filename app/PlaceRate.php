<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceRate extends Model
{
    ##### Relations
	public function place()
	{
		return $this->belongsTo(\App\Place::class,'place_id');
	}
	public function user()
	{
		return $this->belongsTo(\App\User::class,'user_id');
	}
	##### Protected
    protected $fillable = ['user_id','place_id','rate',];
    protected $hidden = ['updated_at',];
}
