<div>
    <nav class="bg-slate-900 text-white px-6 py-4 flex items-center gap-6 shadow-md">
        <span class="font-bold text-lg text-blue-400 mr-2">Simple POS</span>
        <a href="{{ route('pos.create') }}" 
           class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('pos.create') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
            Kasir
        </a>
        <a href="{{ route('transactions.index') }}" 
           class="px-3 py-1.5 rounded transition-all text-sm font-medium {{ Request::routeIs('transactions.index') ? 'bg-slate-800 text-blue-400 font-semibold' : 'text-slate-300 hover:text-white hover:underline' }}">
            Transaksi
        </a>
    </nav>
</div>
