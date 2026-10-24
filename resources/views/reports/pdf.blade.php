<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Penjualan</title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
h1 { font-size: 18px; margin-bottom: 4px; }
h2 { font-size: 14px; margin-top: 20px; margin-bottom: 4px; }
table { width: 100%; border-collapse: collapse; margin-top: 12px; }
th, td { border: 1px solid #999; padding: 4px 8px; text-align: left; }
th { background: #eee; }
.right { text-align: right; }
</style>
</head>
<body>
<h1>Laporan Penjualan</h1>
<p>Periode {{ $from }} s.d. {{ $to }}</p>
<p>Total penjualan: <strong>Rp {{ number_format($totalPenjualan) }}</strong> dari {{ $jumlahTransaksi }} transaksi</p>
<p>50 transaksi terbaru pada periode ini:</p>
<table>
<thead>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Kasir</th>
<th class="right">Total</th>
</tr>
</thead>
<tbody>
@foreach ($transactions as $t)
<tr>
<td>{{ $t->id }}</td>
<td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
<td>{{ $t->user->name }}</td>
<td class="right">Rp {{ number_format($t->total) }}</td>
</tr>
@endforeach
</tbody>
</table>

<h2>5 Produk Terlaris:</h2>
<table>
<thead>
<tr>
<th style="width: 40px;">No</th>
<th>Nama Produk</th>
<th class="right">Total Kuantitas Terjual</th>
</tr>
</thead>
<tbody>
@forelse ($topProducts as $index => $product)
<tr>
<td>{{ $index + 1 }}</td>
<td>{{ $product->name }}</td>
<td class="right">{{ number_format($product->total_qty) }}</td>
</tr>
@empty
<tr>
<td colspan="3" style="text-align: center;">Tidak ada data produk terjual pada periode ini.</td>
</tr>
@endforelse
</tbody>
</table>
</body>
</html>
