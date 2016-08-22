<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickQuoteLog extends Model
{
    public $fillable = [
        'bgas',
        'bom_lines',
        'quantity',
        'sides',
        'smt',
        'formula_id',
        'ip',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('\App\Users\CovUser', 'user_id');
    }

    public function formula()
    {
        return $this->belongsTo('\App\Admin\AdminSettings', 'formula_id');
    }
}
