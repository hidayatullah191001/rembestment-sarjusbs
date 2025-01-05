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
        Schema::create('budget_relocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_province')->constrained('provinces', 'id')->cascadeOnDelete();
            $table->foreignId('to_province')->constrained('provinces', 'id')->cascadeOnDelete();
            $table->unsignedBigInteger('amount_relocation');
            $table->unsignedBigInteger('start_amount');
            $table->unsignedBigInteger('final_amount');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_true')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('to_start_amount')->nullable();
            $table->unsignedBigInteger('to_final_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_relocations');
    }
};