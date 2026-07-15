<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasTransaction extends Model
{
    protected $fillable = [
        'kode',
        'tanggal',
        'tipe',
        'kategori',
        'nominal',
        'metode',
        'sumber',
        'pembayaran_id',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
