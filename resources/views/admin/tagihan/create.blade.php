@extends('layouts.admin.app')

@section('title', 'Generate Tagihan')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Generate Tagihan</h1>
        <p class="text-sm text-gray-500 mt-1">Buat tagihan massal untuk santri.</p>
    </div>

    <form action="{{ route('admin.tagihan.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Jenis Pembayaran</label>
                <select name="jenis_pembayaran_id" class="{{ $inputClass }}">
                    <option value="">Pilih Jenis Pembayaran</option>
                    @foreach ($jenisPembayarans as $item)
                        <option value="{{ $item->id }}"
                                data-nominal="{{ $item->nominal }}"
                                @selected(old('jenis_pembayaran_id') == $item->id)>
                            {{ $item->nama }} - Rp{{ number_format($item->nominal, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_pembayaran_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="{{ $inputClass }}">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach ($tahunAjarans as $item)
                        <option value="{{ $item->id }}" @selected(old('tahun_ajaran_id') == $item->id || $item->is_active)>
                            {{ $item->nama_tahun }} - {{ ucfirst($item->semester) }}
                            {{ $item->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Bulan</label>
                <select name="bulan" class="{{ $inputClass }}">
                    <option value="">Tanpa Bulan</option>
                    @foreach ([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ] as $key => $value)
                        <option value="{{ $key }}" @selected(old('bulan', now()->month) == $key)>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @error('bulan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tahun</label>
                <input type="number"
                       name="tahun"
                       value="{{ old('tahun', now()->year) }}"
                       class="{{ $inputClass }}">
                @error('tahun') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal', 0) }}"
                       min="0"
                       class="{{ $inputClass }}">
                @error('nominal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tanggal Jatuh Tempo</label>
                <input type="date"
                       name="tanggal_jatuh_tempo"
                       value="{{ old('tanggal_jatuh_tempo') }}"
                       class="{{ $inputClass }}">
                @error('tanggal_jatuh_tempo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t pt-6">
            <h2 class="font-semibold text-gray-800 mb-4">Target Santri</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="{{ $labelClass }}">Target</label>
                    <select name="target" class="{{ $inputClass }}">
                        <option value="semua" @selected(old('target') == 'semua')>Semua Santri Aktif</option>
                        <option value="kelas" @selected(old('target') == 'kelas')>Per Kelas</option>
                        <option value="asrama" @selected(old('target') == 'asrama')>Per Asrama</option>
                    </select>
                    @error('target') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Kelas</label>
                    <select name="kelas_id" class="{{ $inputClass }}">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" @selected(old('kelas_id') == $item->id)>
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Asrama</label>
                    <select name="asrama_id" class="{{ $inputClass }}">
                        <option value="">Pilih Asrama</option>
                        @foreach ($asramas as $item)
                            <option value="{{ $item->id }}" @selected(old('asrama_id') == $item->id)>
                                {{ $item->nama_asrama }}
                            </option>
                        @endforeach
                    </select>
                    @error('asrama_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div>
            <label class="{{ $labelClass }}">Keterangan</label>
            <textarea name="keterangan"
                      rows="4"
                      class="{{ $inputClass }}"
                      placeholder="Contoh: Tagihan syahriyah bulan ini">{{ old('keterangan') }}</textarea>
            @error('keterangan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.tagihan.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Generate Tagihan
            </button>
        </div>
    </form>
</div>
@endsection
