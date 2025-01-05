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
        Schema::create('user_entertains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_account_manager', 100);
            $table->string('nama_kanwil_manager', 100);
            $table->string('hari', 10);
            $table->date('tanggal');
            $table->time('waktu');
            $table->foreignId('type_id')->constrained('types')->cascadeOnDelete();
            $table->unsignedBigInteger('nilai_entertain');
            $table->unsignedBigInteger('revenue');
            $table->string('pelanggan');
            $table->text('topik')->nullable();
            $table->text('aktivitas')->nullable();
            $table->text('target_pelaksanaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_entertains');
    }
};
