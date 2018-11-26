<?php

namespace App;

use App\Favourite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Place extends Model
{
    ##### Relations
	public function user()
	{
		return $this->belongsTo(\App\User::class,'user_id');
	}
	public function place_type()
	{
		return $this->belongsTo(\App\PlaceType::class,'place_type_id');
	}
	public function continent()
	{
		return $this->belongsTo(\App\Continent::class,'continent_id');
	}
	public function country()
	{
		return $this->belongsTo(\App\Country::class,'country_id');
	}
	public function images()
	{
		return $this->hasMany(\App\PlaceImage::class,'place_id');
	}
	public function rates()
	{
		return $this->hasMany(\App\PlaceRate::class,'place_id');
	}
	public function posts()
	{
		return $this->hasMany(\App\Post::class,'place_id');
	}
	
    ##### Methods
    public function avg_rates()
	{
		$rates = 0;
		$allrates = PlaceRate::where('place_id',$this->id)->pluck('rate')->toArray();
		if(count($allrates))
			$rates = array_sum($allrates) / count($allrates);

		return $rates;
	}
	public function isFavourite()
    {
        $isFavourite = 0;
        $favourite = Favourite::where([
                'user_id' => Auth::user()->id,
                'place_id' => $this->id
        ])->first();
        if($favourite)
            $isFavourite = 1;
        return $isFavourite;
    }

    ##### Protected
    protected $fillable = [
    	'user_id','place_type_id','continent_id','country_id','name','name_en','lat','lon','address','description','status',
    ];
    protected $hidden = ['user_id','name_en','updated_at',];
}
