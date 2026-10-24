<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ImportError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 30];
    public int $timeout = 90;

    private string $csvPath;

    public function __construct(string $csvPath)
    {
        $this->csvPath = $csvPath;
    }

    public function handle(): void
    {
        $filePath = Storage::path($this->csvPath);

        if (!file_exists($filePath)) {
            throw new \Exception("File CSV tidak ditemukan pada path: " . $this->csvPath);
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        ImportError::truncate();

        $rowNumber = 1; 

        foreach ($rows as $row) {
            $rowNumber++;

            if (count($row) !== count($header)) {
                ImportError::create([
                    'row_number' => $rowNumber,
                    'product_name' => 'Format Kolom Cacat',
                    'error_message' => 'Jumlah kolom data tidak cocok dengan baris header CSV.'
                ]);
                continue;
            }

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'name' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                ImportError::create([
                    'row_number' => $rowNumber,
                    'product_name' => $data['name'] ?? 'Tidak Diketahui',
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
