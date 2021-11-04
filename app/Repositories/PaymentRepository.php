<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function all()
    {
        return Payment::all();
    }

    public function get(int $id)
    {
        return Payment::find($id);
    }

    public function save(Payment $payment)
    {
        $payment->save();
    }
}
