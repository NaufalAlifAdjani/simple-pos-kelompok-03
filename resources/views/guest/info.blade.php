<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi Publik - Simple POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 px-4 py-8">
    <div class="w-full max-w-md bg-white rounded-md shadow p-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
            <h1 class="text-lg font-semibold text-slate-900">Informasi Simple POS</h1>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                Online
            </span>
        </div>

        <p class="text-sm text-slate-600 mb-4 leading-relaxed">
            Simple POS adalah aplikasi kasir terpadu yang dirancang untuk mempercepat transaksi penjualan, mengelola stok barang, serta memantau ringkasan pendapatan toko secara akurat.
        </p>

        <div class="space-y-3 mb-6">
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-md">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Transaksi Kasir Cepat</h2>
                <p class="text-xs text-slate-500">
                    Mendukung pemilihan produk interaktif, penyesuaian kuantitas, dan kalkulasi kembalian otomatis.
                </p>
            </div>

            <div class="p-3 bg-slate-50 border border-slate-200 rounded-md">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Katalog Produk & Kategori</h2>
                <p class="text-xs text-slate-500">
                    Pengelolaan produk terstruktur dengan penetapan harga, pelacakan stok, dan pengelompokan kategori.
                </p>
            </div>

            <div class="p-3 bg-slate-50 border border-slate-200 rounded-md">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Riwayat & Struk Transaksi</h2>
                <p class="text-xs text-slate-500">
                    Setiap transaksi tersimpan dengan nomor faktur unik, rincian pembayaran, dan identitas kasir yang bertugas.
                </p>
            </div>
        </div>

        <a href="{{ route('login') }}" 
           class="block w-full text-center bg-slate-900 hover:bg-slate-800 text-white rounded-md py-2 text-sm font-medium transition mb-4">
            Masuk ke Aplikasi
        </a>

        <p class="text-xs text-slate-500 text-center">
            Halaman ini khusus untuk tamu (belum login). Pengguna yang telah login akan langsung dialihkan ke menu POS.
        </p>
    </div>
</body>
</html>
