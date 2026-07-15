<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::query()
            ->with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('users', 'search'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', [
            'Super Admin',
            'Pengurus',
            'Wali Santri',
        ])->orderBy('name')->get();

        $santris = Santri::query()
            ->whereNull('user_id')
            ->orderBy('nama')
            ->get();

        return view('admin.user.create', compact('roles', 'santris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
            'santri_ids' => ['nullable', 'array'],
            'santri_ids.*' => ['exists:santris,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['role']);

            if ($validated['role'] === 'Wali Santri' && ! empty($validated['santri_ids'])) {
                Santri::whereIn('id', $validated['santri_ids'])
                    ->update(['user_id' => $user->id]);
            }
        });

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('name', [
            'Super Admin',
            'Pengurus',
            'Wali Santri',
        ])->orderBy('name')->get();

        $santris = Santri::query()
            ->whereNull('user_id')
            ->orWhere('user_id', $user->id)
            ->orderBy('nama')
            ->get();

        $selectedSantris = Santri::where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        return view('admin.user.edit', compact(
            'user',
            'roles',
            'santris',
            'selectedSantris'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
            'santri_ids' => ['nullable', 'array'],
            'santri_ids.*' => ['exists:santris,id'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);
            $user->syncRoles([$validated['role']]);

            Santri::where('user_id', $user->id)->update(['user_id' => null]);

            if ($validated['role'] === 'Wali Santri' && ! empty($validated['santri_ids'])) {
                Santri::whereIn('id', $validated['santri_ids'])
                    ->update(['user_id' => $user->id]);
            }
        });

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'User yang sedang login tidak boleh dihapus.');
        }

        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            return back()->with('error', 'Super Admin terakhir tidak boleh dihapus.');
        }

        DB::transaction(function () use ($user) {
            Santri::where('user_id', $user->id)->update(['user_id' => null]);

            $user->syncRoles([]);
            $user->delete();
        });

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
