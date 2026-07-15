@extends('layouts.admin.app')

@section('title', 'Role & Permission')

@section('content')
@php
    $moduleLabels = [
        'dashboard' => 'Dashboard',
        'santri' => 'Data Santri',
        'kelas' => 'Data Kelas',
        'asrama' => 'Data Asrama',
        'wali-santri' => 'Data Wali Santri',
        'tahun-ajaran' => 'Tahun Ajaran',
        'jenis-pembayaran' => 'Jenis Pembayaran',
        'tagihan' => 'Tagihan Santri',
        'pembayaran' => 'Pembayaran',
        'kas' => 'Kas Pondok',
        'laporan' => 'Laporan Keuangan',
        'user' => 'Manajemen User',
        'setting' => 'Pengaturan Sistem',
    ];

    $actionLabels = [
        'view' => 'Melihat',
        'create' => 'Menambah',
        'update' => 'Mengubah',
        'delete' => 'Menghapus',
        'export' => 'Cetak / Export',
        'manage' => 'Mengelola',
    ];

    $roleDescriptions = [
        'Super Admin' => 'Akses penuh ke seluruh fitur aplikasi.',
        'Pengurus' => 'Akses untuk operasional harian pondok.',
        'Wali Santri' => 'Akses khusus wali untuk melihat tagihan dan pembayaran santri.',
    ];
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Role & Permission</h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola hak akses pengguna dengan tampilan yang mudah dipahami.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @foreach ($roles as $role)
            @php
                $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                    return explode('.', $permission->name)[0];
                });
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                                <x-heroicon-o-shield-check class="w-6 h-6 text-emerald-700" />
                            </div>

                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $role->name }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $roleDescriptions[$role->name] ?? 'Hak akses pengguna.' }}
                            </p>
                        </div>

                        <a href="{{ route('admin.role-permission.edit', $role) }}"
                           class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 hover:bg-amber-200 text-sm font-medium">
                            Atur Akses
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Jumlah akses aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">
                            {{ $role->permissions->count() }} akses
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse ($groupedPermissions as $module => $permissions)
                            <div class="border rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $moduleLabels[$module] ?? ucfirst(str_replace('-', ' ', $module)) }}
                                        </h4>

                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @foreach ($permissions as $permission)
                                                @php
                                                    $action = explode('.', $permission->name)[1] ?? $permission->name;
                                                @endphp

                                                <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                                    {{ $actionLabels[$action] ?? ucfirst($action) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                    <x-heroicon-o-lock-closed class="w-7 h-7 text-gray-400" />
                                </div>

                                <p class="text-sm text-gray-500">
                                    Role ini belum memiliki akses.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
