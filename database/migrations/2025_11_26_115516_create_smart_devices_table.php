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
        Schema::create('smart_devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->unsignedBigInteger('prop_id');

            $table->string('device_name');                // e.g. Smart Thermostat
            $table->string('device_type');                // thermostat, camera, lock, lights, etc.
            $table->string('model')->nullable();          // optional model number
            $table->string('serial_number')->nullable();  // device unique ID

            // Device Status
            $table->enum('connection_status', ['online', 'offline'])->default('offline');
            $table->enum('power_status', ['on', 'off'])->default('off');

            $table->integer('battery_level')->nullable();  // 0 - 100

            $table->timestamps();

            $table->foreign('prop_id')->references('prop_id')->on('properties')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_devices');
    }
};
