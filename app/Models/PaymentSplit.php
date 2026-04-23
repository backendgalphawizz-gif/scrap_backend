<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSplit extends Model
{
    protected $fillable = [
        'user_percentage',
        'sales_percentage',
        'admin_percentage',
    ];
}
// Updated