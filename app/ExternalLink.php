<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalLink extends Model
{

	##### Protected
    protected $fillable = ['link','title','content','image',];

}
