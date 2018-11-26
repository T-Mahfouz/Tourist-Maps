<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	##### Relations
    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }
    
    ##### Protected
    protected $fillable = ['user_id','content',];
    protected $hidden = ['updated_at',];
    protected $dates = ['created_at', 'updated_at',];
}
