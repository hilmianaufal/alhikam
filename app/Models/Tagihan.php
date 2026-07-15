<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'santri_id',
        'jenis_pembayaran_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'nominal',
        'dibayar',
        'tanggal_jatuh_tempo',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'dibayar' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function getSisaAttribute()
    {
        return max($this->nominal - $this->dibayar, 0);
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
