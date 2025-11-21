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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('audit_id');

            // Actor
            $table->unsignedInteger('user_id')->nullable(); 
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->string('user_agent')->nullable();    // Browser/device

            // Target (record being affected)
            $table->string('table_name', 100);

            // Action type
            $table->enum('action', ['create','update','delete','login','logout','status_change','system'])->default('update');
            // Additional info
            $table->text('remarks')->nullable(); // Optional: triggered by system, batch job, etc.

            $table->dateTime('created_at'); 
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
