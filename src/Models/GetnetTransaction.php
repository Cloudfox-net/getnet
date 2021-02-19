<?php

namespace CloudFox\GetNet\Models;

use Illuminate\Database\Eloquent\Model;

class GetnetTransaction extends Model
{
    
    public $fillable = [
        'company_id',
        'sale_id',
        'order_id',
        'hash_id',
        'type',
        'type_register',
        'status_code',
        'bank',
        'agency',
        'account_number',
        'release_status',
        'transaction_date',
        'confirmation_date',
        'amount',
        'payment_date',
        'subseller_rate_closing_date',
        'subseller_rate_confirm_date',
        'transaction_sign',
    ];
}
