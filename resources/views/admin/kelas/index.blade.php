@extends('layouts.admin.app')

@section('title', 'Data Kelas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Kelas</h1>
            <p class="text-sm text-gray-500">Kelola data kelas Pondok Pesantren Al Ishlah.</p>
        </div>

        <a href="{{ route('admin.kelas.create') }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
            + Tambah Kelas
        </a>
    </div>

    <form method="GET" action="{{ route('admin.kelas.index') }}" class="mb-5">
        <div class="flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari nama kelas, tingkat, atau wali kelas..."
                   class="w-full rounded-xl border-gray-300">

            <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('admin.kelas.index') }}"
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
                    <th class="px-4 py-3 text-left">Nama Kelas</th>
                    <th class="px-4 py-3 text-left">Tingkat</th>
                    <th class="px-4 py-3 text-left">Wali Kelas</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($kelas as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">
                            {{ $item->nama_kelas }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->tingkat ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->wali_kelas ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.kelas.edit', $item) }}"
                                   class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </a>

                                <form id="delete-kelas-{{ $item->id }}"
                                      action="{{ route('admin.kelas.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="confirmDelete(event, 'delete-kelas-{{ $item->id }}')">
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
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Data kelas belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kelas->links() }}
    </div>
</div>
@endsection
