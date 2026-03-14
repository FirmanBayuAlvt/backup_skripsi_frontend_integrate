@extends('layouts.app')

@section('title', 'Analisis Kandang')
@section('header-title', 'Analisis Kandang')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4" id="pen-name">Analisis Kandang</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Rata-rata Berat</p>
            <p class="text-2xl font-bold" id="avg-weight">- kg</p>
        </div>
        <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Total Berat</p>
            <p class="text-2xl font-bold" id="total-weight">- kg</p>
        </div>
        <div class="p-4 bg-gray-50 rounded">
            <p class="text-sm text-gray-500">Kebutuhan Pakan Harian</p>
            <p class="text-2xl font-bold" id="feed-daily">- kg</p>
        </div>
    </div>
    <div class="mt-6">
        <canvas id="penChart" height="250"></canvas>
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('pens.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">Kembali</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const id = '{{ $penId ?? $data["pen"]["id"] ?? 0 }}';
    try {
        const res = await TernakPark.api.fetchData(`/web-api/pens/${id}/analytics`);
        if (res.success) renderAnalytics(res.data);
    } catch (e) { console.error(e); }
});

function renderAnalytics(data) {
    document.getElementById('pen-name').innerText = 'Analisis ' + (data.pen?.name || 'Kandang');
    document.getElementById('avg-weight').innerText = (data.livestock_stats?.average_weight || 0) + ' kg';
    document.getElementById('total-weight').innerText = (data.livestock_stats?.total_weight || 0) + ' kg';
    document.getElementById('feed-daily').innerText = (data.feed_requirements?.daily_kg || 0) + ' kg';
    new Chart(document.getElementById('penChart'), {
        type: 'bar',
        data: {
            labels: ['Jantan', 'Betina'],
            datasets: [{
                label: 'Jumlah',
                data: [data.livestock_stats?.by_gender?.male || 0, data.livestock_stats?.by_gender?.female || 0],
                backgroundColor: ['#10b981', '#f59e0b']
            }]
        }
    });
}
</script>
@endpush
