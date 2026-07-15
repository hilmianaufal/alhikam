<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfirmasiPembayaran extends Model
{
    protected $fillable = [
        'tagihan_id',
        'santri_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode',
        'bukti_pembayaran',
        'keterangan',
        'status',
        'catatan_admin',
        'verified_by',
        'verified_at',
        'pembayaran_id',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
