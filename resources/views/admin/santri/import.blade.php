@extends('layouts.admin.app')

@section('title', 'Import Santri')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Import Data Santri</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Upload file Excel untuk menambah atau memperbarui data santri.
                </p>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
                <a href="{{ route('admin.santri.import.template') }}"
                   class="px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 text-center">
                    Download Template
                </a>

                <a href="{{ route('admin.santri.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if (session('import_failures'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <h2 class="font-bold text-red-700 mb-3">Data gagal diimport</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-red-700">
                            <th class="py-2">Baris</th>
                            <th class="py-2">Kolom</th>
                            <th class="py-2">Error</th>
                            <th class="py-2">Value</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-red-200">
                        @foreach (session('import_failures') as $failure)
                            <tr>
                                <td class="py-2">{{ $failure->row() }}</td>
                                <td class="py-2">{{ $failure->attribute() }}</td>
                                <td class="py-2">
                                    @foreach ($failure->errors() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </td>
                                <td class="py-2">
                                    {{ $failure->values()[$failure->attribute()] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Upload File Excel</h2>

            <form action="{{ route('admin.santri.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Excel
                    </label>

                    <input type="file"
                           name="file"
                           accept=".xlsx,.xls,.csv"
                           class="{{ $inputClass }}">

                    <p class="text-xs text-gray-500 mt-2">
                        Format file: .xlsx, .xls, atau .csv. Maksimal 5MB.
                    </p>

                    @error('file')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.santri.index') }}"
                       class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Import Sekarang
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Petunjuk Import</h2>

            <div class="space-y-4 text-sm text-gray-600">
                <div class="border rounded-xl p-4">
                    <p class="font-semibold text-gray-800 mb-1">1. Download template</p>
                    <p>Gunakan template agar nama kolom sesuai dengan sistem.</p>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="font-semibold text-gray-800 mb-1">2. Isi data santri</p>
                    <p>Kolom wajib: NIS, Nama, dan Jenis Kelamin.</p>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="font-semibold text-gray-800 mb-1">3. Format penting</p>
                    <p>Jenis kelamin isi dengan <strong>L</strong> atau <strong>P</strong>.</p>
                    <p>Status mukim isi dengan <strong>mukim</strong> atau <strong>non_mukim</strong>.</p>
                    <p>Status santri isi dengan <strong>aktif</strong> atau <strong>nonaktif</strong>.</p>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="font-semibold text-gray-800 mb-1">4. Data lama otomatis update</p>
                    <p>Jika NIS sudah ada, data santri akan diperbarui.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
