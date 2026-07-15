<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        $santriIds = $santris->pluck('id');

        $pembayarans = Pembayaran::query()
            ->with([
                'santri.kelas',
                'santri.asrama',
                'tagihan.jenisPembayaran',
                'user',
                'konfirmasiPembayaran',
            ])
            ->whereIn('santri_id', $santriIds)
            ->when($request->santri_id, function ($query) use ($request) {
                $query->where('santri_id', $request->santri_id);
            })
            ->when($request->metode, function ($query) use ($request) {
                $query->where('metode', $request->metode);
            })
            ->when($request->tanggal_mulai, function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
            })
            ->when($request->tanggal_selesai, function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '<=', $request->tanggal_selesai);
            })
            ->latest('tanggal_bayar')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPembayaran = Pembayaran::whereIn('santri_id', $santriIds)->sum('jumlah_bayar');
        $jumlahTransaksi = Pembayaran::whereIn('santri_id', $santriIds)->count();

        return view('wali.pembayaran.index', compact(
            'santris',
            'pembayarans',
            'totalPembayaran',
            'jumlahTransaksi'
        ));
    }
}
