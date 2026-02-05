<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'reg_date' => 'date',
        'signature_date' => 'date',
        'sessions' => 'integer',
        'did' => 'integer',
    ];
}
