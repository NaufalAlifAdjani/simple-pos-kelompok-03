@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
<h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

@if (session('success'))
<div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md">
    @csrf
    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV</span>
        <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
        @error('csv')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </label>
    <p class="text-xs text-slate-500 mb-3">Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.</p>
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
</form>

@if(isset($importErrors) && $importErrors->count() > 0)
<div class="bg-white border rounded-md p-6 mt-6">
    <h2 class="text-md font-semibold text-red-600 mb-3">Log Baris Gagal Import (import_errors)</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b">
                    <th class="p-3 font-medium text-slate-700">No. Baris CSV</th>
                    <th class="p-3 font-medium text-slate-700">Nama Produk</th>
                    <th class="p-3 font-medium text-slate-700 text-red-600">Alasan Gagal Validasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($importErrors as $error)
                <tr class="border-b hover:bg-slate-50">
                    <td class="p-3 font-mono text-slate-600">{{ $error->row_number }}</td>
                    <td class="p-3 text-slate-800">{{ $error->product_name }}</td>
                    <td class="p-3 text-red-600 bg-red-50/50">{{ $error->error_message }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
