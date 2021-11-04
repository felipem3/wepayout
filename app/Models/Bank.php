<?php

namespace App\Models;

use App\Services\PaymentService;

abstract class Bank
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function getPayment(int $paymentId)
    {
        return $this->paymentService->get($paymentId);
    }

    abstract function registerPayment(Payment $payment);
}
