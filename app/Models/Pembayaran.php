<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'tagihan_id',
        'santri_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode',
        'bukti_pembayaran',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kasTransaction()
    {
        return $this->hasOne(KasTransaction::class);
    }

    public function konfirmasiPembayaran()
    {
        return $this->hasOne(KonfirmasiPembayaran::class);
    }
}
