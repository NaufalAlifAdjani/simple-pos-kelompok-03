@extends('layouts.app')

@section('title', 'Kasir')

@section('content')

    <h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

    <div x-data="{
        cart: [],

        addToCart(id, name, price) {
            this.cart.push({ id, name, price });
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        subtotal() {
            return this.cart.reduce((sum, item) => sum + item.price, 0);
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

            <template x-for="(item, index) in cart" :key="index">
                <div class="flex items-center justify-between gap-3 py-1">

                    <p x-text="item.name + ' - ' + formatRp(item.price)"></p>

                    <button type="button" class="text-sm text-red-600 hover:text-red-800" @click="removeFromCart(index)">
                        Hapus
                    </button>

                </div>
            </template>

            <p class="font-semibold mt-2">
                Subtotal: <span x-text="formatRp(subtotal())"></span>
            </p>

        </div>

    </div>
@endsection