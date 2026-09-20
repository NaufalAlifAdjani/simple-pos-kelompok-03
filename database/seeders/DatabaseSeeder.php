<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User kasir
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Kategori (5 buah)
        $categoryNames = ['Minuman', 'Makanan', 'Snack', 'ATK', 'Elektronik'];
        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('categories')->insert($categories);
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        // 3. 300 Produk (bulk insert per batch 100)
        $products = [];
        for ($i = 0; $i < 300; $i++) {
            $products[] = [
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'name' => fake()->unique()->words(3, true),
                'price' => fake()->numberBetween(1000, 50000),
                'stock' => fake()->numberBetween(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($products, 100) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        $productRows = DB::table('products')->get(['id', 'price']);

        // 4. 2500 transaksi + detail (dibungkus DB::transaction)
        DB::transaction(function () use ($productRows) {
            $userId = DB::table('users')->value('id');
            $transactions = [];
            $details = [];

            for ($i = 0; $i < 2500; $i++) {
                $trxId = $i + 1;
                $total = 0;
                $itemCount = rand(1, 5);

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $productRows->random();
                    $qty = rand(1, 3);
                    $subtotal = $product->price * $qty;
                    $total += $subtotal;

                    $details[] = [
                        'transaction_id' => $trxId,
                        'product_id' => $product->id,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $transactions[] = [
                    'id' => $trxId,
                    'user_id' => $userId,
                    'total' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach (array_chunk($transactions, 500) as $chunk) {
                DB::table('transactions')->insert($chunk);
            }
            foreach (array_chunk($details, 500) as $chunk) {
                DB::table('transaction_details')->insert($chunk);
            }
        });
    }
}