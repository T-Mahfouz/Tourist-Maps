<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    ##### Relations
	public function banners()
	{
		return $this->hasMany(\App\OfferBanner::class,'offer_id');
	}

    ##### Protected
    protected $fillable = ['link','title','content','image',];
    protected $hidden = ['created_at','updated_at',];
}
