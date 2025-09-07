<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('medical_history')->nullable();
            $table->date('last_visit')->nullable();
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete(); // ✅ ensures cascade delete
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete(); // ✅ ensures cascade delete
            $table->dateTime('appointment_time');
            $table->string('status')->default('Scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop appointments first because it references patients
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('patients');
    }
};
