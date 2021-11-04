<?php

namespace App\Models;

class Bank2 extends Bank
{
    const ID = 2;

    function registerPayment(Payment $payment)
    {
        $this->paymentService->update($payment->id, [
            'processor_bank_id' => self::ID,
            'status' => Payment::STATUS_PROCESSED
        ]);
    }
}
