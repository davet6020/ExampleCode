<?php

namespace App\Users;

use App\User;

class CovUser extends \Cartalyst\Sentinel\Users\EloquentUser
{
    protected $guarded = [
        'id',
        'created_by',
    ];

    protected $fillable = [];

    public function address() {
        return $this->hasMany('\App\Address', 'user_id');
    }

    public function projects() {
        return $this->hasMany('\App\Project', 'user_id');
    }

    public function quickQuoteLogs() {
        return $this->hasMany('\App\QuickQuoteLog', 'user_id');
    }
}
