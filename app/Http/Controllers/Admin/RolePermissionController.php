<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->with('permissions')
            ->whereIn('name', [
                'Super Admin',
                'Pengurus',
                'Wali Santri',
            ])
            ->orderBy('name')
            ->get();

        return view('admin.role-permission.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        if (! in_array($role->name, ['Super Admin', 'Pengurus', 'Wali Santri'])) {
            abort(404);
        }

        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0];
            });

        $selectedPermissions = $role->permissions()
            ->pluck('name')
            ->toArray();

        return view('admin.role-permission.edit', compact(
            'role',
            'permissions',
            'selectedPermissions'
        ));
    }

    public function update(Request $request, Role $role)
    {
        if (! in_array($role->name, ['Super Admin', 'Pengurus', 'Wali Santri'])) {
            abort(404);
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        if ($role->name === 'Super Admin') {
            $role->syncPermissions(Permission::all());

            return redirect()
                ->route('admin.role-permission.index')
                ->with('success', 'Permission Super Admin dikembalikan ke semua akses.');
        }

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.role-permission.index')
            ->with('success', 'Permission role berhasil diperbarui.');
    }
}
