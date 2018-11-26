<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactLink extends Model
{
	
    ##### Protected
    protected $fillable = ['type','link','icon',];
    protected $hidden = ['created_at','updated_at',];
}
