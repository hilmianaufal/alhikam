<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KasTransaction;
use Illuminate\Http\Request;

class KasTransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kasTransactions = KasTransaction::query()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('kode', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            })
            ->when($request->tipe, function ($query) use ($request) {
                $query->where('tipe', $request->tipe);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPemasukan = KasTransaction::where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = KasTransaction::where('tipe', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('admin.kas.index', compact(
            'kasTransactions',
            'search',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo'
        ));
    }

    public function create()
    {
        return view('admin.kas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:pemasukan,pengeluaran'],
            'kategori' => ['required', 'string', 'max:150'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'metode' => ['required', 'in:tunai,transfer,qris,lainnya'],
            'keterangan' => ['nullable', 'string'],
        ]);

        KasTransaction::create([
            'kode' => $this->generateKodeKas(),
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
            'kategori' => $validated['kategori'],
            'nominal' => $validated['nominal'],
            'metode' => $validated['metode'],
            'sumber' => 'manual',
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.kas.index')
            ->with('success', 'Transaksi kas berhasil ditambahkan.');
    }

    public function edit(KasTransaction $kasTransaction)
    {
        if ($kasTransaction->sumber === 'pembayaran') {
            return redirect()
                ->route('admin.kas.index')
                ->with('error', 'Transaksi dari pembayaran tidak bisa diedit di menu Kas.');
        }

        return view('admin.kas.edit', compact('kasTransaction'));
    }

    public function update(Request $request, KasTransaction $kasTransaction)
    {
        if ($kasTransaction->sumber === 'pembayaran') {
            return redirect()
                ->route('admin.kas.index')
                ->with('error', 'Transaksi dari pembayaran tidak bisa diedit di menu Kas.');
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:pemasukan,pengeluaran'],
            'kategori' => ['required', 'string', 'max:150'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'metode' => ['required', 'in:tunai,transfer,qris,lainnya'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kasTransaction->update($validated);

        return redirect()
            ->route('admin.kas.index')
            ->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    public function destroy(KasTransaction $kasTransaction)
    {
        if ($kasTransaction->sumber === 'pembayaran') {
            return redirect()
                ->route('admin.kas.index')
                ->with('error', 'Transaksi dari pembayaran tidak bisa dihapus di menu Kas.');
        }

        $kasTransaction->delete();

        return redirect()
            ->route('admin.kas.index')
            ->with('success', 'Transaksi kas berhasil dihapus.');
    }

    private function generateKodeKas(): string
    {
        do {
            $kode = 'KAS-' . now()->format('Ymd') . '-' . random_int(10000, 99999);
        } while (KasTransaction::where('kode', $kode)->exists());

        return $kode;
    }
}
