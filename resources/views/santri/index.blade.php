@extends('layouts.admin.app')

@section('title', 'Data Santri')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Santri</h1>
                <p class="text-sm text-gray-500">Kelola data santri Pondok Pesantren Al Ishlah.</p>
            </div>
            <div class="flex gap-2">
                @can('laporan.export')
                    <a href="{{ route('admin.export.santri', request()->query()) }}"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                        Export Excel
                    </a>
                @endcan

                @can('santri.create')
                    <a href="{{ route('admin.santri.import') }}"
                    class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        Import Excel
                    </a>

                    <a href="{{ route('admin.santri.create') }}"
                    class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        + Tambah Santri
                    </a>
                @endcan
            </div>
        </div>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">JK</th>
                        <th class="px-4 py-3 text-left">Wali</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Data santri belum tersedia.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
