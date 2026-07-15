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
    Schema::table('santris', function (Blueprint $table) {
        $table->string('nisn')->nullable()->after('nis');
        $table->string('nama_panggilan')->nullable()->after('nama');
        $table->string('agama')->default('Islam')->after('tanggal_lahir');
        $table->string('foto')->nullable()->after('agama');

        $table->string('nama_ayah')->nullable()->after('foto');
        $table->string('nama_ibu')->nullable()->after('nama_ayah');
        $table->string('no_hp_ayah')->nullable()->after('nama_ibu');
        $table->string('no_hp_ibu')->nullable()->after('no_hp_ayah');

        $table->date('tanggal_masuk')->nullable()->after('alamat');
        $table->enum('status_mukim', ['mukim', 'non_mukim'])->default('mukim')->after('tanggal_masuk');

        $table->string('qr_token')->nullable()->unique()->after('status_mukim');
        $table->foreignId('user_id')->nullable()->after('qr_token')->constrained()->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('santris', function (Blueprint $table) {
        $table->dropForeign(['user_id']);

        $table->dropColumn([
            'nisn',
            'nama_panggilan',
            'agama',
            'foto',
            'nama_ayah',
            'nama_ibu',
            'no_hp_ayah',
            'no_hp_ibu',
            'tanggal_masuk',
            'status_mukim',
            'qr_token',
            'user_id',
        ]);
    });
}
};
