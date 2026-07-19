<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppSetting;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class SettingController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.setting.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => [
                'required',
                'string',
                'max:100',
            ],

            'pondok_name' => [
                'required',
                'string',
                'max:150',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
            ],

            'footer_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'nullable',
                'file',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],

            'favicon' => [
                'nullable',
                'file',
                'mimes:png,jpg,jpeg,webp,ico',
                'max:1024',
            ],
        ]);

        try {
            Setting::setValue('app_name', $validated['app_name']);
            Setting::setValue('pondok_name', $validated['pondok_name']);
            Setting::setValue('address', $validated['address'] ?? null);
            Setting::setValue('phone', $validated['phone'] ?? null);
            Setting::setValue('email', $validated['email'] ?? null);
            Setting::setValue('footer_text', $validated['footer_text'] ?? null);

            if ($request->hasFile('logo')) {
                $this->saveUploadedFile(
                    request: $request,
                    inputName: 'logo',
                    settingKey: 'logo'
                );
            }

            if ($request->hasFile('favicon')) {
                $this->saveUploadedFile(
                    request: $request,
                    inputName: 'favicon',
                    settingKey: 'favicon'
                );
            }

            AppSetting::clearCache();

            return redirect()
                ->route('admin.setting.edit')
                ->with('success', 'Pengaturan sistem berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'upload' => 'Pengaturan gagal disimpan. ' . $exception->getMessage(),
                ]);
        }
    }

    private function saveUploadedFile(
        Request $request,
        string $inputName,
        string $settingKey
    ): void {
        $uploadedFile = $request->file($inputName);

        if (! $uploadedFile || ! $uploadedFile->isValid()) {
            throw new \RuntimeException(
                "File {$inputName} tidak valid atau gagal diunggah."
            );
        }

        $oldPath = Setting::getValue($settingKey);

        if ($oldPath) {
            $oldPath = AppSetting::normalizeStoragePath((string) $oldPath);
        }

        $newPath = $uploadedFile->store('settings', 'public');

        if (! $newPath) {
            throw new \RuntimeException(
                "File {$inputName} gagal disimpan ke storage."
            );
        }

        if (! Storage::disk('public')->exists($newPath)) {
            throw new \RuntimeException(
                "File {$inputName} tidak ditemukan setelah disimpan."
            );
        }

        Setting::setValue($settingKey, $newPath);

        if (
            $oldPath &&
            $oldPath !== $newPath &&
            Storage::disk('public')->exists($oldPath)
        ) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
