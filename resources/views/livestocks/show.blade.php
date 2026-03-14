@extends('layouts.app')

@section('title', 'Detail Ternak')
@section('header-title', 'Detail Ternak')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-start mb-6">
        <h2 class="text-xl font-bold text-gray-800" id="ear-tag">-</h2>
        <span id="status-badge" class="px-3 py-1 rounded-full text-sm font-medium"></span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <dl class="space-y-3">
                <div class="flex"><dt class="w-1/3 text-gray-600">Jenis</dt><dd class="w-2/3 font-medium" id="breed">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Kelamin</dt><dd class="w-2/3 font-medium" id="gender">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Tanggal Lahir</dt><dd class="w-2/3 font-medium" id="birth">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Umur</dt><dd class="w-2/3 font-medium" id="age">- hari</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Berat Awal</dt><dd class="w-2/3 font-medium" id="initial-weight">- kg</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Berat Terkini</dt><dd class="w-2/3 font-medium" id="current-weight">- kg</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Kesehatan</dt><dd class="w-2/3 font-medium" id="health">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Kandang</dt><dd class="w-2/3 font-medium" id="pen">-</dd></div>
                <div class="flex"><dt class="w-1/3 text-gray-600">Catatan</dt><dd class="w-2/3 text-gray-700" id="notes">-</dd></div>
            </dl>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Riwayat Berat</h3>
            <div id="weight-history" class="space-y-2 max-h-80 overflow-y-auto pr-2">
                <div class="text-center text-gray-500">Memuat...</div>
            </div>
        </div>
    </div>
    <div class="mt-6">
        <h3 class="font-semibold mb-2">Grafik Pertumbuhan</h3>
        <canvas id="growthChart" height="200"></canvas>
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('livestocks.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">Kembali</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const id = '{{ $livestockId ?? $livestock["id"] ?? 0 }}';
    try {
        const res = await TernakPark.api.fetchData(`/web-api/livestocks/${id}/detail`);
        if (res.success) {
            renderDetail(res.data);
            renderWeightHistory(res.data.weight_records);
            initChart(res.data.weight_records);
        } else {
            TernakPark.ui.showToast('Gagal memuat detail', 'error');
        }
    } catch (e) {
        console.error(e);
        TernakPark.ui.showToast('Koneksi error', 'error');
    }
});

function renderDetail(l) {
    document.getElementById('ear-tag').innerText = l.ear_tag;
    document.getElementById('breed').innerText = l.breed_type;
    document.getElementById('gender').innerText = l.gender === 'male' ? 'Jantan' : 'Betina';
    document.getElementById('birth').innerText = new Date(l.birth_date).toLocaleDateString('id-ID');
    document.getElementById('age').innerText = l.age_days;
    document.getElementById('initial-weight').innerText = l.initial_weight + ' kg';
    document.getElementById('current-weight').innerText = l.current_weight + ' kg';
    document.getElementById('health').innerText = l.health_status;
    document.getElementById('pen').innerText = l.pen?.name || '-';
    document.getElementById('notes').innerText = l.notes || '-';
    const badge = document.getElementById('status-badge');
    if (l.status) {
        badge.innerText = 'Aktif';
        badge.className = 'px-3 py-1 rounded-full bg-green-100 text-green-800';
    } else {
        badge.innerText = 'Tidak Aktif';
        badge.className = 'px-3 py-1 rounded-full bg-red-100 text-red-800';
    }
}

function renderWeightHistory(records) {
    const container = document.getElementById('weight-history');
    if (!records || records.length === 0) {
        container.innerHTML = '<div class="text-gray-500">Belum ada catatan berat</div>';
        return;
    }
    container.innerHTML = records.map(r => `
        <div class="flex justify-between items-center p-2 border rounded">
            <span>${new Date(r.record_date).toLocaleDateString('id-ID')}</span>
            <span class="font-semibold">${r.weight_kg} kg</span>
        </div>
    `).join('');
}

function initChart(records) {
    if (!records || records.length === 0) return;
    const sorted = records.sort((a,b) => new Date(a.record_date) - new Date(b.record_date));
    const labels = sorted.map(r => new Date(r.record_date).toLocaleDateString('id-ID'));
    const data = sorted.map(r => r.weight_kg);
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: { labels, datasets: [{ label: 'Berat (kg)', data, borderColor: '#10b981' }] }
    });
}
</script>
@endpush
