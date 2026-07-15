<?php

namespace App\Http\Controllers\Api\Wali;

use App\Http\Controllers\Controller;
use App\Models\KonfirmasiPembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class KonfirmasiPembayaranController extends Controller
{
    public function index()
    {
        $santriIds = Santri::where('user_id', auth()->id())->pluck('id');

        $konfirmasis = KonfirmasiPembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran', 'verifier', 'pembayaran'])
            ->whereIn('santri_id', $santriIds)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $konfirmasis,
        ]);
    }

    public function show(KonfirmasiPembayaran $konfirmasiPembayaran)
    {
        $this->authorizeWaliKonfirmasi($konfirmasiPembayaran);

        $konfirmasiPembayaran->load([
            'santri.kelas',
            'santri.asrama',
            'tagihan.jenisPembayaran',
            'tagihan.tahunAjaran',
            'verifier',
            'pembayaran',
        ]);

        return response()->json([
            'success' => true,
            'data' => $konfirmasiPembayaran,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tagihan_id' => ['required', 'exists:tagihans,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'metode' => ['required', 'in:transfer,qris,lainnya'],
            'bukti_pembayaran' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $tagihan = Tagihan::query()
            ->with('santri')
            ->findOrFail($validated['tagihan_id']);

        if ((int) $tagihan->santri->user_id !== (int) auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        if ($tagihan->status === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan ini sudah lunas.',
            ], 422);
        }

        $sisa = (float) $tagihan->nominal - (float) $tagihan->dibayar;

        if ((float) $validated['jumlah_bayar'] > $sisa) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah bayar melebihi sisa tagihan.',
            ], 422);
        }

        $buktiPath = $request->file('bukti_pembayaran')
            ->store('bukti-konfirmasi', 'public');

        $konfirmasi = KonfirmasiPembayaran::create([
            'tagihan_id' => $tagihan->id,
            'santri_id' => $tagihan->santri_id,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'metode' => $validated['metode'],
            'bukti_pembayaran' => $buktiPath,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil dikirim.',
            'data' => $konfirmasi,
        ], 201);
    }

    private function authorizeWaliKonfirmasi(KonfirmasiPembayaran $konfirmasiPembayaran): void
    {
        $punyaWali = Santri::where('user_id', auth()->id())
            ->where('id', $konfirmasiPembayaran->santri_id)
            ->exists();

        if (! $punyaWali) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
