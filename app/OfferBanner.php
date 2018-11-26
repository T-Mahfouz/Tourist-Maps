<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferBanner extends Model
{
    ##### Relations
	public function offer()
	{
		return $this->belongsTo(\App\Offer::class,'offer_id');
	}

    ##### Protected
    protected $fillable = ['offer_id','image','description',];
    protected $hidden = ['created_at','updated_at',];
}
