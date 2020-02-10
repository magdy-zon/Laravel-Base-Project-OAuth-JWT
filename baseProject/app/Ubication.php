<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubication extends Model
{
    public function user_ubication() {
        return $this->belongsTo('App\User');
    }
}
