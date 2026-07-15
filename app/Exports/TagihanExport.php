<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TagihanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private array $bulanList = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return Tagihan::query()
            ->with(['santri.kelas', 'santri.asrama', 'jenisPembayaran', 'tahunAjaran'])
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                })
                ->orWhereHas('jenisPembayaran', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($this->filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filters['bulan'] ?? null, function ($query, $bulan) {
                $query->where('bulan', $bulan);
            })
            ->when($this->filters['tahun'] ?? null, function ($query, $tahun) {
                $query->where('tahun', $tahun);
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Santri',
            'NIS',
            'Kelas',
            'Asrama',
            'Jenis Pembayaran',
            'Tahun Ajaran',
            'Periode',
            'Nominal',
            'Dibayar',
            'Sisa',
            'Status',
            'Jatuh Tempo',
            'Keterangan',
        ];
    }

    public function map($tagihan): array
    {
        $periode = $tagihan->bulan
            ? ($this->bulanList[$tagihan->bulan] ?? '-') . ' ' . $tagihan->tahun
            : '-';

        return [
            $tagihan->santri->nama ?? '-',
            $tagihan->santri->nis ?? '-',
            $tagihan->santri->kelas->nama_kelas ?? '-',
            $tagihan->santri->asrama->nama_asrama ?? '-',
            $tagihan->jenisPembayaran->nama ?? '-',
            $tagihan->tahunAjaran->nama_tahun ?? '-',
            $periode,
            (float) $tagihan->nominal,
            (float) $tagihan->dibayar,
            max((float) $tagihan->nominal - (float) $tagihan->dibayar, 0),
            ucfirst(str_replace('_', ' ', $tagihan->status)),
            $tagihan->tanggal_jatuh_tempo?->format('d/m/Y'),
            $tagihan->keterangan,
        ];
    }
}
