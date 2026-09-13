@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

<div x-data="{
    cart: [],
    lastClickedId: null, // State untuk mencatat ID produk yang terakhir diklik
    addToCart(id, name, price) {
        this.cart.push({ id, name, price });
        this.lastClickedId = id; // Set ID produk yang baru diklik (Langkah 12 Poin 3)
    },
    subtotal() {
        return this.cart.reduce((sum, item) => sum + item.price, 0);
    }
}">
    <div class="grid grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="border rounded-md p-3 cursor-pointer transition-all duration-150"
             :class="lastClickedId === {{ $product->id }} ? 'ring-2 ring-blue-500 bg-blue-50 scale-[0.98]' : ''"
             @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
            <p class="font-medium">{{ $product->name }}</p>
            <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
        </div>
        @endforeach
    </div>

    <div class="mt-4 border-t pt-3">
        <template x-for="item in cart" :key="item.id">
            <p x-text="item.name + ' - Rp ' + item.price"></p>
        </template>
        <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>
    </div>
</div>
@endsection
