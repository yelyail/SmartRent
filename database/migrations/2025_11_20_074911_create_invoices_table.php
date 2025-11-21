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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->unsignedBigInteger('lease_id');  // Tenant's lease
            $table->string('invoice_no', 50)->unique(); // INV-2025-00001
            
            $table->decimal('subtotal', 12, 2);
            $table->decimal('late_fees', 12, 2)->default(0.00);
            $table->decimal('other_charges', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2); // subtotal + late_fees + others

            // Status
            $table->enum('status', ['unpaid','partial','paid'])->default('unpaid');

            $table->date('invoice_date');
            $table->date('due_date');

            $table->foreign('lease_id')->references('lease_id')->on('leases')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
