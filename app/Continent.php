<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
	##### Relations
	public function countries()
	{
		return $this->hasMany(\App\Country::class,'continent_id');
	}
    ##### Protected
    protected $fillable = ['name','name_en','icon',];
    protected $hidden = ['created_at','updated_at',];
}
