<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCommissionSlabAudit extends Model
{
    protected $fillable = [
        'action',
        'slab_id',
        'slab_data',
        'performed_by',
    ];

    protected $casts = [
        'slab_data' => 'array',
    ];
}
