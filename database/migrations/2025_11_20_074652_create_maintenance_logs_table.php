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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('request_id');

            // Action details
            $table->string('action', 150);        // "Technician assigned", "Part replaced"
            $table->text('notes')->nullable();    // Deep details
            
            // Optional staff/tech who performed the action
            $table->unsignedBigInteger('performed_by')->nullable();

            // Costing (optional but recommended)
            $table->decimal('labor_cost', 12, 2)->default(0.00);
            $table->decimal('material_cost', 12, 2)->default(0.00);

            // Attachments per log entry
            $table->string('attachment_path')->nullable();

            $table->dateTime('logged_at');

            $table->foreign('request_id')->references('request_id')->on('maintenance_rqst')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
