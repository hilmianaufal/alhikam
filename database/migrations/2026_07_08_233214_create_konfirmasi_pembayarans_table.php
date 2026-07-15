<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfirmasi_pembayarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tagihan_id')
                ->constrained('tagihans')
                ->cascadeOnDelete();

            $table->foreignId('santri_id')
                ->constrained('santris')
                ->cascadeOnDelete();

            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);

            $table->enum('metode', ['transfer', 'qris', 'lainnya'])
                ->default('transfer');

            $table->string('bukti_pembayaran');
            $table->text('keterangan')->nullable();

            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])
                ->default('menunggu');

            $table->text('catatan_admin')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->foreignId('pembayaran_id')
                ->nullable()
                ->constrained('pembayarans')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfirmasi_pembayarans');
    }
};
