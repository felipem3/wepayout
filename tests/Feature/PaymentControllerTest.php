<?php

namespace Tests\Feature;

use App\Jobs\ProcessPayment;
use App\Models\Payment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_validate_min_value_of_payment()
    {
        $response = $this->post('api/payments', [
            'value' => 0.0,
            'recipient_bank_code' => 1,
            'recipient_branch_number' => 2,
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "value" => ["The value must be at least 0.01."]
            ]);
    }

    public function test_validate_max_value_of_payment()
    {
        $response = $this->post('api/payments', [
            'value' => 100001,
            'recipient_bank_code' => 1,
            'recipient_branch_number' => 2,
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "value" => ["The value must not be greater than 100000."]
            ]);
    }

    public function test_validate_empty_bank_code()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => '',
            'recipient_branch_number' => 2,
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_bank_code" => ["The recipient bank code field is required."]
            ]);
    }

    public function test_validate_max_length_bank_code()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => '6651',
            'recipient_branch_number' => 2,
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_bank_code" => ["The recipient bank code must be between 1 and 3 digits."]
            ]);
    }

    public function test_validate_empty_branch_number()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => '3',
            'recipient_branch_number' => '',
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_branch_number" => ["The recipient branch number field is required."]
            ]);
    }

    public function test_validate_max_length_branch_number()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 23256,
            'recipient_account_number' => 3,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_branch_number" => ["The recipient branch number must be between 1 and 4 digits."]
            ]);
    }

    public function test_validate_empty_recipient_account_number()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => 656,
            'recipient_branch_number' => 225,
            'recipient_account_number' => '',
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_account_number" => ["The recipient account number field is required."]
            ]);
    }

    public function test_validate_max_length_recipient_account_number()
    {
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 23256,
            'recipient_account_number' => 4365232525412585,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "recipient_account_number" => ["The recipient account number must be between 1 and 15 digits."]
            ]);
    }

    public function test_validate_unique_invoice()
    {
        DB::table('payments')->insert([
            'value' => 100,
            'recipient_bank_code' => 11,
            'recipient_branch_number' => 22,
            'recipient_account_number' => 123321,
            'status' => Payment::STATUS_CREATED,
            'invoice' => 1,
            'recipient_name' => 'recipient'
        ]);
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 2325,
            'recipient_account_number' => 33213,
            'invoice' => 1,
            'recipient_name' => 'name...'
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                "invoice" => ["The invoice has already been taken."]
            ]);
    }

    public function test_create_payment()
    {
        Bus::fake();
        $response = $this->post('api/payments', [
            'value' => 10,
            'recipient_bank_code' => 22,
            'recipient_branch_number' => 2356,
            'recipient_account_number' => 43652325,
            'recipient_name' => 'name...',
            'invoice' => 1,
        ]);

        $response->assertStatus(201);
        $payments = Payment::all();
        $this->assertCount(1, $payments);
        $this->assertEquals(Payment::STATUS_CREATED, $payments->first()->status);
        Bus::assertDispatched(ProcessPayment::class);
    }

}
