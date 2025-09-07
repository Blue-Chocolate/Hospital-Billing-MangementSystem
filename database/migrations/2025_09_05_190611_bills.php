<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('bills', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
    $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
    $table->decimal('amount', 10, 2);
    $table->date('bill_date');
    $table->text('description')->nullable();
    $table->boolean('is_anomaly')->default(false);
    $table->string('insurance_provider')->nullable();
    $table->decimal('insurance_coverage', 10, 2)->default(0);
    $table->string('payment_status')->default('Pending');
        $table->foreignId('user_id')->nullable()->constrained('employees')->onDelete('set null');

    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
