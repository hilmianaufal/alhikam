<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppSetting;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.setting.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'pondok_name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,ico', 'max:1024'],
        ]);

        Setting::setValue('app_name', $validated['app_name']);
        Setting::setValue('pondok_name', $validated['pondok_name']);
        Setting::setValue('address', $validated['address'] ?? null);
        Setting::setValue('phone', $validated['phone'] ?? null);
        Setting::setValue('email', $validated['email'] ?? null);
        Setting::setValue('footer_text', $validated['footer_text'] ?? null);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::getValue('logo');

            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::setValue('logo', $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::getValue('favicon');

            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::setValue('favicon', $faviconPath);
        }
        AppSetting::clearCache();
        return redirect()
            ->route('admin.setting.edit')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
