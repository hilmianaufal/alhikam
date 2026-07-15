<?php

namespace Database\Seeders;

use App\Models\Asrama;
use App\Models\JenisPembayaran;
use App\Models\KasTransaction;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $superAdminRole = Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ]);

            $pengurusRole = Role::firstOrCreate([
                'name' => 'Pengurus',
                'guard_name' => 'web',
            ]);

            $waliRole = Role::firstOrCreate([
                'name' => 'Wali Santri',
                'guard_name' => 'web',
            ]);

            $admin = User::updateOrCreate(
                ['email' => 'admin@alishlahpay.test'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('password'),
                ]
            );

            $admin->syncRoles([$superAdminRole->name]);

            $pengurus = User::updateOrCreate(
                ['email' => 'pengurus@alishlahpay.test'],
                [
                    'name' => 'Pengurus Pondok',
                    'password' => Hash::make('password'),
                ]
            );

            $pengurus->syncRoles([$pengurusRole->name]);

            $kelas = $this->seedKelas();
            $asramas = $this->seedAsrama();
            $tahunAjarans = $this->seedTahunAjaran();
            $jenisPembayarans = $this->seedJenisPembayaran();
            $waliUsers = $this->seedWaliUsers($waliRole);
            $santris = $this->seedSantri($kelas, $asramas, $waliUsers);

            $tahunAktif = TahunAjaran::where('is_active', true)->first()
                ?? $tahunAjarans->first();

            $tagihans = $this->seedTagihan($santris, $jenisPembayarans, $tahunAktif);

            $this->seedPembayaranDanKas($tagihans, $admin);
            $this->seedKasManual($admin);
        });
    }

    private function seedKelas()
    {
        $data = [
            ['nama_kelas' => 'Kelas 7A', 'tingkat' => '7', 'wali_kelas' => 'Ust. Ahmad', 'status' => 'aktif'],
            ['nama_kelas' => 'Kelas 7B', 'tingkat' => '7', 'wali_kelas' => 'Ust. Budi', 'status' => 'aktif'],
            ['nama_kelas' => 'Kelas 8A', 'tingkat' => '8', 'wali_kelas' => 'Ust. Hasan', 'status' => 'aktif'],
            ['nama_kelas' => 'Kelas 8B', 'tingkat' => '8', 'wali_kelas' => 'Ust. Fajar', 'status' => 'aktif'],
            ['nama_kelas' => 'Kelas 9A', 'tingkat' => '9', 'wali_kelas' => 'Ust. Ridwan', 'status' => 'aktif'],
        ];

        return collect($data)->map(function ($item) {
            return Kelas::updateOrCreate(
                ['nama_kelas' => $item['nama_kelas']],
                $item
            );
        });
    }

    private function seedAsrama()
    {
        $data = [
            ['nama_asrama' => 'Asrama Abu Bakar', 'kode_asrama' => 'ASR-001', 'musyrif' => 'Ust. Farhan', 'kapasitas' => '30 Santri', 'status' => 'aktif'],
            ['nama_asrama' => 'Asrama Umar', 'kode_asrama' => 'ASR-002', 'musyrif' => 'Ust. Yusuf', 'kapasitas' => '35 Santri', 'status' => 'aktif'],
            ['nama_asrama' => 'Asrama Utsman', 'kode_asrama' => 'ASR-003', 'musyrif' => 'Ust. Rafi', 'kapasitas' => '28 Santri', 'status' => 'aktif'],
            ['nama_asrama' => 'Asrama Ali', 'kode_asrama' => 'ASR-004', 'musyrif' => 'Ust. Lukman', 'kapasitas' => '32 Santri', 'status' => 'aktif'],
            ['nama_asrama' => 'Asrama Bilal', 'kode_asrama' => 'ASR-005', 'musyrif' => 'Ust. Salman', 'kapasitas' => '25 Santri', 'status' => 'aktif'],
        ];

        return collect($data)->map(function ($item) {
            return Asrama::updateOrCreate(
                ['kode_asrama' => $item['kode_asrama']],
                $item
            );
        });
    }

    private function seedTahunAjaran()
    {
        $data = [
            ['nama_tahun' => '2024/2025', 'semester' => 'ganjil', 'tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => '2024-12-31', 'is_active' => false, 'status' => 'aktif'],
            ['nama_tahun' => '2024/2025', 'semester' => 'genap', 'tanggal_mulai' => '2025-01-01', 'tanggal_selesai' => '2025-06-30', 'is_active' => false, 'status' => 'aktif'],
            ['nama_tahun' => '2025/2026', 'semester' => 'ganjil', 'tanggal_mulai' => '2025-07-01', 'tanggal_selesai' => '2025-12-31', 'is_active' => true, 'status' => 'aktif'],
            ['nama_tahun' => '2025/2026', 'semester' => 'genap', 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-06-30', 'is_active' => false, 'status' => 'aktif'],
            ['nama_tahun' => '2026/2027', 'semester' => 'ganjil', 'tanggal_mulai' => '2026-07-01', 'tanggal_selesai' => '2026-12-31', 'is_active' => false, 'status' => 'aktif'],
        ];

        return collect($data)->map(function ($item) {
            return TahunAjaran::updateOrCreate(
                [
                    'nama_tahun' => $item['nama_tahun'],
                    'semester' => $item['semester'],
                ],
                $item
            );
        });
    }

    private function seedJenisPembayaran()
    {
        $data = [
            ['kode' => 'SYH', 'nama' => 'Syahriyah', 'nominal' => 350000, 'tipe' => 'bulanan', 'deskripsi' => 'Iuran bulanan santri', 'status' => 'aktif'],
            ['kode' => 'MKN', 'nama' => 'Uang Makan', 'nominal' => 500000, 'tipe' => 'bulanan', 'deskripsi' => 'Biaya makan santri', 'status' => 'aktif'],
            ['kode' => 'KTB', 'nama' => 'Kitab', 'nominal' => 250000, 'tipe' => 'sekali', 'deskripsi' => 'Pembelian kitab pembelajaran', 'status' => 'aktif'],
            ['kode' => 'SRG', 'nama' => 'Seragam', 'nominal' => 400000, 'tipe' => 'sekali', 'deskripsi' => 'Biaya seragam santri', 'status' => 'aktif'],
            ['kode' => 'INF', 'nama' => 'Infaq Pondok', 'nominal' => 100000, 'tipe' => 'bebas', 'deskripsi' => 'Infaq sukarela wali santri', 'status' => 'aktif'],
        ];

        return collect($data)->map(function ($item) {
            return JenisPembayaran::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        });
    }

    private function seedWaliUsers(Role $waliRole)
    {
        return collect(range(1, 5))->map(function ($i) use ($waliRole) {
            $user = User::updateOrCreate(
                ['email' => "wali{$i}@alishlahpay.test"],
                [
                    'name' => "Wali Santri {$i}",
                    'password' => Hash::make('password'),
                ]
            );

            $user->syncRoles([$waliRole->name]);

            return $user;
        });
    }

    private function seedSantri($kelas, $asramas, $waliUsers)
    {
        $data = [
            [
                'nis' => 'S001',
                'nisn' => '1000000001',
                'nama' => 'Muhammad Fikri',
                'nama_panggilan' => 'Fikri',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Subang',
                'tanggal_lahir' => '2011-03-12',
                'nama_ayah' => 'Bapak Rahmat',
                'nama_ibu' => 'Ibu Siti',
                'nama_wali' => 'Bapak Rahmat',
                'no_hp_ayah' => '081111111101',
                'no_hp_ibu' => '081111111201',
                'no_hp_wali' => '081111111001',
            ],
            [
                'nis' => 'S002',
                'nisn' => '1000000002',
                'nama' => 'Ahmad Fauzan',
                'nama_panggilan' => 'Fauzan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2011-05-20',
                'nama_ayah' => 'Bapak Dedi',
                'nama_ibu' => 'Ibu Lina',
                'nama_wali' => 'Bapak Dedi',
                'no_hp_ayah' => '081111111102',
                'no_hp_ibu' => '081111111202',
                'no_hp_wali' => '081111111002',
            ],
            [
                'nis' => 'S003',
                'nisn' => '1000000003',
                'nama' => 'Siti Aisyah',
                'nama_panggilan' => 'Aisyah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Purwakarta',
                'tanggal_lahir' => '2010-08-14',
                'nama_ayah' => 'Bapak Hendra',
                'nama_ibu' => 'Ibu Rina',
                'nama_wali' => 'Ibu Rina',
                'no_hp_ayah' => '081111111103',
                'no_hp_ibu' => '081111111203',
                'no_hp_wali' => '081111111003',
            ],
            [
                'nis' => 'S004',
                'nisn' => '1000000004',
                'nama' => 'Nabila Zahra',
                'nama_panggilan' => 'Zahra',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Subang',
                'tanggal_lahir' => '2012-01-22',
                'nama_ayah' => 'Bapak Ujang',
                'nama_ibu' => 'Ibu Neni',
                'nama_wali' => 'Bapak Ujang',
                'no_hp_ayah' => '081111111104',
                'no_hp_ibu' => '081111111204',
                'no_hp_wali' => '081111111004',
            ],
            [
                'nis' => 'S005',
                'nisn' => '1000000005',
                'nama' => 'Rizky Maulana',
                'nama_panggilan' => 'Rizky',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Karawang',
                'tanggal_lahir' => '2010-11-02',
                'nama_ayah' => 'Bapak Agus',
                'nama_ibu' => 'Ibu Yanti',
                'nama_wali' => 'Bapak Agus',
                'no_hp_ayah' => '081111111105',
                'no_hp_ibu' => '081111111205',
                'no_hp_wali' => '081111111005',
            ],
        ];

        return collect($data)->values()->map(function ($item, $index) use ($kelas, $asramas, $waliUsers) {
            return Santri::updateOrCreate(
                ['nis' => $item['nis']],
                array_merge($item, [
                    'agama' => 'Islam',
                    'alamat' => 'Jatireja, Subang',
                    'tanggal_masuk' => '2025-07-01',
                    'kelas_id' => $kelas[$index]->id,
                    'asrama_id' => $asramas[$index]->id,
                    'status_mukim' => 'mukim',
                    'qr_token' => (string) Str::uuid(),
                    'user_id' => $waliUsers[$index]->id,
                    'status' => 'aktif',
                ])
            );
        });
    }

    private function seedTagihan($santris, $jenisPembayarans, TahunAjaran $tahunAktif)
    {
        return $santris->values()->map(function ($santri, $index) use ($jenisPembayarans, $tahunAktif) {
            $jenis = $jenisPembayarans[$index];

            return Tagihan::updateOrCreate(
                [
                    'santri_id' => $santri->id,
                    'jenis_pembayaran_id' => $jenis->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'bulan' => now()->month,
                    'tahun' => now()->year,
                ],
                [
                    'nominal' => $jenis->nominal,
                    'dibayar' => 0,
                    'tanggal_jatuh_tempo' => now()->endOfMonth()->toDateString(),
                    'status' => 'belum_lunas',
                    'keterangan' => 'Tagihan contoh dummy data',
                ]
            );
        });
    }

    private function seedPembayaranDanKas($tagihans, User $admin): void
    {
        $nominalBayar = [150000, 250000, 250000, 400000, 50000];
        $metode = ['tunai', 'transfer', 'qris', 'tunai', 'lainnya'];

        $tagihans->values()->each(function ($tagihan, $index) use ($nominalBayar, $metode, $admin) {
            $jumlahBayar = min($nominalBayar[$index], (float) $tagihan->nominal);

            $pembayaran = Pembayaran::updateOrCreate(
                ['kode_transaksi' => 'PAY-DUMMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'tagihan_id' => $tagihan->id,
                    'santri_id' => $tagihan->santri_id,
                    'tanggal_bayar' => now()->subDays(5 - $index)->toDateString(),
                    'jumlah_bayar' => $jumlahBayar,
                    'metode' => $metode[$index],
                    'keterangan' => 'Pembayaran contoh dummy data',
                    'created_by' => $admin->id,
                ]
            );

            $totalDibayar = Pembayaran::where('tagihan_id', $tagihan->id)->sum('jumlah_bayar');

            $tagihan->update([
                'dibayar' => $totalDibayar,
                'status' => $totalDibayar >= $tagihan->nominal
                    ? 'lunas'
                    : ($totalDibayar > 0 ? 'sebagian' : 'belum_lunas'),
            ]);

            KasTransaction::updateOrCreate(
                ['kode' => 'KAS-PAY-DUMMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'tanggal' => $pembayaran->tanggal_bayar,
                    'tipe' => 'pemasukan',
                    'kategori' => 'Pembayaran Santri',
                    'nominal' => $pembayaran->jumlah_bayar,
                    'metode' => $pembayaran->metode,
                    'sumber' => 'pembayaran',
                    'pembayaran_id' => $pembayaran->id,
                    'keterangan' => 'Kas otomatis dari pembayaran dummy',
                    'created_by' => $admin->id,
                ]
            );
        });
    }

    private function seedKasManual(User $admin): void
    {
        $data = [
            ['kode' => 'KAS-DUMMY-001', 'tanggal' => now()->subDays(4)->toDateString(), 'tipe' => 'pemasukan', 'kategori' => 'Donasi Wali Santri', 'nominal' => 1000000, 'metode' => 'transfer', 'sumber' => 'manual', 'keterangan' => 'Donasi contoh'],
            ['kode' => 'KAS-DUMMY-002', 'tanggal' => now()->subDays(3)->toDateString(), 'tipe' => 'pengeluaran', 'kategori' => 'Konsumsi Santri', 'nominal' => 450000, 'metode' => 'tunai', 'sumber' => 'manual', 'keterangan' => 'Belanja konsumsi'],
            ['kode' => 'KAS-DUMMY-003', 'tanggal' => now()->subDays(2)->toDateString(), 'tipe' => 'pengeluaran', 'kategori' => 'Operasional Pondok', 'nominal' => 300000, 'metode' => 'tunai', 'sumber' => 'manual', 'keterangan' => 'Biaya operasional'],
            ['kode' => 'KAS-DUMMY-004', 'tanggal' => now()->subDay()->toDateString(), 'tipe' => 'pemasukan', 'kategori' => 'Infaq Jamaah', 'nominal' => 750000, 'metode' => 'qris', 'sumber' => 'manual', 'keterangan' => 'Infaq contoh'],
            ['kode' => 'KAS-DUMMY-005', 'tanggal' => now()->toDateString(), 'tipe' => 'pengeluaran', 'kategori' => 'Perlengkapan Kelas', 'nominal' => 250000, 'metode' => 'transfer', 'sumber' => 'manual', 'keterangan' => 'Pembelian alat tulis'],
        ];

        foreach ($data as $item) {
            KasTransaction::updateOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, [
                    'created_by' => $admin->id,
                ])
            );
        }
    }
}
