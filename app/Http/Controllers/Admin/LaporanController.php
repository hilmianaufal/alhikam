<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\KasTransaction;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?: now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?: now()->endOfMonth()->toDateString();

        $data = $this->getRingkasan($tanggalMulai, $tanggalSelesai);

        $kasTransactions = KasTransaction::query()
            ->with('user')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->latest('tanggal')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pembayarans = Pembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran', 'user'])
            ->whereBetween('tanggal_bayar', [$tanggalMulai, $tanggalSelesai])
            ->latest('tanggal_bayar')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.laporan.index', array_merge($data, compact(
            'tanggalMulai',
            'tanggalSelesai',
            'kasTransactions',
            'pembayarans'
        )));
    }

    public function print(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?: now()->startOfMonth()->toDateString();
        $tanggalSelesai = $request->tanggal_selesai ?: now()->endOfMonth()->toDateString();

        $data = $this->getRingkasan($tanggalMulai, $tanggalSelesai);

        $kasTransactions = KasTransaction::query()
            ->with('user')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->latest('tanggal')
            ->latest()
            ->get();

        $pembayarans = Pembayaran::query()
            ->with(['santri.kelas', 'tagihan.jenisPembayaran', 'user'])
            ->whereBetween('tanggal_bayar', [$tanggalMulai, $tanggalSelesai])
            ->latest('tanggal_bayar')
            ->latest()
            ->get();

        return view('admin.laporan.print', array_merge($data, compact(
            'tanggalMulai',
            'tanggalSelesai',
            'kasTransactions',
            'pembayarans'
        )));
    }

    public function tunggakan(Request $request)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get();
        $asramas = Asrama::where('status', 'aktif')->orderBy('nama_asrama')->get();
        $tahunAjarans = TahunAjaran::where('status', 'aktif')->latest()->get();

        $status = $request->status ?: 'menunggak';

        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->with([
                'tagihans' => function ($query) use ($request, $status) {
                    $this->applyTagihanTunggakanFilter($query, $request, $status);

                    $query->with(['jenisPembayaran', 'tahunAjaran'])
                        ->latest();
                }
            ])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', "%{$request->search}%")
                        ->orWhere('nis', 'like', "%{$request->search}%")
                        ->orWhere('nisn', 'like', "%{$request->search}%");
                });
            })
            ->when($request->kelas_id, function ($query) use ($request) {
                $query->where('kelas_id', $request->kelas_id);
            })
            ->when($request->asrama_id, function ($query) use ($request) {
                $query->where('asrama_id', $request->asrama_id);
            })
            ->whereHas('tagihans', function ($query) use ($request, $status) {
                $this->applyTagihanTunggakanFilter($query, $request, $status);
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $santris->getCollection()->transform(function ($santri) {
            $santri->total_tagihan = $santri->tagihans->sum('nominal');
            $santri->total_dibayar = $santri->tagihans->sum('dibayar');
            $santri->total_sisa = max($santri->total_tagihan - $santri->total_dibayar, 0);
            $santri->jumlah_tagihan = $santri->tagihans->count();

            return $santri;
        });

        $summaryQuery = Tagihan::query()
            ->whereHas('santri', function ($query) use ($request) {
                $query->when($request->search, function ($q) use ($request) {
                    $q->where(function ($qq) use ($request) {
                        $qq->where('nama', 'like', "%{$request->search}%")
                            ->orWhere('nis', 'like', "%{$request->search}%")
                            ->orWhere('nisn', 'like', "%{$request->search}%");
                    });
                })
                ->when($request->kelas_id, function ($q) use ($request) {
                    $q->where('kelas_id', $request->kelas_id);
                })
                ->when($request->asrama_id, function ($q) use ($request) {
                    $q->where('asrama_id', $request->asrama_id);
                });
            });

        $this->applyTagihanTunggakanFilter($summaryQuery, $request, $status);

        $totalTagihan = (clone $summaryQuery)->sum('nominal');
        $totalDibayar = (clone $summaryQuery)->sum('dibayar');
        $totalSisa = max($totalTagihan - $totalDibayar, 0);
        $jumlahTagihan = (clone $summaryQuery)->count();
        $jumlahSantri = (clone $summaryQuery)->distinct('santri_id')->count('santri_id');

        return view('admin.laporan.tunggakan', compact(
            'kelas',
            'asramas',
            'tahunAjarans',
            'santris',
            'status',
            'totalTagihan',
            'totalDibayar',
            'totalSisa',
            'jumlahTagihan',
            'jumlahSantri'
        ));
    }

    public function printTunggakan(Request $request)
    {
        $status = $request->status ?: 'menunggak';

        $santris = Santri::query()
            ->with(['kelas', 'asrama'])
            ->with([
                'tagihans' => function ($query) use ($request, $status) {
                    $this->applyTagihanTunggakanFilter($query, $request, $status);

                    $query->with(['jenisPembayaran', 'tahunAjaran'])
                        ->latest();
                }
            ])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', "%{$request->search}%")
                        ->orWhere('nis', 'like', "%{$request->search}%")
                        ->orWhere('nisn', 'like', "%{$request->search}%");
                });
            })
            ->when($request->kelas_id, function ($query) use ($request) {
                $query->where('kelas_id', $request->kelas_id);
            })
            ->when($request->asrama_id, function ($query) use ($request) {
                $query->where('asrama_id', $request->asrama_id);
            })
            ->whereHas('tagihans', function ($query) use ($request, $status) {
                $this->applyTagihanTunggakanFilter($query, $request, $status);
            })
            ->orderBy('nama')
            ->get()
            ->map(function ($santri) {
                $santri->total_tagihan = $santri->tagihans->sum('nominal');
                $santri->total_dibayar = $santri->tagihans->sum('dibayar');
                $santri->total_sisa = max($santri->total_tagihan - $santri->total_dibayar, 0);
                $santri->jumlah_tagihan = $santri->tagihans->count();

                return $santri;
            });

        $totalTagihan = $santris->sum('total_tagihan');
        $totalDibayar = $santris->sum('total_dibayar');
        $totalSisa = $santris->sum('total_sisa');
        $jumlahTagihan = $santris->sum('jumlah_tagihan');
        $jumlahSantri = $santris->count();

        return view('admin.laporan.print-tunggakan', compact(
            'santris',
            'status',
            'totalTagihan',
            'totalDibayar',
            'totalSisa',
            'jumlahTagihan',
            'jumlahSantri'
        ));
    }

    private function applyTagihanTunggakanFilter($query, Request $request, string $status): void
    {
        if ($status === 'menunggak') {
            $query->whereIn('status', ['belum_lunas', 'sebagian']);
        } elseif ($status !== 'semua') {
            $query->where('status', $status);
        }

        $query->when($request->tahun_ajaran_id, function ($q) use ($request) {
            $q->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        });

        $query->when($request->bulan, function ($q) use ($request) {
            $q->where('bulan', $request->bulan);
        });

        $query->when($request->tahun, function ($q) use ($request) {
            $q->where('tahun', $request->tahun);
        });
    }

    private function getRingkasan(string $tanggalMulai, string $tanggalSelesai): array
    {
        $totalPemasukan = KasTransaction::query()
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->where('tipe', 'pemasukan')
            ->sum('nominal');

        $totalPengeluaran = KasTransaction::query()
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->where('tipe', 'pengeluaran')
            ->sum('nominal');

        $saldoPeriode = $totalPemasukan - $totalPengeluaran;

        $totalPembayaran = Pembayaran::query()
            ->whereBetween('tanggal_bayar', [$tanggalMulai, $tanggalSelesai])
            ->sum('jumlah_bayar');

        $jumlahPembayaran = Pembayaran::query()
            ->whereBetween('tanggal_bayar', [$tanggalMulai, $tanggalSelesai])
            ->count();

        $totalTagihan = Tagihan::query()
            ->whereDate('created_at', '>=', $tanggalMulai)
            ->whereDate('created_at', '<=', $tanggalSelesai)
            ->sum('nominal');

        $totalDibayarTagihan = Tagihan::query()
            ->whereDate('created_at', '>=', $tanggalMulai)
            ->whereDate('created_at', '<=', $tanggalSelesai)
            ->sum('dibayar');

        $sisaTagihan = max($totalTagihan - $totalDibayarTagihan, 0);

        $tagihanLunas = Tagihan::query()
            ->whereDate('created_at', '>=', $tanggalMulai)
            ->whereDate('created_at', '<=', $tanggalSelesai)
            ->where('status', 'lunas')
            ->count();

        $tagihanBelumLunas = Tagihan::query()
            ->whereDate('created_at', '>=', $tanggalMulai)
            ->whereDate('created_at', '<=', $tanggalSelesai)
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->count();

        return compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoPeriode',
            'totalPembayaran',
            'jumlahPembayaran',
            'totalTagihan',
            'totalDibayarTagihan',
            'sisaTagihan',
            'tagihanLunas',
            'tagihanBelumLunas'
        );
    }


    public function kartuSantri(Request $request)
{
    $kelas = Kelas::where('status', 'aktif')->orderBy('nama_kelas')->get();
    $asramas = Asrama::where('status', 'aktif')->orderBy('nama_asrama')->get();
    $tahunAjarans = TahunAjaran::where('status', 'aktif')->latest()->get();

    $santris = Santri::query()
        ->with(['kelas', 'asrama'])
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nis', 'like', "%{$request->search}%")
                    ->orWhere('nisn', 'like', "%{$request->search}%");
            });
        })
        ->when($request->kelas_id, function ($query) use ($request) {
            $query->where('kelas_id', $request->kelas_id);
        })
        ->when($request->asrama_id, function ($query) use ($request) {
            $query->where('asrama_id', $request->asrama_id);
        })
        ->orderBy('nama')
        ->limit(50)
        ->get();

    $selectedSantri = null;
    $tagihans = collect();
    $pembayarans = collect();

    $totalTagihan = 0;
    $totalDibayar = 0;
    $sisaTagihan = 0;
    $jumlahTagihan = 0;
    $jumlahPembayaran = 0;

    if ($request->santri_id) {
        $selectedSantri = Santri::query()
            ->with(['kelas', 'asrama', 'user'])
            ->findOrFail($request->santri_id);

        $tagihans = Tagihan::query()
            ->with(['jenisPembayaran', 'tahunAjaran', 'pembayarans'])
            ->where('santri_id', $selectedSantri->id)
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->tahun_ajaran_id, function ($query) use ($request) {
                $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
            })
            ->when($request->bulan, function ($query) use ($request) {
                $query->where('bulan', $request->bulan);
            })
            ->when($request->tahun, function ($query) use ($request) {
                $query->where('tahun', $request->tahun);
            })
            ->latest()
            ->get();

        $pembayarans = Pembayaran::query()
            ->with(['tagihan.jenisPembayaran', 'user'])
            ->where('santri_id', $selectedSantri->id)
            ->latest('tanggal_bayar')
            ->latest()
            ->get();

        $totalTagihan = $tagihans->sum('nominal');
        $totalDibayar = $tagihans->sum('dibayar');
        $sisaTagihan = max($totalTagihan - $totalDibayar, 0);
        $jumlahTagihan = $tagihans->count();
        $jumlahPembayaran = $pembayarans->count();
    }

    return view('admin.laporan.kartu-santri', compact(
        'kelas',
        'asramas',
        'tahunAjarans',
        'santris',
        'selectedSantri',
        'tagihans',
        'pembayarans',
        'totalTagihan',
        'totalDibayar',
        'sisaTagihan',
        'jumlahTagihan',
        'jumlahPembayaran'
    ));
}

public function printKartuSantri(Request $request, Santri $santri)
{
    $santri->load(['kelas', 'asrama', 'user']);

    $tagihans = Tagihan::query()
        ->with(['jenisPembayaran', 'tahunAjaran', 'pembayarans'])
        ->where('santri_id', $santri->id)
        ->when($request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when($request->tahun_ajaran_id, function ($query) use ($request) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        })
        ->when($request->bulan, function ($query) use ($request) {
            $query->where('bulan', $request->bulan);
        })
        ->when($request->tahun, function ($query) use ($request) {
            $query->where('tahun', $request->tahun);
        })
        ->latest()
        ->get();

    $pembayarans = Pembayaran::query()
        ->with(['tagihan.jenisPembayaran', 'user'])
        ->where('santri_id', $santri->id)
        ->latest('tanggal_bayar')
        ->latest()
        ->get();

    $totalTagihan = $tagihans->sum('nominal');
    $totalDibayar = $tagihans->sum('dibayar');
    $sisaTagihan = max($totalTagihan - $totalDibayar, 0);
    $jumlahTagihan = $tagihans->count();
    $jumlahPembayaran = $pembayarans->count();

    return view('admin.laporan.print-kartu-santri', compact(
        'santri',
        'tagihans',
        'pembayarans',
        'totalTagihan',
        'totalDibayar',
        'sisaTagihan',
        'jumlahTagihan',
        'jumlahPembayaran'
    ));
}
}
