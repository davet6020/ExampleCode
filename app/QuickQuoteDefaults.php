<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickQuoteDefaults extends Model
{
    public $fillable = [
        'id',
        'field',
        'default',
        'min',
        'max'
    ];

}
