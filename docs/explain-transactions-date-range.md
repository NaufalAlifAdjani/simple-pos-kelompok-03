# Query Listing Baru: Transaksi per Rentang Tanggal

## Query yang dijalankan

Query listing untuk mencari transaksi dalam rentang tanggal tertentu:

```sql
SELECT * FROM transactions
WHERE created_at BETWEEN '2026-01-01' AND '2026-12-31'
```

## Hasil EXPLAIN QUERY PLAN

```
EXPLAIN QUERY PLAN SELECT * FROM transactions WHERE created_at BETWEEN '2026-01-01' AND '2026-12-31'

detail: SCAN transactions
```

## Pembanding: query yang memakai index

```
EXPLAIN QUERY PLAN SELECT * FROM transactions WHERE user_id = 1

detail: SEARCH transactions USING INDEX transactions_user_id_index (user_id=?)
```

## Kesimpulan

Query rentang tanggal **belum memakai index**. Hasilnya `SCAN transactions`,
artinya SQLite memeriksa seluruh baris tabel `transactions` satu per satu
untuk mencari baris yang cocok, karena kolom `created_at` belum punya index.

Sebagai pembanding, query berdasarkan `user_id` sudah memakai index
`transactions_user_id_index` (dibuat pada Langkah 4), sehingga SQLite
langsung melompat ke baris yang relevan tanpa memindai seluruh tabel.

Index yang ada sekarang hanya membantu query yang memfilter kolom yang
diindeks. Jika query per rentang tanggal sering dipakai dan data terus
bertambah, menambahkan index pada `created_at` lewat migrasi baru bisa
menjadi perbaikan. Dengan 2.500 baris perbedaannya belum terasa, tetapi
pada data yang jauh lebih besar `SCAN` akan makin lambat.