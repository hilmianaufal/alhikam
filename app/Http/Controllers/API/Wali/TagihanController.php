<?php

namespace App\Http\Controllers\Api\Wali;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $santriIds = Santri::where('user_id', auth()->id())->pluck('id');

        $tagihans = Tagihan::query()
            ->with([
                'santri.kelas',
                'santri.asrama',
                'jenisPembayaran',
                'tahunAjaran',
                'konfirmasiPembayarans' => fn ($query) => $query->latest(),
            ])
            ->whereIn('santri_id', $santriIds)
            ->when($request->status, function ($query) use ($request) {
                if ($request->status === 'menunggak') {
                    $query->whereIn('status', ['belum_lunas', 'sebagian']);
                } else {
                    $query->where('status', $request->status);
                }
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $tagihans,
        ]);
    }

    public function show(Tagihan $tagihan)
    {
        $this->authorizeWaliTagihan($tagihan);

        $tagihan->load([
            'santri.kelas',
            'santri.asrama',
            'jenisPembayaran',
            'tahunAjaran',
            'pembayarans',
            'konfirmasiPembayarans.verifier',
        ]);

        return response()->json([
            'success' => true,
            'data' => $tagihan,
            'sisa_tagihan' => max((float) $tagihan->nominal - (float) $tagihan->dibayar, 0),
        ]);
    }

    private function authorizeWaliTagihan(Tagihan $tagihan): void
    {
        $punyaWali = Santri::where('user_id', auth()->id())
            ->where('id', $tagihan->santri_id)
            ->exists();

        if (! $punyaWali) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }
    }
}
