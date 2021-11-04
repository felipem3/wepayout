<?php

namespace Tests\Feature;

use App\Models\Bank1;
use App\Models\Bank2;
use App\Models\Payment;
use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProcessPaymentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_process_payment_in_processor_bank()
    {
        $payment = new Payment();
        $payment->fill([
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 2356,
            'recipient_account_number' => 43652325,
            'recipient_name' => 'name...',
            'invoice' => 1,
            'status' => Payment::STATUS_CREATED
        ]);
        $payment->save();
        $this->assertEquals(Payment::STATUS_PROCESSED, Payment::query()->first()->status);
        $payment->refresh();

        if ($payment->id % 2 == 0) {
           $this->assertEquals(Bank2::ID, $payment->processor_bank_id);
        } else {
            $this->assertEquals(Bank1::ID, $payment->processor_bank_id);
        }
        $this->assertCount(1, Task::all());
    }

    public function test_update_scheduled_payment()
    {
        $id = DB::table('payments')->insertGetId([
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 2356,
            'recipient_account_number' => 43652325,
            'recipient_name' => 'name...',
            'invoice' => 1,
            'status' => Payment::STATUS_PROCESSED,
            'processor_bank_id' => 1
        ]);

        $this->artisan("payment:status $id");
        $payment = Payment::find($id);
        $this->assertNotEquals(Payment::STATUS_PROCESSED, $payment->status);
    }
}
