<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function address()
    {
        return $this->belongsTo('App\Address');
    }
}
