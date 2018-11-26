<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceImage extends Model
{
    public function place()
	{
		return $this->belongsTo(\App\Place::class,'place_id');
	}
	##### Protected
    protected $fillable = ['place_id','image',];    
    protected $hidden = ['updated_at',];
}
