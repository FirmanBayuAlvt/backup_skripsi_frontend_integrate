<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('header-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl shadow-md p-5 card-hover border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-xl text-green-600">
                    <i class="fas fa-cow text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Ternak</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-livestock">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 card-hover border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                    <i class="fas fa-warehouse text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kandang Aktif</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-pens">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-xl text-yellow-600">
                    <i class="fas fa-seedling text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Stok Pakan (kg)</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-feed-stock">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 card-hover border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-xl text-purple-600">
                    <i class="fas fa-weight text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Rata-rata ADG</p>
                    <p class="text-2xl font-bold text-gray-800" id="avg-daily-gain">- kg</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📈 Pertumbuhan Bobot (30 hari)</h3>
            <canvas id="growthChart" height="250"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Distribusi Kandang</h3>
            <canvas id="penChart" height="250"></canvas>
        </div>
    </div>

    <!-- Alerts & Recent Predictions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i> Peringatan
            </h3>
            <div id="alerts-list" class="space-y-3">
                <div class="text-center py-8 text-gray-400">Memuat...</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-robot text-green-600 mr-2"></i> Prediksi Terbaru
            </h3>
            <div id="recent-predictions" class="space-y-3">
                <div class="text-center py-8 text-gray-400">Memuat...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

async function loadDashboardData() {
    try {
        const [overview, history, pens] = await Promise.all([
            TernakPark.api.fetchData('/web-api/dashboard/overview'),
            TernakPark.api.fetchData('/web-api/dashboard/predictions/history'),
            TernakPark.api.fetchData('/web-api/pens/data') // Ambil data kandang
        ]);
        if (overview.success) updateStats(overview.data.overview);
        if (history.success) updatePredictions(history.data.predictions);
        if (pens.success) updatePenChart(pens.data.pens);
        else initDummyPenChart(); // fallback jika gagal
    } catch (error) {
        console.error(error);
        TernakPark.ui.showToast('Gagal memuat dashboard', 'error');
        initDummyCharts();
    }
}

function updateStats(ov) {
    document.getElementById('total-livestock').textContent = TernakPark.format.number(ov.total_livestock || 0);
    document.getElementById('total-pens').textContent = TernakPark.format.number(ov.total_pens || 0);
    document.getElementById('total-feed-stock').textContent = TernakPark.format.number(ov.total_feed_stock_kg || 0);
    document.getElementById('avg-daily-gain').textContent = (ov.average_daily_gain || 0) + ' kg';

    const alertsDiv = document.getElementById('alerts-list');
    if (ov.alerts && ov.alerts.length) {
        alertsDiv.innerHTML = ov.alerts.map(a => `
            <div class="p-3 bg-${a.severity === 'warning' ? 'yellow' : 'blue'}-50 border-l-4 border-${a.severity === 'warning' ? 'yellow' : 'blue'}-500 rounded">
                <p class="font-medium">${a.message}</p>
                <p class="text-sm text-gray-600">${a.suggestion || ''}</p>
            </div>
        `).join('');
    } else {
        alertsDiv.innerHTML = '<p class="text-gray-400 text-center py-4">Tidak ada peringatan</p>';
    }

    // Growth chart tetap dummy, bisa dikembangkan nanti
    initGrowthChart();
}

function updatePredictions(preds) {
    const container = document.getElementById('recent-predictions');
    if (!preds || preds.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-center py-4">Belum ada prediksi</p>';
        return;
    }
    container.innerHTML = preds.map(p => `
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="font-medium">${p.livestock_ear_tag || 'Ternak'}</span>
            <span class="text-green-600 font-semibold">${p.predicted_gain} kg</span>
        </div>
    `).join('');
}

function updatePenChart(pens) {
    if (!pens || pens.length === 0) {
        initDummyPenChart();
        return;
    }
    // Ambil nama kandang dan jumlah ternak (current_occupancy)
    const labels = pens.map(p => p.name);
    const data = pens.map(p => p.current_occupancy || 0);
    new Chart(document.getElementById('penChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Ternak',
                data: data,
                backgroundColor: '#1e7b5e'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Ekor' } }
            }
        }
    });
}

function initGrowthChart() {
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: ['1','5','10','15','20','25','30'],
            datasets: [{
                label: 'Rata-rata Berat (kg)',
                data: [42,44,47,49,52,54,57],
                borderColor: '#1e7b5e',
                backgroundColor: 'rgba(30,123,94,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
}

function initDummyPenChart() {
    new Chart(document.getElementById('penChart'), {
        type: 'bar',
        data: {
            labels: ['Kandang Fattening', 'Kandang Kambing', 'Kandang Kawin'],
            datasets: [{
                label: 'Jumlah Ternak',
                data: [0, 0, 0],
                backgroundColor: '#1e7b5e'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
}

// Untuk fallback total (jika semua gagal)
function initDummyCharts() {
    initGrowthChart();
    initDummyPenChart();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tugas Akhir\Aplikasi_TernakParkWonosalam\ternakpark-frontend\resources\views/dashboard.blade.php ENDPATH**/ ?>