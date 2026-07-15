<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::findByName('Super Admin');
        $pengurus   = Role::findByName('Pengurus');
        $wali       = Role::findByName('Wali Santri');

        // Super Admin mendapatkan semua permission
        $superAdmin->syncPermissions(Permission::all());

        // Pengurus
        $pengurus->syncPermissions([
            'dashboard.view',

            'santri.view',
            'santri.create',
            'santri.update',

            'wali-santri.view',
            'wali-santri.create',
            'wali-santri.update',

            'jenis-pembayaran.view',
            'jenis-pembayaran.create',
            'jenis-pembayaran.update',

            'tagihan.view',
            'tagihan.create',
            'tagihan.update',
            'asrama.view',
            'asrama.create',
            'asrama.update',
            'tahun-ajaran.view',
            'tahun-ajaran.create',
            'tahun-ajaran.update',
            'pembayaran.view',
            'pembayaran.create',
            'pembayaran.update',
            'kelas.view',
            'kelas.create',
            'kelas.update',
            'laporan.view',
            'laporan.export',
        ]);

        // Wali Santri
        $wali->syncPermissions([
            'dashboard.view',
            'tagihan.view',
            'pembayaran.view',
        ]);
    }
}
