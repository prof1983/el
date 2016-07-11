<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['id', 'address', 'password', 'key', 'salt', 'currency', 'private_key', 'public_key', 'iv', 'data'];

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
