<div>
    <nav class="bg-slate-900 text-white px-6 py-4 flex items-center gap-6 shadow-md">
        <span class="font-bold text-lg text-blue-400 mr-2">Simple POS</span>

        {{-- Menu untuk semua user yang sudah login --}}
        <a href="{{ route('pos.create') }}"
            class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('pos.create') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
            Kasir
        </a>
        <a href="{{ route('transactions.index') }}"
            class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('transactions.index') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
            Transaksi
        </a>

        {{-- Menu khusus admin --}}
        @if (auth()->user()?->isAdmin())
            <a href="{{ route('products.index') }}"
                class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('products.index') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
                Produk
            </a>
            <a href="{{ route('categories.index') }}"
                class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('categories.*') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
                Kategori
            </a>
            <a href="{{ route('reports.index') }}" class="hover:underline">Laporan</a>
        @endif

        {{-- Info user + tombol keluar --}}
        <span class="ml-auto text-sm text-slate-300">
            {{ auth()->user()?->name }} ({{ auth()->user()?->role }})
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="px-3 py-1.5 rounded text-sm font-medium text-slate-300 hover:text-white hover:underline">
                Keluar
            </button>
        </form>
    </nav>
</div>