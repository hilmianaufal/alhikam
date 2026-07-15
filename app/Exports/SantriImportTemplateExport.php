<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SantriImportTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nis',
            'nisn',
            'nama',
            'nama_panggilan',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'tanggal_masuk',
            'kelas',
            'asrama',
            'status_mukim',
            'nama_ayah',
            'nama_ibu',
            'nama_wali',
            'no_hp_ayah',
            'no_hp_ibu',
            'no_hp_wali',
            'alamat',
            'status',
        ];
    }

    public function array(): array
    {
        return [
            [
                'S001',
                '1000000001',
                'Muhammad Fikri',
                'Fikri',
                'L',
                'Subang',
                '2011-03-12',
                'Islam',
                '2025-07-01',
                'Kelas 7A',
                'Asrama Abu Bakar',
                'mukim',
                'Bapak Rahmat',
                'Ibu Siti',
                'Bapak Rahmat',
                '081111111101',
                '081111111201',
                '081111111001',
                'Jatireja, Subang',
                'aktif',
            ],
        ];
    }
}
