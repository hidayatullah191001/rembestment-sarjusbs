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
        Schema::create('user_entertain_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_entertain_id')->constrained('user_entertains')->cascadeOnDelete();
            $table->string('nama_pelanggan');
            $table->string('internal_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_entertain_pesertas');
    }
};
