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
        Schema::create('property_assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->unsignedBigInteger('prop_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('asset_name');
            $table->string('asset_type');
            $table->date('last_checked');
            $table->string('notes');
            $table->enum('condition_status', ['operational','need_maintenance','out_of_service','under_repair','locked','unlocked'])->default('operational');
            
            $table->foreign('unit_id')->references('unit_id')->on('property_units')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('prop_id')->references('prop_id')->on('properties')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_assets');
    }
};
