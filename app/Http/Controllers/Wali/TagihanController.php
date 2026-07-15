<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        $santriIds = $santris->pluck('id');

        $tagihans = Tagihan::query()
            ->with([
                'santri.kelas',
                'santri.asrama',
                'jenisPembayaran',
                'tahunAjaran',
                'konfirmasiPembayarans' => function ($query) {
                    $query->latest();
                },
            ])
            ->whereIn('santri_id', $santriIds)
            ->when($request->santri_id, function ($query) use ($request) {
                $query->where('santri_id', $request->santri_id);
            })
            ->when($request->status, function ($query) use ($request) {
                if ($request->status === 'menunggak') {
                    $query->whereIn('status', ['belum_lunas', 'sebagian']);
                } else {
                    $query->where('status', $request->status);
                }
            })
            ->when($request->bulan, function ($query) use ($request) {
                $query->where('bulan', $request->bulan);
            })
            ->when($request->tahun, function ($query) use ($request) {
                $query->where('tahun', $request->tahun);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summaryQuery = Tagihan::whereIn('santri_id', $santriIds);

        $totalTagihan = (clone $summaryQuery)->sum('nominal');
        $totalDibayar = (clone $summaryQuery)->sum('dibayar');
        $sisaTagihan = max($totalTagihan - $totalDibayar, 0);

        $jumlahMenunggak = (clone $summaryQuery)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->count();

        return view('wali.tagihan.index', compact(
            'santris',
            'tagihans',
            'totalTagihan',
            'totalDibayar',
            'sisaTagihan',
            'jumlahMenunggak'
        ));
    }

    public function show(Tagihan $tagihan)
    {
        $punyaWali = Santri::where('user_id', auth()->id())
            ->where('id', $tagihan->santri_id)
            ->exists();

        if (! $punyaWali) {
            abort(403);
        }

        $tagihan->load([
            'santri.kelas',
            'santri.asrama',
            'jenisPembayaran',
            'tahunAjaran',
            'pembayarans.user',
            'konfirmasiPembayarans.verifier',
        ]);

        $sisaTagihan = max((float) $tagihan->nominal - (float) $tagihan->dibayar, 0);

        return view('wali.tagihan.show', compact('tagihan', 'sisaTagihan'));
    }
}
