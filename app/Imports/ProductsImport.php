<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row): Model|array|null
    {
        if (empty($row['name']) || empty($row['category_id']) || !isset($row['price'])) {
            return null;
        }

        return Product::updateOrCreate(
            [
                'name'        => trim($row['name']),
                'category_id' => (int) $row['category_id'],
            ],
            [
                'price'       => (int) $row['price'],
            ]
        );
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}