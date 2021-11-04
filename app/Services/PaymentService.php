<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use function Symfony\Component\Translation\t;

class PaymentService
{
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function all()
    {
        return $this->paymentRepository->all();
    }

    public function get(int $id)
    {
        return $this->paymentRepository->get($id);
    }

    public function create(
        float  $value,
        string $invoice,
        string $recipientName,
        string $recipientBankCode,
        string $recipientBranchNumber,
        string $recipientAccountNumber
    ) {
        $this->paymentRepository->save(new Payment([
            'value' => $value,
            'invoice' => $invoice,
            'recipient_name' => $recipientName,
            'status' => Payment::STATUS_CREATED,
            'recipient_bank_code' => $recipientBankCode,
            'recipient_branch_number' => $recipientBranchNumber,
            'recipient_account_number' => $recipientAccountNumber,
        ]));
    }

    public function update(int $id, array $data)
    {
        $payment = $this->paymentRepository->get($id);
        $payment->fill($data);
        $this->paymentRepository->save($payment);
    }
}
