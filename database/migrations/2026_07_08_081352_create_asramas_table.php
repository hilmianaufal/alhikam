<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asramas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asrama');
            $table->string('kode_asrama')->nullable()->unique();
            $table->string('musyrif')->nullable();
            $table->string('kapasitas')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asramas');
    }
};
