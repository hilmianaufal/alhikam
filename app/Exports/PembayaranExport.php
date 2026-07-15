<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        return Pembayaran::query()
            ->with(['tagihan.jenisPembayaran', 'santri.kelas', 'santri.asrama', 'user'])
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->where('kode_transaksi', 'like', "%{$search}%")
                    ->orWhereHas('santri', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tagihan.jenisPembayaran', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest('tanggal_bayar')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal Bayar',
            'Nama Santri',
            'NIS',
            'Kelas',
            'Asrama',
            'Jenis Pembayaran',
            'Metode',
            'Jumlah Bayar',
            'Kasir',
            'Keterangan',
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->kode_transaksi,
            $pembayaran->tanggal_bayar?->format('d/m/Y'),
            $pembayaran->santri->nama ?? '-',
            $pembayaran->santri->nis ?? '-',
            $pembayaran->santri->kelas->nama_kelas ?? '-',
            $pembayaran->santri->asrama->nama_asrama ?? '-',
            $pembayaran->tagihan->jenisPembayaran->nama ?? '-',
            ucfirst($pembayaran->metode),
            (float) $pembayaran->jumlah_bayar,
            $pembayaran->user->name ?? '-',
            $pembayaran->keterangan,
        ];
    }
}
