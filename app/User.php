<?php

namespace App;

use App\Favourite;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    ##### Relations
    public function places()
    {
        return $this->hasMany(\App\Place::class,'user_id');
    }
    public function rates()
    {
        return $this->hasMany(\App\PlaceRate::class,'user_id');
    }
    public function notifications()
    {
        return $this->hasMany(\App\Notification::class,'user_id');
    }
    public function favourites()
    {
        return $this->hasMany(\App\Favourite::class,'user_id');
    }
    public function posts()
    {
        return $this->hasMany(\App\Post::class,'user_id');
    }
    ##### Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    ##### Protected
    protected $fillable = [
    'fullname','email','phone','image','username','password','bio','status','code','firebase','last_seen',
    ];
    protected $hidden = [
        'password', 'remember_token','bio','last_seen','status','code',
    ];
    protected $dates = ['created_at', 'updated_at','last_seen',];
       
    
}
