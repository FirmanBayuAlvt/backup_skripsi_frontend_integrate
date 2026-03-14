@extends('layouts.app')

@section('title', 'Prediksi & AI')
@section('header-title', 'Prediksi Pertumbuhan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Prediksi -->
    <div class="lg:col-span-1 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold mb-4">Jalankan Prediksi</h3>
        <form id="prediction-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Pilih Ternak</label>
                <select id="prediction-livestock" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Memuat...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Periode (hari)</label>
                <input type="number" id="prediction-days" value="30" min="1" max="90" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="button" onclick="runPrediction()" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-play mr-2"></i>Jalankan
            </button>
        </form>
    </div>

    <!-- Hasil Prediksi -->
    <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold mb-4">Hasil Prediksi</h3>
        <div id="prediction-results" class="text-center text-gray-500 py-12">
            <i class="fas fa-brain text-5xl text-gray-300 mb-3"></i>
            <p>Pilih ternak dan jalankan prediksi</p>
        </div>
    </div>
</div>

<!-- Analisis Korelasi -->
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold">Analisis Korelasi Pakan</h3>
        <button onclick="loadCorrelation()" class="text-green-600 hover:text-green-800">Refresh</button>
    </div>
    <div id="correlation-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="text-center col-span-2">Memuat...</div>
    </div>
</div>

<!-- Riwayat Prediksi -->
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-bold mb-4">Riwayat Prediksi</h3>
    <div id="history-container" class="space-y-3">
        <div class="text-center">Memuat...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadLivestocks();
    loadCorrelation();
    loadHistory();
});

async function loadLivestocks() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/livestocks/data?per_page=100');
        const select = document.getElementById('prediction-livestock');
        if (res.success && res.data.livestocks.length) {
            select.innerHTML = '<option value="">Pilih Ternak</option>' +
                res.data.livestocks.map(l => `<option value="${l.id}">${l.ear_tag}</option>`).join('');
        } else {
            select.innerHTML = '<option value="">Tidak ada ternak</option>';
        }
    } catch (e) { console.error(e); }
}

async function runPrediction() {
    const id = document.getElementById('prediction-livestock').value;
    const days = document.getElementById('prediction-days').value;
    if (!id) { TernakPark.ui.showToast('Pilih ternak', 'error'); return; }

    const resultsDiv = document.getElementById('prediction-results');
    resultsDiv.innerHTML = '<div class="loading-spinner mx-auto"></div><p class="mt-2">Memproses...</p>';

    try {
        const res = await fetch('/web-api/predictions/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ livestock_id: id, prediction_days: days })
        });
        const json = await res.json();
        if (json.success) {
            renderPredictionResult(json.data);
            loadHistory();
        } else {
            resultsDiv.innerHTML = `<p class="text-red-500">${json.message || 'Gagal'}</p>`;
        }
    } catch (e) {
        resultsDiv.innerHTML = '<p class="text-red-500">Koneksi gagal</p>';
    }
}

function renderPredictionResult(data) {
    const res = data.prediction_result || data;
    const html = `
        <div class="text-center">
            <p class="text-4xl font-bold text-green-600">${res.predicted_gain.toFixed(3)} kg</p>
            <p class="text-sm text-gray-500">Prediksi kenaikan bobot</p>
        </div>
        <div class="grid grid-cols-3 gap-2 mt-4">
            <div class="p-2 bg-gray-50 rounded text-center">
                <p class="text-xs text-gray-500">Kepercayaan</p>
                <p class="font-bold">${(res.confidence * 100).toFixed(1)}%</p>
            </div>
            <div class="p-2 bg-gray-50 rounded text-center">
                <p class="text-xs text-gray-500">Bawah</p>
                <p class="font-bold">${res.interval.lower.toFixed(3)} kg</p>
            </div>
            <div class="p-2 bg-gray-50 rounded text-center">
                <p class="text-xs text-gray-500">Atas</p>
                <p class="font-bold">${res.interval.upper.toFixed(3)} kg</p>
            </div>
        </div>
        ${res.recommendations ? `<div class="mt-4 p-3 bg-blue-50 rounded"><i class="fas fa-lightbulb text-blue-500 mr-2"></i>${res.recommendations[0]}</div>` : ''}
    `;
    document.getElementById('prediction-results').innerHTML = html;
}

async function loadCorrelation() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/predictions/correlation');
        if (res.success) renderCorrelation(res.data);
    } catch (e) { console.error(e); }
}

function renderCorrelation(data) {
    const factors = data.factors || {};
    const container = document.getElementById('correlation-container');
    if (Object.keys(factors).length === 0) {
        container.innerHTML = '<div class="text-center col-span-2">Tidak ada data</div>';
        return;
    }
    container.innerHTML = Object.entries(factors).map(([k, v]) => `
        <div class="flex justify-between p-3 border rounded">
            <span class="font-medium">${k}</span>
            <span class="${v > 0.5 ? 'text-green-600' : 'text-yellow-600'}">${v.toFixed(2)}</span>
        </div>
    `).join('');
}

async function loadHistory() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/predictions/history?per_page=5');
        if (res.success) renderHistory(res.data.predictions);
    } catch (e) { console.error(e); }
}

function renderHistory(preds) {
    const container = document.getElementById('history-container');
    if (!preds || preds.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500">Belum ada riwayat</div>';
        return;
    }
    container.innerHTML = preds.map(p => `
        <div class="flex justify-between items-center p-3 border rounded">
            <div>
                <span class="font-medium">${p.livestock?.ear_tag || '-'}</span>
                <span class="text-sm text-gray-500 ml-2">${new Date(p.created_at).toLocaleDateString('id-ID')}</span>
            </div>
            <span class="text-green-600 font-bold">${p.predicted_gain} kg</span>
        </div>
    `).join('');
}
</script>
@endpush
