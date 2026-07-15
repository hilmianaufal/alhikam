<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', auth()->id())
            ->get();

        $santriIds = $santris->pluck('id');

        $totalTagihan = Tagihan::whereIn('santri_id', $santriIds)->sum('nominal');

        $totalDibayar = Tagihan::whereIn('santri_id', $santriIds)->sum('dibayar');

        $sisaTagihan = max($totalTagihan - $totalDibayar, 0);

        $jumlahBelumLunas = Tagihan::whereIn('santri_id', $santriIds)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->count();

        $tagihanBelumLunas = Tagihan::query()
            ->with(['santri.kelas', 'jenisPembayaran', 'tahunAjaran'])
            ->whereIn('santri_id', $santriIds)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->latest()
            ->get();

        $pembayaranTerbaru = Pembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran'])
            ->whereIn('santri_id', $santriIds)
            ->latest('tanggal_bayar')
            ->latest()
            ->limit(10)
            ->get();

        return view('wali.dashboard', compact(
            'santris',
            'totalTagihan',
            'totalDibayar',
            'sisaTagihan',
            'jumlahBelumLunas',
            'tagihanBelumLunas',
            'pembayaranTerbaru'
        ));
    }
}
