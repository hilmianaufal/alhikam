<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_kelas',
        'status',
    ];

    public function santris()
{
    return $this->hasMany(Santri::class, 'kelas_id');
}
}
