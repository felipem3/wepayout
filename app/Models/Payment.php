<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'invoice',
        'recipient_name',
        'value',
        'recipient_bank_code',
        'recipient_branch_number',
        'recipient_account_number',
        'status',
        'processor_bank_id'
    ];

    const STATUS_CREATED = 'created';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PROCESSED = 'processed';
    const STATUS_PAID = 'paid';
    const STATUS_REJECTED = 'rejected';
}
