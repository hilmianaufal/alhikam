@extends('layouts.admin.app')

@section('title', 'Data Santri')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Santri</h1>
                <p class="text-sm text-gray-500">Kelola data santri Pondok Pesantren Al Ishlah.</p>
            </div>

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

        <div class="border rounded-xl overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-left">Asrama</th>
                        <th class="px-4 py-3 text-left">JK</th>
                        <th class="px-4 py-3 text-left">Wali</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($santris as $santri)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ $santri->nis }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->nama }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->kelas->nama_kelas ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->asrama->nama_asrama ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->nama_wali ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs
                                        {{ $santri->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($santri->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.santri.show', $santri) }}"
                                        class="px-3 py-1 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.santri.edit', $santri) }}"
                                        class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                        Edit
                                    </a>

                                    <form id="delete-santri-{{ $santri->id }}"
                                        action="{{ route('admin.santri.destroy', $santri) }}" method="POST"
                                        onsubmit="confirmDelete(event, 'delete-santri-{{ $santri->id }}')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Data santri belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">

                {{ $santris->links() }}
            </div>
        </div>
    </div>
@endsection
