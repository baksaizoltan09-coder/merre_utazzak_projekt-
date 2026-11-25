<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orszag extends Model
{
    protected $table = 'orszagok';
    protected $guarded = [];

    public function utiCelok()
    {
        return $this->hasMany(UtiCel::class);
    }
}