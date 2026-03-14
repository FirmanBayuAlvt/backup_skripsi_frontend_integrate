@extends('layouts.app')

@section('title', 'Analisis Korelasi')
@section('header-title', 'Analisis Korelasi Pakan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Matriks Korelasi</h2>
    <div id="correlation-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="text-center col-span-2">Memuat...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadCorrelation);

async function loadCorrelation() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/predictions/correlation');
        if (res.success) render(res.data);
    } catch (e) { TernakPark.ui.showToast('Gagal', 'error'); }
}

function render(data) {
    const factors = data.factors || {};
    const grid = document.getElementById('correlation-grid');
    grid.innerHTML = Object.entries(factors).map(([k, v]) => `
        <div class="flex justify-between p-4 border rounded">
            <span class="font-medium">${k}</span>
            <span class="text-lg font-bold ${v > 0.5 ? 'text-green-600' : 'text-yellow-600'}">${v.toFixed(2)}</span>
        </div>
    `).join('');
}
</script>
@endpush
