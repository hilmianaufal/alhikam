<?php

use App\Http\Controllers\Admin\AsramaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\JenisPembayaranController;
use App\Http\Controllers\Admin\KasTransactionController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\SantriImportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WaliSantriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KonfirmasiPembayaranController;
use App\Http\Controllers\Wali\KonfirmasiPembayaranController as WaliKonfirmasiPembayaranController;
use App\Http\Controllers\Wali\TagihanController as WaliTagihanController;
use App\Http\Controllers\Wali\RiwayatPembayaranController as WaliRiwayatPembayaranController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'role:Super Admin|Pengurus'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.view')
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Santri Import - harus sebelum Route::resource('santri')
        |--------------------------------------------------------------------------
        */
        Route::get('santri/import', [SantriImportController::class, 'create'])
            ->name('santri.import')
            ->middleware('permission:santri.create');

        Route::post('santri/import', [SantriImportController::class, 'store'])
            ->name('santri.import.store')
            ->middleware('permission:santri.create');

        Route::get('santri/import/template', [SantriImportController::class, 'template'])
            ->name('santri.import.template')
            ->middleware('permission:santri.create');

        Route::resource('santri', SantriController::class)
            ->middleware('permission:santri.view');

        /*
        |--------------------------------------------------------------------------
        | Master Data
        |--------------------------------------------------------------------------
        */
        Route::resource('kelas', KelasController::class)
            ->except(['show'])
            ->middleware('permission:kelas.view');

        Route::resource('asrama', AsramaController::class)
            ->except(['show'])
            ->middleware('permission:asrama.view');

        Route::resource('tahun-ajaran', TahunAjaranController::class)
            ->parameters(['tahun-ajaran' => 'tahunAjaran'])
            ->except(['show'])
            ->middleware('permission:tahun-ajaran.view');

        Route::resource('jenis-pembayaran', JenisPembayaranController::class)
            ->parameters(['jenis-pembayaran' => 'jenisPembayaran'])
            ->except(['show'])
            ->middleware('permission:jenis-pembayaran.view');

        /*
        |--------------------------------------------------------------------------
        | Wali Santri
        |--------------------------------------------------------------------------
        */
        Route::get('wali-santri', [WaliSantriController::class, 'index'])
            ->name('wali-santri.index')
            ->middleware('permission:wali-santri.view');

        Route::get('wali-santri/{santri}/edit', [WaliSantriController::class, 'edit'])
            ->name('wali-santri.edit')
            ->middleware('permission:wali-santri.update');

        Route::put('wali-santri/{santri}', [WaliSantriController::class, 'update'])
            ->name('wali-santri.update')
            ->middleware('permission:wali-santri.update');

        Route::get('konfirmasi-pembayaran', [KonfirmasiPembayaranController::class, 'index'])
            ->name('konfirmasi-pembayaran.index')
            ->middleware('permission:pembayaran.view');

        Route::get('konfirmasi-pembayaran/{konfirmasiPembayaran}', [KonfirmasiPembayaranController::class, 'show'])
            ->name('konfirmasi-pembayaran.show')
            ->middleware('permission:pembayaran.view');

        Route::patch('konfirmasi-pembayaran/{konfirmasiPembayaran}/approve', [KonfirmasiPembayaranController::class, 'approve'])
            ->name('konfirmasi-pembayaran.approve')
            ->middleware('permission:pembayaran.create');

        Route::patch('konfirmasi-pembayaran/{konfirmasiPembayaran}/reject', [KonfirmasiPembayaranController::class, 'reject'])
            ->name('konfirmasi-pembayaran.reject')
            ->middleware('permission:pembayaran.create');

        /*
        |--------------------------------------------------------------------------
        | Keuangan
        |--------------------------------------------------------------------------
        */
        Route::resource('tagihan', TagihanController::class)
            ->middleware('permission:tagihan.view');

        Route::get('pembayaran/{pembayaran}/struk', [PembayaranController::class, 'struk'])
            ->name('pembayaran.struk')
            ->middleware('permission:pembayaran.view');

        Route::resource('pembayaran', PembayaranController::class)
            ->middleware('permission:pembayaran.view');

        Route::resource('kas', KasTransactionController::class)
            ->parameters(['kas' => 'kasTransaction'])
            ->except(['show'])
            ->middleware('permission:kas.view');

        /*
        |--------------------------------------------------------------------------
        | Laporan
        |--------------------------------------------------------------------------
        */
        Route::get('laporan/tunggakan', [LaporanController::class, 'tunggakan'])
            ->name('laporan.tunggakan')
            ->middleware('permission:laporan.view');

        Route::get('laporan/tunggakan/print', [LaporanController::class, 'printTunggakan'])
            ->name('laporan.tunggakan.print')
            ->middleware('permission:laporan.export');

        Route::get('laporan/kartu-santri', [LaporanController::class, 'kartuSantri'])
            ->name('laporan.kartu-santri')
            ->middleware('permission:laporan.view');

        Route::get('laporan/kartu-santri/{santri}/print', [LaporanController::class, 'printKartuSantri'])
            ->name('laporan.kartu-santri.print')
            ->middleware('permission:laporan.export');

        Route::get('laporan/print', [LaporanController::class, 'print'])
            ->name('laporan.print')
            ->middleware('permission:laporan.export');

        Route::get('laporan', [LaporanController::class, 'index'])
            ->name('laporan.index')
            ->middleware('permission:laporan.view');

        /*
        |--------------------------------------------------------------------------
        | Export Excel
        |--------------------------------------------------------------------------
        */
        Route::get('export/santri', [ExportController::class, 'santri'])
            ->name('export.santri')
            ->middleware('permission:laporan.export');

        Route::get('export/tagihan', [ExportController::class, 'tagihan'])
            ->name('export.tagihan')
            ->middleware('permission:laporan.export');

        Route::get('export/pembayaran', [ExportController::class, 'pembayaran'])
            ->name('export.pembayaran')
            ->middleware('permission:laporan.export');

        Route::get('export/kas', [ExportController::class, 'kas'])
            ->name('export.kas')
            ->middleware('permission:laporan.export');

        Route::get('export/tunggakan', [ExportController::class, 'tunggakan'])
            ->name('export.tunggakan')
            ->middleware('permission:laporan.export');

        /*
        |--------------------------------------------------------------------------
        | User & Permission
        |--------------------------------------------------------------------------
        */
        Route::resource('user', UserController::class)
            ->except(['show'])
            ->middleware('permission:user.view');

        Route::get('role-permission', [RolePermissionController::class, 'index'])
            ->name('role-permission.index')
            ->middleware('role:Super Admin');

        Route::get('role-permission/{role}/edit', [RolePermissionController::class, 'edit'])
            ->name('role-permission.edit')
            ->middleware('role:Super Admin');

        Route::put('role-permission/{role}', [RolePermissionController::class, 'update'])
            ->name('role-permission.update')
            ->middleware('role:Super Admin');

        /*
        |--------------------------------------------------------------------------
        | Setting
        |--------------------------------------------------------------------------
        */
        Route::get('setting', [SettingController::class, 'edit'])
            ->name('setting.edit')
            ->middleware('permission:setting.manage');

        Route::put('setting', [SettingController::class, 'update'])
            ->name('setting.update')
            ->middleware('permission:setting.manage');
    });

Route::middleware(['auth', 'verified', 'role:Wali Santri'])
    ->prefix('wali')
    ->name('wali.')
    ->group(function () {
        Route::get('/dashboard', [WaliDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('tagihan', [WaliTagihanController::class, 'index'])
            ->name('tagihan.index');

        Route::get('tagihan/{tagihan}', [WaliTagihanController::class, 'show'])
            ->name('tagihan.show');

        Route::get('pembayaran', [WaliRiwayatPembayaranController::class, 'index'])
            ->name('pembayaran.index');

        Route::get('konfirmasi-pembayaran', [WaliKonfirmasiPembayaranController::class, 'create'])
            ->name('konfirmasi-pembayaran.create');

        Route::post('konfirmasi-pembayaran', [WaliKonfirmasiPembayaranController::class, 'store'])
            ->name('konfirmasi-pembayaran.store');

        Route::get('konfirmasi-pembayaran/{konfirmasiPembayaran}', [WaliKonfirmasiPembayaranController::class, 'show'])
            ->name('konfirmasi-pembayaran.show');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
