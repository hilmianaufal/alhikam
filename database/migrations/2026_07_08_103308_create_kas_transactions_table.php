<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->date('tanggal');

            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->string('kategori');
            $table->decimal('nominal', 15, 2);

            $table->enum('metode', ['tunai', 'transfer', 'qris', 'lainnya'])
                ->default('tunai');

            $table->enum('sumber', ['manual', 'pembayaran'])
                ->default('manual');

            $table->foreignId('pembayaran_id')
                ->nullable()
                ->constrained('pembayarans')
                ->nullOnDelete();

            $table->text('keterangan')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_transactions');
    }
};
