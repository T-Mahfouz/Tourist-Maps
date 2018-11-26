<?php

namespace App;

use App\Comment;
use App\Post;
use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable,HasApiTokens;

    ############ Protected
    protected $fillable = [
        'role_id', 'name', 'email', 'phone', 'image', 'code', 'status', 'password', 'firebase', 'last_seen',
    ];
    protected $hidden = ['password', 'remember_token','email','phone','firebase','last_seen','role_id','id','code','status','created_at', 'updated_at',];
    protected $dates = ['created_at', 'updated_at','last_seen',];
}
