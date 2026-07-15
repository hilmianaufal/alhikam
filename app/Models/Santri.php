<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'foto',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'no_hp_ayah',
        'no_hp_ibu',
        'no_hp_wali',
        'alamat',
        'tanggal_masuk',
        'kelas_id',
        'asrama_id',
        'status_mukim',
        'qr_token',
        'user_id',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function konfirmasiPembayarans()
    {
        return $this->hasMany(KonfirmasiPembayaran::class);
    }
}
