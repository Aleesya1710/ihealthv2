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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('ICNumber')->unique();
            $table->string('studentID')->nullable()->unique();
            $table->string('faculty');
            $table->string('phoneNumber');
            $table->string('program')->nullable();
            $table->string('staffID')->nullable()->unique();
            $table->enum('category', ['student', 'staff', 'public'])->default('public');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
