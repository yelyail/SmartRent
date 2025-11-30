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
        Schema::create('leases', function (Blueprint $table) {
            $table->id('lease_id');
            $table->unsignedBigInteger('user_id'); // tenant
            $table->unsignedBigInteger('prop_id');
            $table->unsignedBigInteger('unit_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount');
            $table->decimal('deposit_amount');            
            $table->enum('status', ['approved', 'terminated', 'activate', 'pending', 'inactive'])->default('pending'); 
            
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('prop_id')->references('prop_id')->on('properties')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('unit_id')->references('unit_id')->on('property_units')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
