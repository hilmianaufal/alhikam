<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use Illuminate\Http\Request;

class AsramaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $asramas = Asrama::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_asrama', 'like', "%{$search}%")
                    ->orWhere('kode_asrama', 'like', "%{$search}%")
                    ->orWhere('musyrif', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.asrama.index', compact('asramas', 'search'));
    }

    public function create()
    {
        return view('admin.asrama.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_asrama' => ['required', 'string', 'max:150'],
            'kode_asrama' => ['nullable', 'string', 'max:50', 'unique:asramas,kode_asrama'],
            'musyrif' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Asrama::create($validated);

        return redirect()
            ->route('admin.asrama.index')
            ->with('success', 'Data asrama berhasil ditambahkan.');
    }

    public function edit(Asrama $asrama)
    {
        return view('admin.asrama.edit', compact('asrama'));
    }

    public function update(Request $request, Asrama $asrama)
    {
        $validated = $request->validate([
            'nama_asrama' => ['required', 'string', 'max:150'],
            'kode_asrama' => ['nullable', 'string', 'max:50', 'unique:asramas,kode_asrama,' . $asrama->id],
            'musyrif' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $asrama->update($validated);

        return redirect()
            ->route('admin.asrama.index')
            ->with('success', 'Data asrama berhasil diperbarui.');
    }

    public function destroy(Asrama $asrama)
    {
        $asrama->delete();

        return redirect()
            ->route('admin.asrama.index')
            ->with('success', 'Data asrama berhasil dihapus.');
    }
}
