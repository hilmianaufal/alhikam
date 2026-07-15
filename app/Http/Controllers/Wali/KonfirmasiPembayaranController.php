<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\KonfirmasiPembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class KonfirmasiPembayaranController extends Controller
{
    public function create(Request $request)
    {
        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        $santriIds = $santris->pluck('id');

        $tagihans = Tagihan::query()
            ->with(['santri.kelas', 'jenisPembayaran', 'tahunAjaran'])
            ->whereIn('santri_id', $santriIds)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->latest()
            ->get();

        $selectedTagihan = null;

        if ($request->tagihan_id) {
            $selectedTagihan = $tagihans->firstWhere('id', (int) $request->tagihan_id);
        }

        $riwayatKonfirmasi = KonfirmasiPembayaran::query()
            ->with(['tagihan.jenisPembayaran', 'santri'])
            ->whereIn('santri_id', $santriIds)
            ->latest()
            ->limit(10)
            ->get();

        return view('wali.konfirmasi-pembayaran.create', compact(
            'santris',
            'tagihans',
            'selectedTagihan',
            'riwayatKonfirmasi'
        ));
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
            ->where('id', $validated['tagihan_id'])
            ->firstOrFail();

        if ((int) $tagihan->santri->user_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($tagihan->status === 'lunas') {
            return back()
                ->withInput()
                ->with('error', 'Tagihan ini sudah lunas.');
        }

        $sisa = (float) $tagihan->nominal - (float) $tagihan->dibayar;

        if ((float) $validated['jumlah_bayar'] > $sisa) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah bayar melebihi sisa tagihan.');
        }

        $buktiPath = $request->file('bukti_pembayaran')
            ->store('bukti-konfirmasi', 'public');

        KonfirmasiPembayaran::create([
            'tagihan_id' => $tagihan->id,
            'santri_id' => $tagihan->santri_id,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'metode' => $validated['metode'],
            'bukti_pembayaran' => $buktiPath,
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('wali.konfirmasi-pembayaran.create')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Silakan tunggu verifikasi admin.');
    }

    public function show(KonfirmasiPembayaran $konfirmasiPembayaran)
    {
        $punyaWali = Santri::where('user_id', auth()->id())
            ->where('id', $konfirmasiPembayaran->santri_id)
            ->exists();

        if (! $punyaWali) {
            abort(403);
        }

        $konfirmasiPembayaran->load([
            'santri.kelas',
            'santri.asrama',
            'tagihan.jenisPembayaran',
            'tagihan.tahunAjaran',
            'verifier',
            'pembayaran',
        ]);

        return view('wali.konfirmasi-pembayaran.show', compact('konfirmasiPembayaran'));
    }
}
