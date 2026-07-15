<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $tagihans = Tagihan::query()
            ->with(['santri.kelas', 'santri.asrama', 'jenisPembayaran', 'tahunAjaran'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })
                ->orWhereHas('jenisPembayaran', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
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

        return view('admin.tagihan.index', compact('tagihans', 'search'));
    }

    public function create()
    {
        $jenisPembayarans = JenisPembayaran::where('status', 'aktif')->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::where('status', 'aktif')->orderByDesc('is_active')->latest()->get();
        $kelas = Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get();
        $asramas = Asrama::where('status', 'aktif')->orderBy('nama_asrama')->get();

        return view('admin.tagihan.create', compact(
            'jenisPembayarans',
            'tahunAjarans',
            'kelas',
            'asramas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_pembayaran_id' => ['required', 'exists:jenis_pembayarans,id'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'tanggal_jatuh_tempo' => ['nullable', 'date'],
            'target' => ['required', 'in:semua,kelas,asrama'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'asrama_id' => ['nullable', 'exists:asramas,id'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $santris = Santri::query()
            ->where('status', 'aktif')
            ->when($validated['target'] === 'kelas', function ($query) use ($validated) {
                $query->where('kelas_id', $validated['kelas_id']);
            })
            ->when($validated['target'] === 'asrama', function ($query) use ($validated) {
                $query->where('asrama_id', $validated['asrama_id']);
            })
            ->get();

        if ($santris->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada santri yang sesuai dengan target tagihan.');
        }

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($santris, $validated, &$created, &$skipped) {
            foreach ($santris as $santri) {
                $exists = Tagihan::where('santri_id', $santri->id)
                    ->where('jenis_pembayaran_id', $validated['jenis_pembayaran_id'])
                    ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                    ->where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Tagihan::create([
                    'santri_id' => $santri->id,
                    'jenis_pembayaran_id' => $validated['jenis_pembayaran_id'],
                    'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? null,
                    'bulan' => $validated['bulan'] ?? null,
                    'tahun' => $validated['tahun'],
                    'nominal' => $validated['nominal'],
                    'dibayar' => 0,
                    'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
                    'status' => 'belum_lunas',
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);

                $created++;
            }
        });

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', "Generate tagihan berhasil. Dibuat: {$created}, dilewati karena sudah ada: {$skipped}.");
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['santri.kelas', 'santri.asrama', 'jenisPembayaran', 'tahunAjaran']);

        return view('admin.tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        $tagihan->load(['santri', 'jenisPembayaran', 'tahunAjaran']);

        return view('admin.tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'nominal' => ['required', 'numeric', 'min:0'],
            'dibayar' => ['required', 'numeric', 'min:0'],
            'tanggal_jatuh_tempo' => ['nullable', 'date'],
            'status' => ['required', 'in:belum_lunas,sebagian,lunas'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($validated['dibayar'] >= $validated['nominal']) {
            $validated['status'] = 'lunas';
        } elseif ($validated['dibayar'] > 0) {
            $validated['status'] = 'sebagian';
        } else {
            $validated['status'] = 'belum_lunas';
        }

        $tagihan->update($validated);

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Tagihan $tagihan)
    {
        if ($tagihan->dibayar > 0) {
            return back()->with('error', 'Tagihan yang sudah memiliki pembayaran tidak boleh dihapus.');
        }

        $tagihan->delete();

        return redirect()
            ->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
