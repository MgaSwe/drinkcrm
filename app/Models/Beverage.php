<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beverage extends Model
{
    protected $fillable = [
        'name',
        'id',
        'min_level',
    ];
}
