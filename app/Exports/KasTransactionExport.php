<?php

namespace App\Exports;

use App\Models\KasTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KasTransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return KasTransaction::query()
            ->with('user')
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->where('kode', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            })
            ->when($this->filters['tipe'] ?? null, function ($query, $tipe) {
                $query->where('tipe', $tipe);
            })
            ->latest('tanggal')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Tanggal',
            'Tipe',
            'Kategori',
            'Metode',
            'Sumber',
            'Nominal',
            'Dibuat Oleh',
            'Keterangan',
        ];
    }

    public function map($kas): array
    {
        return [
            $kas->kode,
            $kas->tanggal?->format('d/m/Y'),
            ucfirst($kas->tipe),
            $kas->kategori,
            ucfirst($kas->metode),
            ucfirst($kas->sumber),
            (float) $kas->nominal,
            $kas->user->name ?? '-',
            $kas->keterangan,
        ];
    }
}
