<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name', 'last_name', 'sex', 'direction', 'phone', 'birthdate', 'image'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
