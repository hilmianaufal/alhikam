@extends('layouts.admin.app')

@section('title', 'Detail Santri')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Santri
                </h1>
                <p class="text-sm text-gray-500">
                    Informasi lengkap data santri.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.santri.edit', $santri) }}"
                   class="px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
                    Edit
                </a>

                <a href="{{ route('admin.santri.index') }}"
                   class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex flex-col items-center text-center">
                <div class="w-28 h-28 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                    <x-heroicon-o-user class="w-16 h-16 text-emerald-700" />
                </div>

                <h2 class="text-xl font-bold text-gray-800">
                    {{ $santri->nama }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $santri->nis }}
                </p>

                <span class="mt-3 px-3 py-1 rounded-full text-xs
                    {{ $santri->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($santri->status) }}
                </span>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-semibold text-lg mb-4">
                Data Identitas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">NIS</p>
                    <p class="font-medium">{{ $santri->nis }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-medium">{{ $santri->kelas->nama_kelas ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Asrama</p>
                    <p class="font-medium">{{ $santri->asrama->nama_asrama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">NISN</p>
                    <p class="font-medium">{{ $santri->nisn ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Nama Lengkap</p>
                    <p class="font-medium">{{ $santri->nama }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Nama Panggilan</p>
                    <p class="font-medium">{{ $santri->nama_panggilan ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jenis Kelamin</p>
                    <p class="font-medium">
                        {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Agama</p>
                    <p class="font-medium">{{ $santri->agama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tempat Lahir</p>
                    <p class="font-medium">{{ $santri->tempat_lahir ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal Lahir</p>
                    <p class="font-medium">{{ $santri->tanggal_lahir ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal Masuk</p>
                    <p class="font-medium">{{ $santri->tanggal_masuk ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Status Mukim</p>
                    <p class="font-medium">
                        {{ $santri->status_mukim == 'mukim' ? 'Mukim' : 'Non Mukim' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h3 class="font-semibold text-lg mb-4">
            Data Orang Tua / Wali
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Nama Ayah</p>
                <p class="font-medium">{{ $santri->nama_ayah ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP Ayah</p>
                <p class="font-medium">{{ $santri->no_hp_ayah ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Nama Ibu</p>
                <p class="font-medium">{{ $santri->nama_ibu ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP Ibu</p>
                <p class="font-medium">{{ $santri->no_hp_ibu ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">Nama Wali</p>
                <p class="font-medium">{{ $santri->nama_wali ?? '-' }}</p>
            </div>

            <div>
                <p class="text-gray-500">No HP Wali</p>
                <p class="font-medium">{{ $santri->no_hp_wali ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-5 text-sm">
            <p class="text-gray-500">Alamat</p>
            <p class="font-medium">{{ $santri->alamat ?? '-' }}</p>
        </div>
    </div>

</div>
@endsection
