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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('prop_id');
            $table->unsignedBigInteger('user_id'); //landlord
            $table->string('property_name');
            $table->string('property_address');
            $table->string('property_type');
            $table->decimal('property_price');
            $table->string('property_description');
            $table->string('property_image');
            $table->enum('status', ['active', 'inactive'])->default('active'); 
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
