@extends('layouts.app')

@section('title', 'Stok Pakan')
@section('header-title', 'Stok Pakan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Tingkat Stok Pakan</h2>
    <div id="stock-list" class="space-y-4">
        <div class="text-center">Memuat...</div>
    </div>
    <div class="mt-6">
        <h3 class="font-semibold">Ringkasan</h3>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div class="p-3 bg-gray-50 rounded">
                <span class="text-sm text-gray-500">Total Nilai Stok</span>
                <p class="text-xl font-bold" id="total-value">-</p>
            </div>
            <div class="p-3 bg-gray-50 rounded">
                <span class="text-sm text-gray-500">Stok Rendah</span>
                <p class="text-xl font-bold" id="low-count">-</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadStock);

async function loadStock() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/feeds/stock-levels');
        if (res.success) render(res.data);
    } catch (e) { TernakPark.ui.showToast('Gagal', 'error'); }
}

function render(data) {
    const container = document.getElementById('stock-list');
    container.innerHTML = data.feed_types.map(f => `
        <div class="flex items-center justify-between p-3 border rounded">
            <div>
                <span class="font-medium">${f.name}</span>
                <span class="ml-2 text-sm text-gray-500">${f.category}</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="${f.current_stock < 100 ? 'text-red-600 font-bold' : ''}">${f.current_stock} kg</span>
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: ${Math.min(f.current_stock / 500 * 100, 100)}%"></div>
                </div>
            </div>
        </div>
    `).join('');
    document.getElementById('total-value').innerText = TernakPark.format.currency(data.stock_summary.total_value);
    document.getElementById('low-count').innerText = data.stock_summary.low_stock_count;
}
</script>
@endpush
