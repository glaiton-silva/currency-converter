<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_fee_boleto',
        'payment_fee_credit_card',
        'conversion_fee_below_3000',
        'conversion_fee_above_3000',
    ];
}
