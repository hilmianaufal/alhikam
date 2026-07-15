@extends('layouts.admin.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tahun Ajaran</h1>
            <p class="text-sm text-gray-500">Kelola periode tahun ajaran aktif.</p>
        </div>

        <a href="{{ route('admin.tahun-ajaran.create') }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
            + Tambah Tahun Ajaran
        </a>
    </div>

    <form method="GET" action="{{ route('admin.tahun-ajaran.index') }}" class="mb-5">
        <div class="flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari tahun ajaran atau semester..."
                   class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('admin.tahun-ajaran.index') }}"
                   class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Tahun Ajaran</th>
                    <th class="px-4 py-3 text-left">Semester</th>
                    <th class="px-4 py-3 text-left">Tanggal Mulai</th>
                    <th class="px-4 py-3 text-left">Tanggal Selesai</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($tahunAjarans as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">
                            {{ $item->nama_tahun }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($item->semester) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($item->is_active)
                                <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                    Tidak
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.tahun-ajaran.edit', $item) }}"
                                   class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </a>

                                <form id="delete-tahun-ajaran-{{ $item->id }}"
                                      action="{{ route('admin.tahun-ajaran.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="confirmDelete(event, 'delete-tahun-ajaran-{{ $item->id }}')">
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
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Data tahun ajaran belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tahunAjarans->links() }}
    </div>
</div>
@endsection
