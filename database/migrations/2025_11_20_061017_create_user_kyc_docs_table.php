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
        Schema::create('user_kyc_docs', function (Blueprint $table) {
            $table->id('kyc_id');
            $table->unsignedBigInteger('user_id');       // landlord or tenant
            $table->unsignedBigInteger('reviewed_by')->nullable();   // admin

            $table->string('doc_type');
            $table->string('doc_name');
            $table->string('doc_path');
            $table->string('proof_of_income')->nullable();

            $table->enum('status', ['pending', 'approved', 'reject'])->default('pending'); 

            $table->foreign('user_id')
                ->references('user_id')->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete(); 

            $table->foreign('reviewed_by')
                ->references('user_id')->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kyc_docs');
    }
};
