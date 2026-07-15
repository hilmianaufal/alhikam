<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use App\Models\KonfirmasiPembayaran;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class KonfirmasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $konfirmasis = KonfirmasiPembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran', 'verifier'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })
                ->orWhereHas('tagihan.jenisPembayaran', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.konfirmasi-pembayaran.index', compact('konfirmasis', 'search'));
    }

    public function show(KonfirmasiPembayaran $konfirmasiPembayaran)
    {
        $konfirmasiPembayaran->load([
            'santri.kelas',
            'santri.asrama',
            'tagihan.jenisPembayaran',
            'tagihan.tahunAjaran',
            'verifier',
            'pembayaran',
        ]);

        return view('admin.konfirmasi-pembayaran.show', compact('konfirmasiPembayaran'));
    }

    public function approve(KonfirmasiPembayaran $konfirmasiPembayaran)
    {
        if ($konfirmasiPembayaran->status !== 'menunggu') {
            return back()->with('error', 'Konfirmasi ini sudah diproses.');
        }

        try {
            DB::transaction(function () use ($konfirmasiPembayaran) {
                $tagihan = Tagihan::lockForUpdate()
                    ->with('jenisPembayaran')
                    ->findOrFail($konfirmasiPembayaran->tagihan_id);

                $sisa = (float) $tagihan->nominal - (float) $tagihan->dibayar;

                if ((float) $konfirmasiPembayaran->jumlah_bayar > $sisa) {
                    throw new \Exception('Jumlah bayar melebihi sisa tagihan.');
                }

                $pembayaran = Pembayaran::create([
                    'kode_transaksi' => $this->generateKodeTransaksi(),
                    'tagihan_id' => $tagihan->id,
                    'santri_id' => $tagihan->santri_id,
                    'tanggal_bayar' => $konfirmasiPembayaran->tanggal_bayar,
                    'jumlah_bayar' => $konfirmasiPembayaran->jumlah_bayar,
                    'metode' => $konfirmasiPembayaran->metode,
                    'bukti_pembayaran' => $konfirmasiPembayaran->bukti_pembayaran,
                    'keterangan' => $konfirmasiPembayaran->keterangan,
                    'created_by' => auth()->id(),
                ]);

                $tagihan->dibayar = (float) $tagihan->dibayar + (float) $konfirmasiPembayaran->jumlah_bayar;
                $this->updateStatusTagihan($tagihan);
                $tagihan->save();

                $this->createKasFromPembayaran($pembayaran, $tagihan);

                $konfirmasiPembayaran->update([
                    'status' => 'diterima',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'pembayaran_id' => $pembayaran->id,
                    'catatan_admin' => 'Pembayaran diterima.',
                ]);
            });
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.konfirmasi-pembayaran.index')
            ->with('success', 'Konfirmasi pembayaran berhasil diterima dan masuk ke pembayaran.');
    }

    public function reject(Request $request, KonfirmasiPembayaran $konfirmasiPembayaran)
    {
        if ($konfirmasiPembayaran->status !== 'menunggu') {
            return back()->with('error', 'Konfirmasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        $konfirmasiPembayaran->update([
            'status' => 'ditolak',
            'catatan_admin' => $validated['catatan_admin'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.konfirmasi-pembayaran.index')
            ->with('success', 'Konfirmasi pembayaran berhasil ditolak.');
    }

    private function updateStatusTagihan(Tagihan $tagihan): void
    {
        if ((float) $tagihan->dibayar >= (float) $tagihan->nominal) {
            $tagihan->status = 'lunas';
        } elseif ((float) $tagihan->dibayar > 0) {
            $tagihan->status = 'sebagian';
        } else {
            $tagihan->status = 'belum_lunas';
        }
    }

    private function createKasFromPembayaran(Pembayaran $pembayaran, Tagihan $tagihan): void
    {
        $tagihan->loadMissing('jenisPembayaran');

        KasTransaction::create([
            'kode' => $this->generateKodeKas(),
            'tanggal' => $pembayaran->tanggal_bayar,
            'tipe' => 'pemasukan',
            'kategori' => 'Pembayaran Santri',
            'nominal' => $pembayaran->jumlah_bayar,
            'metode' => $pembayaran->metode,
            'sumber' => 'pembayaran',
            'pembayaran_id' => $pembayaran->id,
            'keterangan' => 'Pembayaran ' . ($tagihan->jenisPembayaran->nama ?? '-') . ' - ' . $pembayaran->kode_transaksi,
            'created_by' => auth()->id(),
        ]);
    }

    private function generateKodeTransaksi(): string
    {
        do {
            $kode = 'PAY-' . now()->format('Ymd') . '-' . random_int(10000, 99999);
        } while (Pembayaran::where('kode_transaksi', $kode)->exists());

        return $kode;
    }

    private function generateKodeKas(): string
    {
        do {
            $kode = 'KAS-' . now()->format('Ymd') . '-' . random_int(10000, 99999);
        } while (KasTransaction::where('kode', $kode)->exists());

        return $kode;
    }
}
