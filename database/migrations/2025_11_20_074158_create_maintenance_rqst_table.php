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
        Schema::create('maintenance_rqst', function (Blueprint $table) {
            $table->id('request_id');

            // Tied to tenant/lease
            $table->unsignedBigInteger('user_id')->nullable();

            // Tied to unit/property
            $table->unsignedBigInteger('unit_id')->nullable();

            $table->string('title', 150);       
            $table->text('description');      
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');

            $table->enum('status', ['pending','approved','in_progress','completed','cancelled'])->default('pending');

            // Optional assignment fields
            $table->unsignedBigInteger('assigned_staff_id')->nullable();

            // Date tracking
            $table->dateTime('requested_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Foreign keys
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('assigned_staff_id')->references('user_id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('unit_id')->references('unit_id')->on('property_units')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_rqst');
    }
};
