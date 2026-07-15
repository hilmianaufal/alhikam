<?php

namespace App\Http\Controllers\Api\Wali;

use App\Http\Controllers\Controller;
use App\Models\KonfirmasiPembayaran;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;

class DashboardController extends Controller
{
    public function index()
    {
        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        $santriIds = $santris->pluck('id');

        $totalTagihan = Tagihan::whereIn('santri_id', $santriIds)->sum('nominal');
        $totalDibayar = Tagihan::whereIn('santri_id', $santriIds)->sum('dibayar');
        $sisaTagihan = max($totalTagihan - $totalDibayar, 0);

        $jumlahTagihanBelumLunas = Tagihan::whereIn('santri_id', $santriIds)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->count();

        $totalPembayaran = Pembayaran::whereIn('santri_id', $santriIds)->sum('jumlah_bayar');

        $konfirmasiMenunggu = KonfirmasiPembayaran::whereIn('santri_id', $santriIds)
            ->where('status', 'menunggu')
            ->count();

        $tagihanTerbaru = Tagihan::query()
            ->with(['santri.kelas', 'jenisPembayaran', 'tahunAjaran'])
            ->whereIn('santri_id', $santriIds)
            ->latest()
            ->limit(5)
            ->get();

        $pembayaranTerbaru = Pembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran'])
            ->whereIn('santri_id', $santriIds)
            ->latest('tanggal_bayar')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'summary' => [
                'total_tagihan' => (float) $totalTagihan,
                'total_dibayar' => (float) $totalDibayar,
                'sisa_tagihan' => (float) $sisaTagihan,
                'jumlah_tagihan_belum_lunas' => $jumlahTagihanBelumLunas,
                'total_pembayaran' => (float) $totalPembayaran,
                'konfirmasi_menunggu' => $konfirmasiMenunggu,
            ],
            'santris' => $santris,
            'tagihan_terbaru' => $tagihanTerbaru,
            'pembayaran_terbaru' => $pembayaranTerbaru,
        ]);
    }
}
