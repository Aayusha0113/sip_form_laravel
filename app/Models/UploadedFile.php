<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    public $timestamps = false;

    protected $fillable = ['sip_number', 'file_name', 'file_path'];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
}
