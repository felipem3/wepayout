<?php

namespace App\Jobs;

use App\Models\Bank1;
use App\Models\Bank2;
use App\Models\Payment;
use App\Models\Task;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payment;
    private $paymentService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PaymentService $paymentService, Payment $payment)
    {
        $this->paymentService = $paymentService;
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('processPayment');
        Log::info('processPayment');
        $this->paymentService->update($this->payment->id, ['status' => Payment::STATUS_PROCESSING]);

        if($this->payment->id % 2 == 0) {
            $bank = new Bank2($this->paymentService);
        } else {
            $bank = new Bank1($this->paymentService);
        }

        $bank->registerPayment($this->payment);
        $paymentId = $this->payment->id;
        $command = "payment:status $paymentId";
        Task::query()->insert([
            'date_time' => Carbon::now()->addMinutes(2)->setSecond(0),
            'command' => $command,
        ]);
    }
}
