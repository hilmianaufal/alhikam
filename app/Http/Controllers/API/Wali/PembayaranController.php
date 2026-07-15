<?php

namespace App\Http\Controllers\Api\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $santriIds = Santri::where('user_id', auth()->id())->pluck('id');

        $pembayarans = Pembayaran::query()
            ->with([
                'santri.kelas',
                'santri.asrama',
                'tagihan.jenisPembayaran',
                'tagihan.tahunAjaran',
                'konfirmasiPembayaran',
            ])
            ->whereIn('santri_id', $santriIds)
            ->when($request->metode, fn ($query) => $query->where('metode', $request->metode))
            ->latest('tanggal_bayar')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $pembayarans,
        ]);
    }
}
