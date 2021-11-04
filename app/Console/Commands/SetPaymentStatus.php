<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Console\Command;

class SetPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:status {payment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set payment status';
    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $paymentId = $this->argument('payment');

        if (rand() % 2) {
            $status = Payment::STATUS_PAID;
        } else {
            $status = Payment::STATUS_REJECTED;
        }

        $this->paymentService->update($paymentId, [
            'status' => $status
        ]);

        return Command::SUCCESS;
    }
}
