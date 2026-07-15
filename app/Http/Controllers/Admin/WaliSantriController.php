<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;

class WaliSantriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $statusAkun = $request->status_akun;

        $santris = Santri::query()
            ->with(['kelas', 'asrama', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%")
                    ->orWhere('nama_wali', 'like', "%{$search}%")
                    ->orWhere('no_hp_ayah', 'like', "%{$search}%")
                    ->orWhere('no_hp_ibu', 'like', "%{$search}%")
                    ->orWhere('no_hp_wali', 'like', "%{$search}%");
            })
            ->when($statusAkun === 'terhubung', function ($query) {
                $query->whereNotNull('user_id');
            })
            ->when($statusAkun === 'belum', function ($query) {
                $query->whereNull('user_id');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.wali-santri.index', compact(
            'santris',
            'search',
            'statusAkun'
        ));
    }

    public function edit(Santri $santri)
    {
        $santri->load(['kelas', 'asrama', 'user']);

        $waliUsers = User::role('Wali Santri')
            ->orderBy('name')
            ->get();

        return view('admin.wali-santri.edit', compact('santri', 'waliUsers'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nama_wali' => ['nullable', 'string', 'max:255'],
            'no_hp_ayah' => ['nullable', 'string', 'max:30'],
            'no_hp_ibu' => ['nullable', 'string', 'max:30'],
            'no_hp_wali' => ['nullable', 'string', 'max:30'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        if (! empty($validated['user_id'])) {
            $user = User::findOrFail($validated['user_id']);

            if (! $user->hasRole('Wali Santri')) {
                return back()
                    ->withInput()
                    ->with('error', 'Akun yang dipilih bukan role Wali Santri.');
            }
        }

        $santri->update($validated);

        return redirect()
            ->route('admin.wali-santri.index')
            ->with('success', 'Data wali santri berhasil diperbarui.');
    }
}
