<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $pembayarans = Pembayaran::query()
            ->with(['tagihan.jenisPembayaran', 'santri.kelas', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('kode_transaksi', 'like', "%{$search}%")
                    ->orWhereHas('santri', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tagihan.jenisPembayaran', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pembayaran.index', compact('pembayarans', 'search'));
    }

    public function create()
    {
        $tagihans = Tagihan::query()
            ->with(['santri.kelas', 'jenisPembayaran'])
            ->where('status', '!=', 'lunas')
            ->latest()
            ->get();

        return view('admin.pembayaran.create', compact('tagihans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tagihan_id' => ['required', 'exists:tagihans,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'metode' => ['required', 'in:tunai,transfer,qris,lainnya'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $buktiPath = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $buktiPath) {
                $tagihan = Tagihan::lockForUpdate()
                    ->with('jenisPembayaran')
                    ->findOrFail($validated['tagihan_id']);

                $sisa = (float) $tagihan->nominal - (float) $tagihan->dibayar;

                if ((float) $validated['jumlah_bayar'] > $sisa) {
                    throw new \Exception('Jumlah bayar melebihi sisa tagihan.');
                }

                $pembayaran = Pembayaran::create([
                    'kode_transaksi' => $this->generateKodeTransaksi(),
                    'tagihan_id' => $tagihan->id,
                    'santri_id' => $tagihan->santri_id,
                    'tanggal_bayar' => $validated['tanggal_bayar'],
                    'jumlah_bayar' => $validated['jumlah_bayar'],
                    'metode' => $validated['metode'],
                    'bukti_pembayaran' => $buktiPath,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'created_by' => auth()->id(),
                ]);

                $tagihan->dibayar = (float) $tagihan->dibayar + (float) $validated['jumlah_bayar'];
                $this->updateStatusTagihan($tagihan);
                $tagihan->save();

                $this->createKasFromPembayaran($pembayaran, $tagihan);
            });
        } catch (Throwable $e) {
            if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan dan bukti pembayaran berhasil diupload.');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'tagihan.jenisPembayaran',
            'tagihan.tahunAjaran',
            'santri.kelas',
            'santri.asrama',
            'user',
        ]);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function struk(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'tagihan.jenisPembayaran',
            'tagihan.tahunAjaran',
            'santri.kelas',
            'santri.asrama',
            'user',
        ]);

        return view('admin.pembayaran.struk', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.jenisPembayaran', 'santri']);

        return view('admin.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'tanggal_bayar' => ['required', 'date'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'metode' => ['required', 'in:tunai,transfer,qris,lainnya'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $oldBukti = $pembayaran->bukti_pembayaran;
        $newBukti = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $newBukti = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $pembayaran, $newBukti, $oldBukti) {
                $tagihan = Tagihan::lockForUpdate()
                    ->with('jenisPembayaran')
                    ->findOrFail($pembayaran->tagihan_id);

                $jumlahLama = (float) $pembayaran->jumlah_bayar;
                $jumlahBaru = (float) $validated['jumlah_bayar'];
                $selisih = $jumlahBaru - $jumlahLama;

                $sisa = (float) $tagihan->nominal - (float) $tagihan->dibayar;

                if ($selisih > $sisa) {
                    throw new \Exception('Jumlah bayar melebihi sisa tagihan.');
                }

                $dataUpdate = [
                    'tanggal_bayar' => $validated['tanggal_bayar'],
                    'jumlah_bayar' => $validated['jumlah_bayar'],
                    'metode' => $validated['metode'],
                    'keterangan' => $validated['keterangan'] ?? null,
                ];

                if ($newBukti) {
                    $dataUpdate['bukti_pembayaran'] = $newBukti;
                }

                $pembayaran->update($dataUpdate);

                if ($newBukti && $oldBukti && Storage::disk('public')->exists($oldBukti)) {
                    Storage::disk('public')->delete($oldBukti);
                }

                $tagihan->dibayar = (float) $tagihan->dibayar + $selisih;
                $this->updateStatusTagihan($tagihan);
                $tagihan->save();

                $this->syncKasFromPembayaran($pembayaran->fresh(), $tagihan);
            });
        } catch (Throwable $e) {
            if ($newBukti && Storage::disk('public')->exists($newBukti)) {
                Storage::disk('public')->delete($newBukti);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran, bukti, dan Kas Pondok berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        try {
            DB::transaction(function () use ($pembayaran) {
                $tagihan = Tagihan::lockForUpdate()->findOrFail($pembayaran->tagihan_id);

                $tagihan->dibayar = max((float) $tagihan->dibayar - (float) $pembayaran->jumlah_bayar, 0);
                $this->updateStatusTagihan($tagihan);
                $tagihan->save();

                KasTransaction::where('pembayaran_id', $pembayaran->id)
                    ->where('sumber', 'pembayaran')
                    ->delete();

                if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }

                $pembayaran->delete();
            });
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran, bukti, dan Kas Pondok berhasil dihapus.');
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
            'created_by' => $pembayaran->created_by,
        ]);
    }

    private function syncKasFromPembayaran(Pembayaran $pembayaran, Tagihan $tagihan): void
    {
        $kas = KasTransaction::where('pembayaran_id', $pembayaran->id)
            ->where('sumber', 'pembayaran')
            ->first();

        if (! $kas) {
            $this->createKasFromPembayaran($pembayaran, $tagihan);
            return;
        }

        $tagihan->loadMissing('jenisPembayaran');

        $kas->update([
            'tanggal' => $pembayaran->tanggal_bayar,
            'nominal' => $pembayaran->jumlah_bayar,
            'metode' => $pembayaran->metode,
            'kategori' => 'Pembayaran Santri',
            'keterangan' => 'Pembayaran ' . ($tagihan->jenisPembayaran->nama ?? '-') . ' - ' . $pembayaran->kode_transaksi,
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
