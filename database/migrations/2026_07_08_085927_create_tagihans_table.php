<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('santri_id')
                ->constrained('santris')
                ->cascadeOnDelete();

            $table->foreignId('jenis_pembayaran_id')
                ->constrained('jenis_pembayarans')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->constrained('tahun_ajarans')
                ->nullOnDelete();

            $table->unsignedTinyInteger('bulan')->nullable();
            $table->year('tahun')->nullable();

            $table->decimal('nominal', 15, 2)->default(0);
            $table->decimal('dibayar', 15, 2)->default(0);

            $table->date('tanggal_jatuh_tempo')->nullable();

            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])
                ->default('belum_lunas');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(
                ['santri_id', 'jenis_pembayaran_id', 'tahun_ajaran_id', 'bulan', 'tahun'],
                'tagihan_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
