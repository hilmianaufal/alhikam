<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',

            'santri.view',
            'santri.create',
            'santri.update',
            'santri.delete',
            'kelas.view',
            'kelas.create',
            'kelas.update',
            'kelas.delete',
            'wali-santri.view',
            'wali-santri.create',
            'wali-santri.update',
            'wali-santri.delete',
            'asrama.view',
            'asrama.create',
            'asrama.update',
            'asrama.delete',
            'tagihan.view',
            'tagihan.create',
            'tagihan.update',
            'tagihan.delete',

            'jenis-pembayaran.view',
            'jenis-pembayaran.create',
            'jenis-pembayaran.update',
            'jenis-pembayaran.delete',

            'kas.view',
            'kas.create',
            'kas.update',
            'kas.delete',

            'tahun-ajaran.view',
            'tahun-ajaran.create',
            'tahun-ajaran.update',
            'tahun-ajaran.delete',

            'pembayaran.view',
            'pembayaran.create',
            'pembayaran.update',
            'pembayaran.delete',

            'laporan.view',
            'laporan.export',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',

            'setting.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
