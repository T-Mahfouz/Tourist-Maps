<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceType extends Model
{
    public function places()
	{
		return $this->hasMany(\App\Place::class,'place_type_id');
	}
	##### Protected
    protected $fillable = ['name','marker',]; 
    protected $hidden = ['created_at','updated_at',];
}
