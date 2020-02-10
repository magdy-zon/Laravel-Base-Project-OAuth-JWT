<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondaryUser extends Model
{
    public function primary_user() {
        return $this->('App\User');
    }
}
