@extends('layouts.app')

@section('title', 'Laporan Pertumbuhan')
@section('header-title', 'Laporan Pertumbuhan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <canvas id="growthChart" height="300"></canvas>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: ['Minggu 1','Minggu 2','Minggu 3','Minggu 4'],
            datasets: [{
                label: 'Rata-rata Berat',
                data: [42,45,48,52],
                borderColor: '#10b981'
            }]
        }
    });
});
</script>
@endpush
