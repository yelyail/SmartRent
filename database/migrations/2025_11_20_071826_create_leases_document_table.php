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
        Schema::create('leases_document', function (Blueprint $table) {
            $table->id('lease_doc_id');
            $table->unsignedBigInteger('lease_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->foreign('lease_id')->references('lease_id')->on('leases')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases_document');
    }
};
