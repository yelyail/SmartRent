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
        Schema::create('billings', function (Blueprint $table) {
            $table->id('bill_id');
            $table->unsignedBigInteger('lease_id');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->string('bill_name');
            $table->string('bill_period');
            $table->string('due_date');
            $table->decimal('late_fee');
            $table->integer('overdue_amount_percent');
            $table->decimal('amount');
            $table->enum('status', ['pending','partial','paid','overdue'])->default('pending');

            $table->foreign('lease_id')->references('lease_id')->on('leases')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('request_id')->references('request_id')->on('maintenance_rqst')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
