<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaDetailDocument extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Belongs To VisaDetail
    public function visa_detail()
    {
        return $this->belongsTo(VisaDetail::class, 'visa_detail_id');
    }

    // Belongs To User Created By
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
