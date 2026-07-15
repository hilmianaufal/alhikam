<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'nominal',
        'tipe',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function tagihans()
{
    return $this->hasMany(Tagihan::class);
}
}
