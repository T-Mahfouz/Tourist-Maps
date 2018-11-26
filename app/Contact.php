<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    ##### Protected
    protected $fillable = ['name','email','title','content',];
    protected $hidden = ['updated_at',];
}
