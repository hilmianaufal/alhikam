<?php

namespace App\Imports;

use App\Models\Asrama;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    public int $imported = 0;
    public int $updated = 0;

    public function model(array $row)
    {
        $kelasId = null;
        $asramaId = null;

        if (! empty($row['kelas'])) {
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => trim($row['kelas'])],
                [
                    'tingkat' => null,
                    'wali_kelas' => null,
                    'status' => 'aktif',
                ]
            );

            $kelasId = $kelas->id;
        }

        if (! empty($row['asrama'])) {
            $asrama = Asrama::firstOrCreate(
                ['nama_asrama' => trim($row['asrama'])],
                [
                    'kode_asrama' => null,
                    'musyrif' => null,
                    'kapasitas' => null,
                    'status' => 'aktif',
                ]
            );

            $asramaId = $asrama->id;
        }

        $existing = Santri::where('nis', $row['nis'])->first();

        $data = [
            'nis' => $row['nis'],
            'nisn' => $row['nisn'] ?? null,
            'nama' => $row['nama'],
            'nama_panggilan' => $row['nama_panggilan'] ?? null,
            'jenis_kelamin' => strtoupper($row['jenis_kelamin']),
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
            'agama' => $row['agama'] ?? 'Islam',
            'tanggal_masuk' => $row['tanggal_masuk'] ?? null,
            'kelas_id' => $kelasId,
            'asrama_id' => $asramaId,
            'status_mukim' => $row['status_mukim'] ?? 'mukim',
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'nama_wali' => $row['nama_wali'] ?? null,
            'no_hp_ayah' => $row['no_hp_ayah'] ?? null,
            'no_hp_ibu' => $row['no_hp_ibu'] ?? null,
            'no_hp_wali' => $row['no_hp_wali'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'status' => $row['status'] ?? 'aktif',
        ];

        if ($existing) {
            $existing->update($data);
            $this->updated++;

            return null;
        }

        $data['qr_token'] = (string) Str::uuid();

        $this->imported++;

        return new Santri($data);
    }

    public function rules(): array
    {
        return [
            '*.nis' => ['required', 'max:50'],
            '*.nisn' => ['nullable', 'max:50'],
            '*.nama' => ['required', 'max:255'],
            '*.nama_panggilan' => ['nullable', 'max:100'],
            '*.jenis_kelamin' => ['required', 'in:L,P,l,p'],
            '*.tempat_lahir' => ['nullable', 'max:255'],
            '*.tanggal_lahir' => ['nullable'],
            '*.agama' => ['nullable', 'max:50'],
            '*.tanggal_masuk' => ['nullable'],
            '*.kelas' => ['nullable', 'max:100'],
            '*.asrama' => ['nullable', 'max:150'],
            '*.status_mukim' => ['nullable', 'in:mukim,non_mukim'],
            '*.nama_ayah' => ['nullable', 'max:255'],
            '*.nama_ibu' => ['nullable', 'max:255'],
            '*.nama_wali' => ['nullable', 'max:255'],
            '*.no_hp_ayah' => ['nullable', 'max:30'],
            '*.no_hp_ibu' => ['nullable', 'max:30'],
            '*.no_hp_wali' => ['nullable', 'max:30'],
            '*.alamat' => ['nullable'],
            '*.status' => ['nullable', 'in:aktif,nonaktif'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.nis.required' => 'Kolom NIS wajib diisi.',
            '*.nama.required' => 'Kolom nama wajib diisi.',
            '*.jenis_kelamin.required' => 'Kolom jenis kelamin wajib diisi.',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            '*.status_mukim.in' => 'Status mukim harus mukim atau non_mukim.',
            '*.status.in' => 'Status harus aktif atau nonaktif.',
        ];
    }
}
