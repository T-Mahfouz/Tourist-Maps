<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    ##### Protected
    protected $fillable = ['image','link',];
    protected $hidden = ['id','created_at','updated_at',];
}
