<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);
        $query = $this->filteredQuery($from, $to);
        return view('reports.index', [
            'totalPenjualan' => $query->sum('total'),
            'jumlahTransaksi' => $query->count(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }
    public function exportCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $query = $this->filteredQuery($from, $to);
        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Tanggal', 'Kasir', 'Total']);
            foreach ($query->lazyById() as $t) {
                fputcsv($out, [$t->id, $t->created_at, $t->user->name, $t->total]);
            }
            fclose($out);
        }, 'laporan-penjualan.csv');
    }
    public function exportExcel(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        return Excel::download(
            new SalesReportExport($this->filteredQuery($from, $to)),
            'laporan-penjualan.xlsx',
        );
    }
    private function resolveDateRange(Request $request): array
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();
        return [$from, $to];
    }
    private function filteredQuery($from, $to): Builder
    {
        return Transaction::with('user')
            ->whereBetween('created_at', [$from, $to]);
    }

    public function importForm(): View
{
    return view('reports.import');
}

public function import(Request $request): RedirectResponse
{
    $request->validate([
        'csv' => ['required', 'file', 'mimes:csv,txt'],
    ]);

    // Membaca file CSV menjadi array baris demi baris
    $lines = file($request->file('csv')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $rows = array_map('str_getcsv', $lines);
    $header = array_shift($rows);

    $created = 0;
    $updated = 0;

    foreach ($rows as $row) {
        if (count($row) !== count($header)) {
            continue; // Lewati row jika jumlah kolom tidak cocok
        }

        $data = array_combine($header, $row);

        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            continue; // Lewati row jika gagal validasi
        }

        $validatedData = $validator->validated();

        // Sifat Idempotent: jika nama & kategori sudah ada, cukup update harganya
        $product = Product::updateOrCreate(
            ['name' => $validatedData['name'], 'category_id' => $validatedData['category_id']],
            ['price' => $validatedData['price']],
        );

        $product->wasRecentlyCreated ? $created++ : $updated++;
    }

    return back()->with('success', "{$created} produk baru, {$updated} diperbarui.");
}

}
