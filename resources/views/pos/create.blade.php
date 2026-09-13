@extends('layouts.app')

@section('title', 'Kasir')

@section('content')

<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

<div x-data="{
    cart: [],

    addToCart(id, name, price) {
        const existing = this.cart.find(item => item.id === id);
        if (existing) {
            existing.qty++;
        } else {
            this.cart.push({ id, name, price, qty: 1 });
        }
    },

    increaseQty(id) {
        const item = this.cart.find(item => item.id === id);
        if (item) item.qty++;
    },

    decreaseQty(id) {
        const item = this.cart.find(item => item.id === id);
        if (!item) return;
        if (item.qty > 1) {
            item.qty--;
        } else {
            this.removeFromCart(id);
        }
    },

    removeFromCart(id) {
        this.cart = this.cart.filter(item => item.id !== id);
    },

    subtotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    },

    formatRp(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
}">

    <div class="grid grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="border rounded-md p-3 cursor-pointer hover:bg-slate-50"
            @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">

            <p class="font-medium">{{ $product->name }}</p>
            <p class="text-sm text-slate-500">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
        </div>
        @endforeach
    </div>

    <div class="mt-4 border-t pt-3">

        <template x-if="cart.length === 0">
            <p class="text-sm text-slate-400">Belum ada item di keranjang.</p>
        </template>

        <template x-for="item in cart" :key="item.id">
            <div class="flex items-center justify-between gap-3 py-2 border-b last:border-b-0">

                <div>
                    <p class="font-medium" x-text="item.name"></p>
                    <p class="text-sm text-slate-500" x-text="formatRp(item.price) + ' x ' + item.qty"></p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                        class="w-7 h-7 border rounded text-sm hover:bg-slate-100"
                        @click="decreaseQty(item.id)">-</button>

                    <span x-text="item.qty" class="w-6 text-center"></span>

                    <button type="button"
                        class="w-7 h-7 border rounded text-sm hover:bg-slate-100"
                        @click="increaseQty(item.id)">+</button>

                    <button
                        type="button"
                        class="text-sm text-red-600 hover:text-red-800 ml-3"
                        @click="removeFromCart(item.id)">
                        Hapus
                    </button>
                </div>

            </div>
        </template>

        <p class="font-semibold mt-3 text-lg">
            Subtotal: <span x-text="formatRp(subtotal())"></span>
        </p>

    </div>

</div>
@endsection