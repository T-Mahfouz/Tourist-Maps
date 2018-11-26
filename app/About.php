<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    ##### Protected
    protected $table = 'about';
    protected $fillable = ['title','content','title_en','content_en',];
    protected $hidden = ['title_en','content_en','id','created_at','updated_at',];
}
