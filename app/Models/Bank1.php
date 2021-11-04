<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank1 extends Bank
{
    const ID = 1;

    function registerPayment(Payment $payment)
    {
        $this->paymentService->update($payment->id, [
            'processor_bank_id' => self::ID,
            'status' => Payment::STATUS_PROCESSED
        ]);
    }
}
