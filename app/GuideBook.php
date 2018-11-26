<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideBook extends Model
{
    ##### Protected
    protected $fillable = ['title','content','path','image',];
    protected $hidden = ['created_at','updated_at',];
}
