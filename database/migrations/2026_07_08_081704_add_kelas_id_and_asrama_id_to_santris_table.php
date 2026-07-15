<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->after('tanggal_masuk')
                ->constrained('kelas')
                ->nullOnDelete();

            $table->foreignId('asrama_id')
                ->nullable()
                ->after('kelas_id')
                ->constrained('asramas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['asrama_id']);

            $table->dropColumn([
                'kelas_id',
                'asrama_id',
            ]);
        });
    }
};
