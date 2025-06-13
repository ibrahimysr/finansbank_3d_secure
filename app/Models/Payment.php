<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'card_number',
        'card_holder_name',
        'email',
        'phone',
        'status',
        'transaction_id',
        'auth_code',
        'host_ref_num',
        'proc_return_code',
        'error_message',
        'raw_response',
        'ip_address'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'array'
    ];
} 