@extends('layouts.app')

@section('title', 'Detail Kandang')
@section('header-title', 'Detail Kandang')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-start mb-6">
        <h2 class="text-xl font-bold text-gray-800" id="pen-name">-</h2>
        <span id="status-badge" class="px-3 py-1 rounded-full text-sm font-medium"></span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <dl class="space-y-3">
                <div class="flex"><dt class="w-1/3 text-gray-600">Kode</dt><dd class="w-2/3 font-medium" id="code">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Kategori</dt><dd class="w-2/3 font-medium" id="category">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Kapasitas</dt><dd class="w-2/3 font-medium" id="capacity">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Jumlah Ternak</dt><dd class="w-2/3 font-medium" id="occupancy">-</dd></div>
            </dl>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Daftar Ternak di Kandang Ini</h3>
            <div id="livestock-list" class="space-y-2 max-h-80 overflow-y-auto pr-2">
                <div class="text-center text-gray-500">Memuat...</div>
            </div>
        </div>
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('pens.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">Kembali</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const id = '{{ $penId ?? $pen["id"] ?? 0 }}';
    try {
        const res = await TernakPark.api.fetchData(`/web-api/pens/${id}/detail`);
        if (res.success) renderDetail(res.data);
    } catch (e) { console.error(e); }
});

function renderDetail(data) {
    document.getElementById('pen-name').innerText = data.name;
    document.getElementById('code').innerText = data.code || '-';
    document.getElementById('category').innerText = data.category;
    document.getElementById('capacity').innerText = data.capacity;
    document.getElementById('occupancy').innerText = data.current_occupancy + ' / ' + data.capacity;
    const badge = document.getElementById('status-badge');
    if (data.status === 'active') {
        badge.innerText = 'Aktif';
        badge.className = 'px-3 py-1 rounded-full bg-green-100 text-green-800';
    } else {
        badge.innerText = 'Nonaktif';
        badge.className = 'px-3 py-1 rounded-full bg-red-100 text-red-800';
    }
    const list = document.getElementById('livestock-list');
    if (data.livestocks && data.livestocks.length) {
        list.innerHTML = data.livestocks.map(l => `
            <div class="flex justify-between items-center p-2 border rounded">
                <span>${l.ear_tag}</span>
                <span class="text-sm">${l.current_weight} kg</span>
            </div>
        `).join('');
    } else {
        list.innerHTML = '<div class="text-gray-500">Tidak ada ternak</div>';
    }
}
</script>
@endpush
