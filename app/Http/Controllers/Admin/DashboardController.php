<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSantriAktif = Santri::where('status', 'aktif')->count();

        $totalPemasukan = KasTransaction::where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = KasTransaction::where('tipe', 'pengeluaran')->sum('nominal');
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni = KasTransaction::where('tipe', 'pemasukan')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        $pengeluaranBulanIni = KasTransaction::where('tipe', 'pengeluaran')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        $tagihanBelumLunas = Tagihan::whereIn('status', ['belum_lunas', 'sebagian'])->count();

        $totalTunggakan = Tagihan::whereIn('status', ['belum_lunas', 'sebagian'])
            ->selectRaw('COALESCE(SUM(nominal - dibayar), 0) as total')
            ->value('total');

        $pembayaranHariIni = Pembayaran::whereDate('tanggal_bayar', today())
            ->sum('jumlah_bayar');

        $pembayaranTerbaru = Pembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran'])
            ->latest('tanggal_bayar')
            ->latest()
            ->limit(5)
            ->get();

        $tagihanTerbaru = Tagihan::query()
            ->with(['santri.kelas', 'jenisPembayaran'])
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->latest()
            ->limit(5)
            ->get();

        $chartKas = collect(range(5, 0))->map(function ($monthBack) {
            $date = now()->subMonths($monthBack);

            $pemasukan = KasTransaction::where('tipe', 'pemasukan')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('nominal');

            $pengeluaran = KasTransaction::where('tipe', 'pengeluaran')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('nominal');

            return [
                'label' => $date->translatedFormat('M Y'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ];
        });

        $maxChartValue = max(
            $chartKas->max('pemasukan') ?? 0,
            $chartKas->max('pengeluaran') ?? 0,
            1
        );

        return view('dashboard', compact(
            'totalSantriAktif',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKas',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'tagihanBelumLunas',
            'totalTunggakan',
            'pembayaranHariIni',
            'pembayaranTerbaru',
            'tagihanTerbaru',
            'chartKas',
            'maxChartValue'
        ));
    }
}
