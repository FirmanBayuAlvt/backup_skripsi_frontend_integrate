@extends('layouts.app')

@section('title', 'Laporan Performa')
@section('header-title', 'Laporan Performa')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <canvas id="performanceChart" height="300"></canvas>
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 border rounded text-center">
            <p class="text-sm text-gray-500">Rata-rata ADG</p>
            <p class="text-2xl font-bold" id="avg-adg">- kg</p>
        </div>
        <div class="p-4 border rounded text-center">
            <p class="text-sm text-gray-500">FCR</p>
            <p class="text-2xl font-bold" id="fcr">-</p>
        </div>
        <div class="p-4 border rounded text-center">
            <p class="text-sm text-gray-500">Mortalitas</p>
            <p class="text-2xl font-bold" id="mortality">-%</p>
        </div>
        <div class="p-4 border rounded text-center">
            <p class="text-sm text-gray-500">Okupansi</p>
            <p class="text-2xl font-bold" id="occupancy">-%</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadPerformance);

async function loadPerformance() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/reports/data?type=performance');
        if (res.success) {
            document.getElementById('avg-adg').innerText = (res.data.average_daily_gain || 0) + ' kg';
            document.getElementById('fcr').innerText = res.data.feed_conversion_ratio || 0;
            document.getElementById('mortality').innerText = (res.data.mortality_rate || 0) + '%';
            document.getElementById('occupancy').innerText = (res.data.occupancy_rate || 0) + '%';
            new Chart(document.getElementById('performanceChart'), {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr'],
                    datasets: [{
                        label: 'ADG',
                        data: [0.15,0.16,0.18,0.17],
                        borderColor: '#10b981'
                    }]
                }
            });
        }
    } catch (e) { console.error(e); }
}
</script>
@endpush
