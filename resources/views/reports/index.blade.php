@extends('layouts.app')

@section('title', 'Laporan')
@section('header-title', 'Laporan & Analisis')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition cursor-pointer" onclick="window.location='{{ route('reports.performance') }}'">
        <div class="text-4xl mb-2">📊</div>
        <h3 class="font-bold text-lg">Laporan Performa</h3>
        <p class="text-sm text-gray-600">Analisis pertumbuhan dan efisiensi</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition cursor-pointer" onclick="window.location='{{ route('reports.growth') }}'">
        <div class="text-4xl mb-2">📈</div>
        <h3 class="font-bold text-lg">Laporan Pertumbuhan</h3>
        <p class="text-sm text-gray-600">Tracking berat badan ternak</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition cursor-pointer" onclick="window.location='#'">
        <div class="text-4xl mb-2">💰</div>
        <h3 class="font-bold text-lg">Laporan Keuangan</h3>
        <p class="text-sm text-gray-600">Analisis biaya dan pendapatan (segera)</p>
    </div>
</div>

<!-- Ringkasan Cepat -->
<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-bold mb-4">Ringkasan Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-3 bg-gray-50 rounded text-center">
            <p class="text-sm text-gray-500">Total Ternak</p>
            <p class="text-2xl font-bold" id="total-livestock">-</p>
        </div>
        <div class="p-3 bg-gray-50 rounded text-center">
            <p class="text-sm text-gray-500">Total Kandang</p>
            <p class="text-2xl font-bold" id="total-pens">-</p>
        </div>
        <div class="p-3 bg-gray-50 rounded text-center">
            <p class="text-sm text-gray-500">Jenis Pakan</p>
            <p class="text-2xl font-bold" id="total-feeds">-</p>
        </div>
        <div class="p-3 bg-gray-50 rounded text-center">
            <p class="text-sm text-gray-500">Stok Pakan (kg)</p>
            <p class="text-2xl font-bold" id="total-feed-stock">-</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadSummary);

async function loadSummary() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/reports/data?type=summary');
        if (res.success) {
            document.getElementById('total-livestock').innerText = res.data.total_livestocks || 0;
            document.getElementById('total-pens').innerText = res.data.total_pens || 0;
            document.getElementById('total-feeds').innerText = res.data.total_feed_types || 0;
            document.getElementById('total-feed-stock').innerText = res.data.total_feed_stock_kg || 0;
        }
    } catch (e) { console.error(e); }
}
</script>
@endpush
