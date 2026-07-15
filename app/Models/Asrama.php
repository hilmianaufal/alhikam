<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    protected $fillable = [
        'nama_asrama',
        'kode_asrama',
        'musyrif',
        'kapasitas',
        'status',
    ];

    public function santris()
{
    return $this->hasMany(Santri::class, 'asrama_id');
}
}
