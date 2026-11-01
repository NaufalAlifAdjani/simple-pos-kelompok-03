<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];
    public int $timeout = 60;

    private string $csvPath;
    private string $method;

    public function __construct(string $csvPath, string $method = 'excel')
    {
        $this->csvPath = $csvPath;
        $this->method  = $method;
    }

    public function handle(): void
    {
        $filePath = Storage::path($this->csvPath);

        if (!file_exists($filePath)) {
            throw new \Exception("File CSV tidak ditemukan pada path: " . $this->csvPath);
        }

        // JIKA MENGGUNAKAN LARAVEL EXCEL
        if ($this->method === 'excel') {
            Excel::import(new ProductsImport, $filePath);
            Storage::delete($this->csvPath);
            return;
        }

        // JIKA MENGGUNAKAN VERSI MANUAL (DENGAN LOG IMPORT_ERRORS)
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        ImportError::truncate();

        $rowNumber = 1;

        foreach ($rows as $row) {
            $rowNumber++;

            if (count($row) !== count($header)) {
                ImportError::create([
                    'row_number'    => $rowNumber,
                    'product_name'  => 'Format Kolom Cacat',
                    'error_message' => 'Jumlah kolom data tidak cocok dengan baris header CSV.'
                ]);
                continue;
            }

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'name'        => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price'       => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                ImportError::create([
                    'row_number'    => $rowNumber,
                    'product_name'  => $data['name'] ?? 'Tidak Diketahui',
                    'error_message' => implode(', ', $validator->errors()->all())
                ]);
                continue;
            }

            $validatedData = $validator->validated();

            Product::updateOrCreate(
                ['name' => $validatedData['name'], 'category_id' => $validatedData['category_id']],
                ['price' => $validatedData['price']],
            );
        }

        Storage::delete($this->csvPath);
    }
}