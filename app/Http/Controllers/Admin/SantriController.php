<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->when($search, function ($query) use ($search) {
                $query->where('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nama_panggilan', 'like', "%{$search}%")
                    ->orWhere('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%")
                    ->orWhere('nama_wali', 'like', "%{$search}%")
                    ->orWhere('no_hp_wali', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.santri.index', compact('santris', 'search'));
    }

    public function create()
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get();
        $asramas = Asrama::where('status', 'aktif')->orderBy('nama_asrama')->get();

        return view('admin.santri.create', compact('kelas', 'asramas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:santris,nis'],
            'nisn' => ['nullable', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:50'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nama_wali' => ['nullable', 'string', 'max:255'],
            'no_hp_ayah' => ['nullable', 'string', 'max:30'],
            'no_hp_ibu' => ['nullable', 'string', 'max:30'],
            'no_hp_wali' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'tanggal_masuk' => ['nullable', 'date'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'asrama_id' => ['nullable', 'exists:asramas,id'],
            'status_mukim' => ['required', 'in:mukim,non_mukim'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $validated['agama'] = $validated['agama'] ?? 'Islam';
        $validated['qr_token'] = (string) Str::uuid();

        Santri::create($validated);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function show(Santri $santri)
    {
        $santri->load(['kelas', 'asrama']);

        return view('admin.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get();
        $asramas = Asrama::where('status', 'aktif')->orderBy('nama_asrama')->get();

        return view('admin.santri.edit', compact('santri', 'kelas', 'asramas'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:santris,nis,' . $santri->id],
            'nisn' => ['nullable', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:50'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nama_wali' => ['nullable', 'string', 'max:255'],
            'no_hp_ayah' => ['nullable', 'string', 'max:30'],
            'no_hp_ibu' => ['nullable', 'string', 'max:30'],
            'no_hp_wali' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'tanggal_masuk' => ['nullable', 'date'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'asrama_id' => ['nullable', 'exists:asramas,id'],
            'status_mukim' => ['required', 'in:mukim,non_mukim'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $validated['agama'] = $validated['agama'] ?? 'Islam';

        $santri->update($validated);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
