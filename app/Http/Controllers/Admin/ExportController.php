<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KasTransactionExport;
use App\Exports\PembayaranExport;
use App\Exports\SantriExport;
use App\Exports\TagihanExport;
use App\Exports\TunggakanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function santri(Request $request)
    {
        return Excel::download(
            new SantriExport($request->all()),
            'data-santri-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function tagihan(Request $request)
    {
        return Excel::download(
            new TagihanExport($request->all()),
            'data-tagihan-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function pembayaran(Request $request)
    {
        return Excel::download(
            new PembayaranExport($request->all()),
            'data-pembayaran-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function kas(Request $request)
    {
        return Excel::download(
            new KasTransactionExport($request->all()),
            'data-kas-pondok-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function tunggakan(Request $request)
    {
        return Excel::download(
            new TunggakanExport($request->all()),
            'laporan-tunggakan-' . now()->format('Ymd-His') . '.xlsx'
        );
    }
}
