<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;

class JenisPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $jenisPembayarans = JenisPembayaran::query()
            ->when($search, function ($query) use ($search) {
                $query->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.jenis-pembayaran.index', compact('jenisPembayarans', 'search'));
    }

    public function create()
    {
        return view('admin.jenis-pembayaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:50', 'unique:jenis_pembayarans,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'tipe' => ['required', 'in:bulanan,tahunan,sekali,bebas'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        JenisPembayaran::create($validated);

        return redirect()
            ->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function edit(JenisPembayaran $jenisPembayaran)
    {
        return view('admin.jenis-pembayaran.edit', compact('jenisPembayaran'));
    }

    public function update(Request $request, JenisPembayaran $jenisPembayaran)
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:50', 'unique:jenis_pembayarans,kode,' . $jenisPembayaran->id],
            'nama' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'tipe' => ['required', 'in:bulanan,tahunan,sekali,bebas'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $jenisPembayaran->update($validated);

        return redirect()
            ->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil diperbarui.');
    }

    public function destroy(JenisPembayaran $jenisPembayaran)
    {
        $jenisPembayaran->delete();

        return redirect()
            ->route('admin.jenis-pembayaran.index')
            ->with('success', 'Jenis pembayaran berhasil dihapus.');
    }
}
