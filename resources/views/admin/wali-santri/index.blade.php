@extends('layouts.admin.app')

@section('title', 'Data Wali Santri')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Wali Santri</h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelola data orang tua/wali dan akun wali santri.
            </p>
        </div>

        <a href="{{ route('admin.user.create') }}"
           class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 text-center">
            + Buat Akun Wali
        </a>
    </div>

    <form method="GET" action="{{ route('admin.wali-santri.index') }}" class="mb-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari santri, wali, orang tua, atau no HP..."
                   class="md:col-span-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <select name="status_akun"
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Akun</option>
                <option value="terhubung" @selected($statusAkun == 'terhubung')>Sudah Terhubung</option>
                <option value="belum" @selected($statusAkun == 'belum')>Belum Terhubung</option>
            </select>

            <div class="flex gap-2">
                <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Cari
                </button>

                <a href="{{ route('admin.wali-santri.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Orang Tua / Wali</th>
                    <th class="px-4 py-3 text-left">Kontak</th>
                    <th class="px-4 py-3 text-left">Akun Wali</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($santris as $santri)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">
                                {{ $santri->nama }}
                            </p>

                            <p class="text-xs text-gray-500">
                                NIS: {{ $santri->nis }}
                                —
                                {{ $santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </p>
                        </td>

                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <p>
                                    <span class="text-gray-500">Ayah:</span>
                                    <span class="font-medium">{{ $santri->nama_ayah ?? '-' }}</span>
                                </p>

                                <p>
                                    <span class="text-gray-500">Ibu:</span>
                                    <span class="font-medium">{{ $santri->nama_ibu ?? '-' }}</span>
                                </p>

                                <p>
                                    <span class="text-gray-500">Wali:</span>
                                    <span class="font-medium">{{ $santri->nama_wali ?? '-' }}</span>
                                </p>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="space-y-1 text-xs">
                                <p>Ayah: {{ $santri->no_hp_ayah ?? '-' }}</p>
                                <p>Ibu: {{ $santri->no_hp_ibu ?? '-' }}</p>
                                <p>Wali: {{ $santri->no_hp_wali ?? '-' }}</p>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            @if ($santri->user)
                                <span class="inline-flex px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 mb-2">
                                    Terhubung
                                </span>

                                <p class="font-medium text-gray-800">
                                    {{ $santri->user->name }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $santri->user->email }}
                                </p>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                    Belum terhubung
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.wali-santri.edit', $santri) }}"
                               class="px-3 py-2 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Data wali santri belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $santris->links() }}
    </div>
</div>
@endsection
