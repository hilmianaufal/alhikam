@extends('layouts.admin.app')

@section('title', 'Pengaturan Sistem')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';

    $logo = $settings['logo'] ?? null;
    $favicon = $settings['favicon'] ?? null;
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
        <p class="text-sm text-gray-500 mt-1">
            Ubah identitas aplikasi, informasi pondok, logo, dan favicon.
        </p>
    </div>

    <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Identitas Aplikasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $labelClass }}">Nama Aplikasi</label>
                    <input type="text"
                           name="app_name"
                           value="{{ old('app_name', $settings['app_name'] ?? 'Al Ishlah Pay') }}"
                           class="{{ $inputClass }}">
                    @error('app_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Pondok</label>
                    <input type="text"
                           name="pondok_name"
                           value="{{ old('pondok_name', $settings['pondok_name'] ?? 'Ponpes Al Ishlah Jatireja - Subang') }}"
                           class="{{ $inputClass }}">
                    @error('pondok_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP / Telepon</label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $settings['phone'] ?? '') }}"
                           placeholder="Contoh: 08xxxxxxxxxx"
                           class="{{ $inputClass }}">
                    @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $settings['email'] ?? '') }}"
                           placeholder="Contoh: admin@alishlahpay.test"
                           class="{{ $inputClass }}">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Alamat Pondok</label>
                    <textarea name="address"
                              rows="4"
                              class="{{ $inputClass }}"
                              placeholder="Alamat lengkap pondok">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="{{ $labelClass }}">Teks Footer</label>
                    <input type="text"
                           name="footer_text"
                           value="{{ old('footer_text', $settings['footer_text'] ?? '© Al Ishlah Pay') }}"
                           class="{{ $inputClass }}">
                    @error('footer_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Logo & Favicon</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border rounded-2xl p-5">
                    <label class="{{ $labelClass }}">Logo Aplikasi</label>

                    <div class="mb-4">
                        @if ($logo)
                            <img src="{{ asset('storage/' . $logo) }}"
                                 alt="Logo"
                                 class="w-24 h-24 object-contain rounded-xl border bg-gray-50 p-2">
                        @else
                            <div class="w-24 h-24 rounded-xl border bg-gray-50 flex items-center justify-center text-gray-400">
                                Logo
                            </div>
                        @endif
                    </div>

                    <input type="file"
                           name="logo"
                           accept="image/*"
                           class="{{ $inputClass }}">

                    <p class="text-xs text-gray-500 mt-2">
                        Format: PNG, JPG, JPEG, WEBP. Maksimal 2MB.
                    </p>

                    @error('logo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border rounded-2xl p-5">
                    <label class="{{ $labelClass }}">Favicon</label>

                    <div class="mb-4">
                        @if ($favicon)
                            <img src="{{ asset('storage/' . $favicon) }}"
                                 alt="Favicon"
                                 class="w-16 h-16 object-contain rounded-xl border bg-gray-50 p-2">
                        @else
                            <div class="w-16 h-16 rounded-xl border bg-gray-50 flex items-center justify-center text-gray-400 text-xs">
                                Icon
                            </div>
                        @endif
                    </div>

                    <input type="file"
                           name="favicon"
                           accept="image/*,.ico"
                           class="{{ $inputClass }}">

                    <p class="text-xs text-gray-500 mt-2">
                        Disarankan ukuran 192x192 atau 512x512.
                    </p>

                    @error('favicon') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Simpan Pengaturan
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
