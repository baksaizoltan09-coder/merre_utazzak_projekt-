<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtiCel extends Model
{
    protected $table = 'uti_celok';
    protected $guarded = [];

    protected $casts = [
        'kviz_cimkek' => 'array',
        'nepszeru' => 'boolean',
    ];

    public function orszag()
    {
        return $this->belongsTo(Orszag::class);
    }

    public function cimkek()
    {
        return $this->belongsToMany(Cimke::class, 'uti_cel_cimke');
    }
}