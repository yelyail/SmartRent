<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('bill_id');  // Target bill
            $table->unsignedBigInteger('lease_id'); // Optional: quick lookup

            $table->decimal('amount_paid', 12, 2);
            $table->dateTime('payment_date');
            $table->enum('payment_method', [ 'e-cash', 'bank']);
            $table->string('reference_no')->nullable();
            $table->string('transaction_type');
            $table->foreign('bill_id')->references('bill_id')->on('billings')->cascadeOnUpdate()->cascadeOnDelete();
           $table->foreign('lease_id')->references('lease_id')->on('leases')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
