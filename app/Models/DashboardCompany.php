<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardCompany extends Model
{
    protected $table = 'dashboard_companies';

    protected $guarded = ['id'];

    protected $casts = [
        'reg_date' => 'date',
        'signature_date' => 'date',
        'sessions' => 'integer',
        'did' => 'integer',
    ];

    public function uploadedFiles()
    {
        return $this->hasMany(UploadedFile::class, 'sip_number', 'sip_number');
    }
}
