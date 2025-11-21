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
        Schema::create('property_units', function (Blueprint $table) {
            $table->id('unit_id');
            $table->unsignedBigInteger('prop_id');
            $table->string('unit_name');
            $table->integer('unit_num');
            $table->string('unit_type');
            $table->string('area_sqm');
            $table->decimal('unit_price');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved', 'rented', 'unavailable'])->default('available'); 
            $table->foreign('prop_id')->references('prop_id')->on('properties')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};
