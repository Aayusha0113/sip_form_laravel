<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'activity'];

    protected $casts = [
        'activity_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
