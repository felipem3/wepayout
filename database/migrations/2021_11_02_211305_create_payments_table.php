<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice');
            $table->string('recipient_name');
            $table->string('recipient_bank_code');
            $table->string('recipient_branch_number');
            $table->string('recipient_account_number');
            $table->double('value')->unsigned();
            $table->enum('status', ['created', 'processing', 'processed', 'paid', 'rejected']);
            $table->integer('processor_bank_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
