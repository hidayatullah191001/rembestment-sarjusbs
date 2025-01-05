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
        Schema::create('budget_relocation_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_relocation_id')->constrained('budget_relocations', 'id')->cascadeOnDelete();
            $table->foreignId('budget_from_id')->constrained('budgets', 'id')->cascadeOnDelete();
            $table->foreignId('budget_to_id')->constrained('budgets', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_relocation_relations');
    }
};
