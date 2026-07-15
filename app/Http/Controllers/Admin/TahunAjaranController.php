<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $tahunAjarans = TahunAjaran::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_tahun', 'like', "%{$search}%")
                    ->orWhere('semester', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tahun-ajaran.index', compact('tahunAjarans', 'search'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tahun' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'in:ganjil,genap'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated) {
            if ($validated['is_active']) {
                TahunAjaran::query()->update(['is_active' => false]);
            }

            TahunAjaran::create($validated);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Data tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validated = $request->validate([
            'nama_tahun' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'in:ganjil,genap'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $tahunAjaran) {
            if ($validated['is_active']) {
                TahunAjaran::query()
                    ->where('id', '!=', $tahunAjaran->id)
                    ->update(['is_active' => false]);
            }

            $tahunAjaran->update($validated);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Data tahun ajaran berhasil dihapus.');
    }
}
