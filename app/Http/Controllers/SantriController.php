<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data santri, diurutkan dari yang terbaru
        $santris = \App\Models\Santri::latest()->get();

        // Mengarahkan ke file view santri/index.blade.php
        return view('santri.index', compact('santris'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    // Mengarahkan ke halaman form tambah santri
    return view('santri.create');
}

public function store(\Illuminate\Http\Request $request)
{
    // 1. Validasi inputan form agar data yang masuk aman dan sesuai
    $validated = $request->validate([
        'nis' => 'required|unique:santris,nis|max:20',
        'nama_lengkap' => 'required|string|max:255',
        'kelas' => 'required|string|max:50',
        'asrama' => 'nullable|string|max:50',
        'no_hp_wali' => 'nullable|string|max:20',
    ], [
        // Pesan error kustom berbahasa Indonesia
        'nis.required' => 'NIS wajib diisi.',
        'nis.unique' => 'NIS sudah terdaftar di sistem.',
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'kelas.required' => 'Kelas wajib diisi.',
    ]);

    // 2. Simpan data ke database
    \App\Models\Santri::create([
        'nis' => $validated['nis'],
        'nama_lengkap' => $validated['nama_lengkap'],
        'kelas' => $validated['kelas'],
        'asrama' => $validated['asrama'],
        'no_hp_wali' => $validated['no_hp_wali'],
        'saldo' => 0 // Saldo awal otomatis 0 rupiah
    ]);

    // 3. Alihkan kembali ke halaman utama santri dengan membawa session success
    return redirect()->route('santri.index')->with('success', 'Data Santri berhasil ditambahkan!');
}

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(\App\Models\Santri $santri)
    {
        // Menampilkan form edit dengan membawa data santri yang dipilih
        return view('santri.edit', compact('santri'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Santri $santri)
    {
        // 1. Validasi inputan (perhatikan rule unique pada NIS, kita kecualikan NIS santri yang sedang diedit)
        $validated = $request->validate([
            'nis' => 'required|max:20|unique:santris,nis,' . $santri->id,
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'asrama' => 'nullable|string|max:50',
            'no_hp_wali' => 'nullable|string|max:20',
        ]);

        // 2. Update data ke database
        $santri->update($validated);

        // 3. Alihkan kembali dengan pesan sukses
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil diperbarui!');
    }

    public function destroy(\App\Models\Santri $santri)
    {
        // Hapus data dari database
        $santri->delete();

        // Alihkan kembali dengan pesan sukses
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil dihapus!');
    }
}
