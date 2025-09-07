<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->integer('quantity')->default(1); // How many items used
            $table->decimal('cost', 10, 2)->default(0); // Cost per item
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_inventory');
    }
};
