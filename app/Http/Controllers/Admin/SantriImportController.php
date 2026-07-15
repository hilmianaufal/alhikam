<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SantriImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\SantriImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class SantriImportController extends Controller
{
    public function create()
    {
        return view('admin.santri.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new SantriImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }

        if ($import->failures()->isNotEmpty()) {
            return back()
                ->with('import_failures', $import->failures())
                ->with('error', 'Beberapa baris gagal diimport. Silakan cek detail error.');
        }

        return redirect()
            ->route('admin.santri.index')
            ->with('success', "Import berhasil. Data baru: {$import->imported}, data diperbarui: {$import->updated}.");
    }

    public function template()
    {
        return Excel::download(
            new SantriImportTemplateExport(),
            'template-import-santri.xlsx'
        );
    }
}
