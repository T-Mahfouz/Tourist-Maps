<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{


    ##### Protected
    protected $table = 'intro';
    protected $fillable = ['title','content','title_en','content_en',];
    protected $hidden = ['title_en','content_en','id','created_at','updated_at',];
}
