@extends('layouts.admin.app')

@section('title', 'Atur Hak Akses')

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

    $moduleDescriptions = [
        'dashboard' => 'Mengakses halaman ringkasan utama aplikasi.',
        'santri' => 'Mengelola data santri pondok.',
        'kelas' => 'Mengelola data kelas santri.',
        'asrama' => 'Mengelola data asrama santri.',
        'wali-santri' => 'Mengelola data orang tua atau wali santri.',
        'tahun-ajaran' => 'Mengelola periode tahun ajaran.',
        'jenis-pembayaran' => 'Mengelola daftar biaya seperti syahriyah, makan, kitab, dan lainnya.',
        'tagihan' => 'Membuat dan mengelola tagihan santri.',
        'pembayaran' => 'Menginput dan mengelola pembayaran santri.',
        'kas' => 'Mengelola pemasukan dan pengeluaran kas pondok.',
        'laporan' => 'Melihat dan mencetak laporan keuangan.',
        'user' => 'Mengelola akun pengguna aplikasi.',
        'setting' => 'Mengelola pengaturan sistem aplikasi.',
    ];

    $actionLabels = [
        'view' => 'Melihat',
        'create' => 'Menambah',
        'update' => 'Mengubah',
        'delete' => 'Menghapus',
        'export' => 'Cetak / Export',
        'manage' => 'Mengelola',
    ];

    $actionDescriptions = [
        'view' => 'Bisa membuka dan melihat data.',
        'create' => 'Bisa menambahkan data baru.',
        'update' => 'Bisa mengubah data yang sudah ada.',
        'delete' => 'Bisa menghapus data.',
        'export' => 'Bisa mencetak atau export laporan.',
        'manage' => 'Bisa mengatur fitur ini sepenuhnya.',
    ];
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Atur Hak Akses</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Role: <span class="font-semibold text-gray-700">{{ $role->name }}</span>
                </p>
            </div>

            <a href="{{ route('admin.role-permission.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                Kembali
            </a>
        </div>
    </div>

    @if ($role->name === 'Super Admin')
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-5 text-sm text-emerald-800">
            <strong>Super Admin</strong> otomatis mendapatkan semua akses.
            Jadi semua pilihan di bawah akan dianggap aktif.
        </div>
    @else
        <div class="rounded-2xl bg-blue-50 border border-blue-200 p-5 text-sm text-blue-800">
            Centang akses yang boleh digunakan oleh role <strong>{{ $role->name }}</strong>.
            Hilangkan centang jika role ini tidak boleh memakai fitur tersebut.
        </div>
    @endif

    <form action="{{ route('admin.role-permission.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            @foreach ($permissions as $group => $items)
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="bg-gray-50 px-6 py-5 border-b">
                        <h2 class="font-bold text-gray-800 text-lg">
                            {{ $moduleLabels[$group] ?? ucfirst(str_replace('-', ' ', $group)) }}
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $moduleDescriptions[$group] ?? 'Atur hak akses untuk modul ini.' }}
                        </p>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($items as $permission)
                            @php
                                $action = explode('.', $permission->name)[1] ?? $permission->name;
                                $isChecked = in_array($permission->name, old('permissions', $selectedPermissions)) || $role->name === 'Super Admin';
                            @endphp

                            <label class="flex items-start gap-4 border rounded-2xl px-5 py-4 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       class="mt-1 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                       @checked($isChecked)
                                       @disabled($role->name === 'Super Admin')>

                                <span>
                                    <span class="block font-semibold text-gray-800">
                                        {{ $actionLabels[$action] ?? ucfirst($action) }}
                                    </span>

                                    <span class="block text-sm text-gray-500 mt-1">
                                        {{ $actionDescriptions[$action] ?? 'Hak akses untuk fitur ini.' }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.role-permission.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Simpan Hak Akses
            </button>
        </div>
    </form>

</div>
@endsection
