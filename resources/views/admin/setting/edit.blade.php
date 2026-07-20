@extends('layouts.admin.app')

@section('title', 'Pengaturan Sistem')

@section('content')
    @php
        $inputClass =
            'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
        $labelClass = 'mb-2 block text-sm font-semibold text-gray-700';

        $logo = $settings['logo'] ?? null;
        $favicon = $settings['favicon'] ?? null;

        $logoUrl = \App\Helpers\AppSetting::storageUrl($logo);
        $faviconUrl = \App\Helpers\AppSetting::storageUrl($favicon);
    @endphp

    <div class="space-y-6">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800">
                Pengaturan Sistem
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Ubah identitas aplikasi, informasi pondok, logo, dan favicon.
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-600" />
                    </div>

                    <div>
                        <h3 class="font-bold text-red-800">
                            Pengaturan gagal disimpan
                        </h3>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-check-circle class="h-6 w-6 text-emerald-600" />

                    <p class="font-semibold text-emerald-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-800">
                    Identitas Aplikasi
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="app_name" class="{{ $labelClass }}">
                            Nama Aplikasi
                        </label>

                        <input type="text" name="app_name" id="app_name"
                            value="{{ old('app_name', $settings['app_name'] ?? 'Al Ishlah Pay') }}"
                            class="{{ $inputClass }}" required>

                        @error('app_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="pondok_name" class="{{ $labelClass }}">
                            Nama Pondok
                        </label>

                        <input type="text" name="pondok_name" id="pondok_name"
                            value="{{ old('pondok_name', $settings['pondok_name'] ?? 'Ponpes Al Ishlah Jatireja - Subang') }}"
                            class="{{ $inputClass }}" required>

                        @error('pondok_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="{{ $labelClass }}">
                            Nomor HP / Telepon
                        </label>

                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="Contoh: 08xxxxxxxxxx"
                            class="{{ $inputClass }}">

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="{{ $labelClass }}">
                            Email
                        </label>

                        <input type="email" name="email" id="email"
                            value="{{ old('email', $settings['email'] ?? '') }}"
                            placeholder="Contoh: admin@alishlahpay.test" class="{{ $inputClass }}">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="{{ $labelClass }}">
                            Alamat Pondok
                        </label>

                        <textarea name="address" id="address" rows="4" class="{{ $inputClass }}"
                            placeholder="Masukkan alamat lengkap pondok">{{ old('address', $settings['address'] ?? '') }}</textarea>

                        @error('address')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-gray-800">
                                Pengaturan Tanda Tangan
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Informasi ini akan ditampilkan pada struk dan dokumen cetak.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div>
                                <label for="signature_city" class="{{ $labelClass }}">
                                    Kota Tanda Tangan
                                </label>

                                <input type="text" name="signature_city" id="signature_city"
                                    value="{{ old('signature_city', $settings['signature_city'] ?? 'Subang') }}"
                                    placeholder="Contoh: Subang" class="{{ $inputClass }}">

                                @error('signature_city')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="signature_name" class="{{ $labelClass }}">
                                    Nama Penandatangan
                                </label>

                                <input type="text" name="signature_name" id="signature_name"
                                    value="{{ old('signature_name', $settings['signature_name'] ?? '') }}"
                                    placeholder="Contoh: Ahmad Fauzi" class="{{ $inputClass }}">

                                @error('signature_name')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="signature_position" class="{{ $labelClass }}">
                                    Jabatan Penandatangan
                                </label>

                                <input type="text" name="signature_position" id="signature_position"
                                    value="{{ old('signature_position', $settings['signature_position'] ?? 'Bendahara') }}"
                                    placeholder="Contoh: Bendahara Pondok" class="{{ $inputClass }}">

                                @error('signature_position')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="footer_text" class="{{ $labelClass }}">
                            Teks Footer
                        </label>

                        <input type="text" name="footer_text" id="footer_text"
                            value="{{ old('footer_text', $settings['footer_text'] ?? '© Al Ishlah Pay') }}"
                            class="{{ $inputClass }}">

                        @error('footer_text')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-800">
                    Logo dan Favicon
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <label for="logo" class="{{ $labelClass }}">
                            Logo Aplikasi
                        </label>

                        <div
                            class="mb-4 flex h-36 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-gray-300 bg-gray-50">
                            <img src="{{ $logoUrl ?? '' }}" alt="Logo aplikasi" id="logo-preview"
                                class="{{ $logoUrl ? '' : 'hidden' }} h-28 w-28 object-contain p-2"
                                onerror="handleImageError(this, 'logo-placeholder')">

                            <div id="logo-placeholder" class="{{ $logoUrl ? 'hidden' : '' }} text-center text-gray-400">
                                <x-heroicon-o-photo class="mx-auto h-9 w-9" />

                                <p class="mt-2 text-xs">
                                    Belum ada logo
                                </p>
                            </div>
                        </div>

                        <input type="file" name="logo" id="logo"
                            accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp" class="{{ $inputClass }}"
                            onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">

                        <p class="mt-2 text-xs text-gray-500">
                            Format PNG, JPG, JPEG, atau WEBP. Maksimal 2 MB.
                        </p>

                        @if ($logo)
                            <p class="mt-2 break-all text-xs text-emerald-700">
                                File tersimpan:
                                {{ \App\Helpers\AppSetting::normalizeStoragePath($logo) }}
                            </p>

                            <a href="{{ $logoUrl }}" target="_blank"
                                class="mt-2 inline-flex text-xs font-semibold text-blue-600 hover:underline">
                                Buka file logo
                            </a>
                        @endif

                        @error('logo')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-gray-200 p-5">
                        <label for="favicon" class="{{ $labelClass }}">
                            Favicon
                        </label>

                        <div
                            class="mb-4 flex h-36 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-gray-300 bg-gray-50">
                            <img src="{{ $faviconUrl ?? '' }}" alt="Favicon aplikasi" id="favicon-preview"
                                class="{{ $faviconUrl ? '' : 'hidden' }} h-20 w-20 object-contain p-2"
                                onerror="handleImageError(this, 'favicon-placeholder')">

                            <div id="favicon-placeholder"
                                class="{{ $faviconUrl ? 'hidden' : '' }} text-center text-gray-400">
                                <x-heroicon-o-globe-alt class="mx-auto h-9 w-9" />

                                <p class="mt-2 text-xs">
                                    Belum ada favicon
                                </p>
                            </div>
                        </div>

                        <input type="file" name="favicon" id="favicon"
                            accept=".png,.jpg,.jpeg,.webp,.ico,image/png,image/jpeg,image/webp,image/x-icon"
                            class="{{ $inputClass }}"
                            onchange="previewImage(this, 'favicon-preview', 'favicon-placeholder')">

                        <p class="mt-2 text-xs text-gray-500">
                            Format PNG, JPG, WEBP, atau ICO. Maksimal 1 MB.
                        </p>

                        @if ($favicon)
                            <p class="mt-2 break-all text-xs text-emerald-700">
                                File tersimpan:
                                {{ \App\Helpers\AppSetting::normalizeStoragePath($favicon) }}
                            </p>

                            <a href="{{ $faviconUrl }}" target="_blank"
                                class="mt-2 inline-flex text-xs font-semibold text-blue-600 hover:underline">
                                Buka file favicon
                            </a>
                        @endif

                        @error('favicon')
                            <p class="mt-2 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.dashboard') }}"
                        class="rounded-xl bg-gray-100 px-5 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-200">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 font-semibold text-white transition hover:bg-emerald-800">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function handleImageError(image, placeholderId) {
            image.classList.add('hidden');

            const placeholder = document.getElementById(placeholderId);

            if (placeholder) {
                placeholder.classList.remove('hidden');
            }
        }

        function previewImage(input, previewId, placeholderId) {
            const file = input.files && input.files[0];

            if (!file) {
                return;
            }

            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            const isFavicon = input.name === 'favicon';

            const allowedExtensions = isFavicon ?
                ['png', 'jpg', 'jpeg', 'webp', 'ico'] :
                ['png', 'jpg', 'jpeg', 'webp'];

            const extension = file.name
                .split('.')
                .pop()
                .toLowerCase();

            if (!allowedExtensions.includes(extension)) {
                input.value = '';

                showUploadError(
                    'Format file tidak didukung.',
                    isFavicon ?
                    'Gunakan PNG, JPG, JPEG, WEBP, atau ICO.' :
                    'Gunakan PNG, JPG, JPEG, atau WEBP.'
                );

                return;
            }

            const maximumSize = isFavicon ?
                1024 * 1024 :
                2 * 1024 * 1024;

            if (file.size > maximumSize) {
                input.value = '';

                showUploadError(
                    'Ukuran file terlalu besar.',
                    isFavicon ?
                    'Ukuran favicon maksimal 1 MB.' :
                    'Ukuran logo maksimal 2 MB.'
                );

                return;
            }

            const reader = new FileReader();

            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.classList.remove('hidden');

                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };

            reader.readAsDataURL(file);
        }

        function showUploadError(title, text) {
            if (window.Swal) {
                window.Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                });

                return;
            }

            alert(title + '\n' + text);
        }
    </script>
@endsection
