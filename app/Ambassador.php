<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ambassador extends Model
{
    ##### Protected
    protected $fillable = ['link','title','content','image',];
}
