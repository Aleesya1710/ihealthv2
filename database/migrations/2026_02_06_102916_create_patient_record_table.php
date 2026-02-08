<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patientRecord', function (Blueprint $table) {
            $table->id();
            $table->string('place_of_injury')->nullable();
            $table->json('symptoms')->nullable();
            $table->json('type_of_injury')->nullable();
            $table->json('diagnosis')->nullable();
            $table->json('treatment')->nullable();
            $table->text('notes')->nullable();
            $table->string('referral_letter')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_record');
    }
};
