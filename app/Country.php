<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    ##### Relations
	public function continent()
	{
		return $this->belongsTo(\App\Continent::class,'continent_id');
	}
	public function places()
	{
		return $this->hasMany(\App\Place::class,'country_id');
	}
	
    ##### Protected
    protected $fillable = ['continent_id','name','flag','order','description','image',];
    protected $hidden = ['name_en','created_at','updated_at',];
}
