# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | 40.4 |
| `Transaction::get()` | 98.8 |

Kesimpulan kelompok: 
Metode `lazyById()` menggunakan memori lebih sedikit dibandingkan
`get()` karena data dibaca secara bertahap. Sementara itu, `get()`
mengambil seluruh data sekaligus sehingga penggunaan memorinya lebih
besar. Oleh karena itu, `lazyById()` lebih sesuai untuk membaca data
dalam jumlah besar.
