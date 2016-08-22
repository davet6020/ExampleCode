<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [
        'id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo('\App\Users\CovUser', 'user_id');
    }
}
