<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SantriExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return Santri::query()
            ->with(['kelas', 'asrama', 'user'])
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('nama_panggilan', 'like', "%{$search}%")
                        ->orWhere('nama_ayah', 'like', "%{$search}%")
                        ->orWhere('nama_ibu', 'like', "%{$search}%")
                        ->orWhere('nama_wali', 'like', "%{$search}%")
                        ->orWhere('no_hp_wali', 'like', "%{$search}%");
                });
            })
            ->when($this->filters['kelas_id'] ?? null, function ($query, $kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->when($this->filters['asrama_id'] ?? null, function ($query, $asramaId) {
                $query->where('asrama_id', $asramaId);
            })
            ->when($this->filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Nama Panggilan',
            'Jenis Kelamin',
            'Kelas',
            'Asrama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Tanggal Masuk',
            'Nama Ayah',
            'Nama Ibu',
            'Nama Wali',
            'No HP Ayah',
            'No HP Ibu',
            'No HP Wali',
            'Akun Wali',
            'Status Mukim',
            'Status Santri',
            'Alamat',
        ];
    }

    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->nisn,
            $santri->nama,
            $santri->nama_panggilan,
            $santri->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $santri->kelas->nama_kelas ?? '-',
            $santri->asrama->nama_asrama ?? '-',
            $santri->tempat_lahir,
            $santri->tanggal_lahir,
            $santri->tanggal_masuk,
            $santri->nama_ayah,
            $santri->nama_ibu,
            $santri->nama_wali,
            $santri->no_hp_ayah,
            $santri->no_hp_ibu,
            $santri->no_hp_wali,
            $santri->user->email ?? '-',
            ucfirst(str_replace('_', ' ', $santri->status_mukim)),
            ucfirst($santri->status),
            $santri->alamat,
        ];
    }
}
