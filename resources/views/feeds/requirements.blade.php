@extends('layouts.app')

@section('title', 'Kebutuhan Pakan')
@section('header-title', 'Kebutuhan Pakan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Kebutuhan Pakan Harian</h2>
    <div id="requirements" class="space-y-6">
        <div class="text-center">Memuat...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadRequirements);

async function loadRequirements() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/feeds/requirements');
        if (res.success) render(res.data);
    } catch (e) { TernakPark.ui.showToast('Gagal', 'error'); }
}

function render(data) {
    const req = data.requirements;
    const html = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded">
                <p class="text-sm text-gray-600">Harian</p>
                <p class="text-2xl font-bold">${req.daily.total_kg} kg</p>
                <p class="text-sm">Biaya: ${TernakPark.format.currency(req.daily.cost)}</p>
            </div>
            <div class="p-4 bg-green-50 rounded">
                <p class="text-sm text-gray-600">Mingguan</p>
                <p class="text-2xl font-bold">${req.weekly.total_kg} kg</p>
                <p class="text-sm">Biaya: ${TernakPark.format.currency(req.weekly.cost)}</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded">
                <p class="text-sm text-gray-600">Bulanan</p>
                <p class="text-2xl font-bold">${req.monthly.total_kg} kg</p>
                <p class="text-sm">Biaya: ${TernakPark.format.currency(req.monthly.cost)}</p>
            </div>
        </div>
        <h3 class="font-semibold mt-6">Komposisi Harian</h3>
        <div class="grid grid-cols-3 gap-4 mt-2">
            <div class="p-3 border rounded text-center">
                <p class="font-medium">Silase</p>
                <p>${req.daily.composition.silase} kg</p>
            </div>
            <div class="p-3 border rounded text-center">
                <p class="font-medium">CF Jember</p>
                <p>${req.daily.composition.cf_jember} kg</p>
            </div>
            <div class="p-3 border rounded text-center">
                <p class="font-medium">Jagung Halus</p>
                <p>${req.daily.composition.jagung_halus} kg</p>
            </div>
        </div>
    `;
    document.getElementById('requirements').innerHTML = html;
}
</script>
@endpush
