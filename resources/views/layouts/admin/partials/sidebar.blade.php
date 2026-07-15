<aside class="w-72 bg-emerald-800 text-white flex flex-col shadow-xl">

    <div class="h-20 flex items-center px-6 border-b border-emerald-700">
        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center overflow-hidden">
            @if (\App\Helpers\AppSetting::logo())
                <img src="{{ \App\Helpers\AppSetting::logo() }}" alt="Logo" class="w-full h-full object-contain p-1">
            @else
                <x-heroicon-o-building-library class="w-7 h-7 text-white" />
            @endif
        </div>

        <div class="ml-3">
            <h1 class="font-bold text-lg">
                {{ \App\Helpers\AppSetting::appName() }}
            </h1>
            <p class="text-xs text-emerald-200">
                {{ \App\Helpers\AppSetting::pondokName() }}
            </p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-5">

        <p class="px-6 mb-3 text-xs uppercase tracking-widest text-emerald-300">
            Dashboard
        </p>

        <a href="{{ route('admin.dashboard') }}"
            class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.dashboard') }}">
            <x-heroicon-o-home class="w-5 h-5" />
            <span>Dashboard</span>
        </a>

        <p class="px-6 mt-6 mb-3 text-xs uppercase tracking-widest text-emerald-300">
            Master Data
        </p>

        @can('santri.view')
            <a href="{{ route('admin.santri.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.santri.*') }}">
                <x-heroicon-o-academic-cap class="w-5 h-5" />
                <span>Data Santri</span>
            </a>
        @endcan
        @can('kelas.view')
            <a href="{{ route('admin.kelas.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.kelas.*') }}">
                <x-heroicon-o-building-office-2 class="w-5 h-5" />
                <span>Data Kelas</span>
            </a>
        @endcan
        @can('asrama.view')
            <a href="{{ route('admin.asrama.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.asrama.*') }}">
                <x-heroicon-o-building-office class="w-5 h-5" />
                <span>Data Asrama</span>
            </a>
        @endcan

        @can('tahun-ajaran.view')
            <a href="{{ route('admin.tahun-ajaran.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.tahun-ajaran.*') }}">
                <x-heroicon-o-calendar-days class="w-5 h-5" />
                <span>Tahun Ajaran</span>
            </a>
        @endcan
        @can('wali-santri.view')
            @can('wali-santri.view')
                <a href="{{ route('admin.wali-santri.index') }}"
                    class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.wali-santri.*') }}">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span>Wali Santri</span>
                </a>
            @endcan
        @endcan

        <p class="px-6 mt-6 mb-3 text-xs uppercase tracking-widest text-emerald-300">
            Keuangan
        </p>

        @can('jenis-pembayaran.view')
            <a href="{{ route('admin.jenis-pembayaran.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.jenis-pembayaran.*') }}">
                <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                <span>Jenis Pembayaran</span>
            </a>
        @endcan
        @can('tagihan.view')
            <a href="{{ route('admin.tagihan.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.tagihan.*') }}">
                <x-heroicon-o-document-text class="w-5 h-5" />
                <span>Tagihan</span>
            </a>
        @endcan
        @can('pembayaran.view')
            <a href="{{ route('admin.pembayaran.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.pembayaran.*') }}">
                <x-heroicon-o-credit-card class="w-5 h-5" />
                <span>Pembayaran</span>
            </a>
        @endcan

        @can('pembayaran.view')
            <a href="{{ route('admin.konfirmasi-pembayaran.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.konfirmasi-pembayaran.*') }}">
                <x-heroicon-o-check-badge class="w-5 h-5" />
                <span>Konfirmasi Bayar</span>
            </a>
        @endcan
        @can('laporan.view')
            <a href="{{ route('admin.laporan.tunggakan') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.laporan.tunggakan*') }}">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                <span>Tunggakan</span>
            </a>
        @endcan
        @can('laporan.view')
            <a href="{{ route('admin.laporan.kartu-santri') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.laporan.kartu-santri*') }}">
                <x-heroicon-o-identification class="w-5 h-5" />
                <span>Kartu Tagihan</span>
            </a>
        @endcan
        @can('kas.view')
            <a href="{{ route('admin.kas.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.kas.*') }}">
                <x-heroicon-o-banknotes class="w-5 h-5" />
                <span>Kas Pondok</span>
            </a>
        @endcan
        @can('laporan.view')
            <a href="{{ route('admin.laporan.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.laporan.*') }}">
                <x-heroicon-o-chart-bar-square class="w-5 h-5" />
                <span>Laporan</span>
            </a>
        @endcan

        @role('Super Admin')
            <p class="px-6 mt-6 mb-3 text-xs uppercase tracking-widest text-emerald-300">
                Pengaturan
            </p>

            <a href="{{ route('admin.user.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.user.*') }}">
                <x-heroicon-o-users class="w-5 h-5" />
                <span>User</span>
            </a>

            <a href="{{ route('admin.role-permission.index') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.role-permission.*') }}">
                <x-heroicon-o-shield-check class="w-5 h-5" />
                <span>Role & Permission</span>
            </a>

            <a href="{{ route('admin.setting.edit') }}"
                class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ \App\Helpers\Menu::active('admin.setting.*') }}">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                <span>Pengaturan Sistem</span>
            </a>
        @endrole

    </div>

    <div class="border-t border-emerald-700 p-5">
        <div class="flex items-center">
            <div class="w-11 h-11 rounded-full bg-emerald-600 flex items-center justify-center">
                <x-heroicon-o-user class="w-5 h-5" />
            </div>

            <div class="ml-3">
                <p class="font-semibold text-sm">
                    {{ Auth::user()->name }}
                </p>

                <p class="text-xs text-emerald-200">
                    {{ Auth::user()->getRoleNames()->first() }}
                </p>
            </div>
        </div>
    </div>

</aside>
