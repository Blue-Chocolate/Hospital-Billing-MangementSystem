<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('filename');
            $table->string('path');
            $table->timestamps(); // created_at will store upload time
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_files');
    }
};
