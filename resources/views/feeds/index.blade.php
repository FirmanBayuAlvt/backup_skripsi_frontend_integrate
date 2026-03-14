@extends('layouts.app')

@section('title', 'Manajemen Pakan')
@section('header-title', 'Manajemen Pakan')

@section('page-header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Manajemen Pakan</h1>
    <div class="flex space-x-2">
        <button onclick="openAddFeedModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center shadow">
            <i class="fas fa-plus mr-2"></i> Tambah Pakan
        </button>
        <button onclick="openImportFeedModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow">
            <i class="fas fa-file-excel mr-2"></i> Impor Excel
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-xl text-green-600"><i class="fas fa-seedling text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Jenis Pakan</p>
                <p class="text-xl font-bold" id="total-feed-types">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-xl text-green-600"><i class="fas fa-warehouse text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Stok (kg)</p>
                <p class="text-xl font-bold" id="total-stock">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-xl text-yellow-600"><i class="fas fa-exclamation-triangle text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Stok Rendah</p>
                <p class="text-xl font-bold" id="low-stock">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-xl text-blue-600"><i class="fas fa-money-bill-wave text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Nilai Stok</p>
                <p class="text-xl font-bold" id="stock-value">-</p>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700">Daftar Pakan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok (kg)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/kg</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="feeds-table-body" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pakan -->
<div id="add-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-xl p-6 max-w-md w-full z-10 shadow-2xl">
            <h3 class="text-xl font-bold mb-5">Tambah Pakan Baru</h3>
            <form id="add-feed-form" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium mb-1">Nama Pakan</label><input type="text" name="name" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"></div>
                <div><label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="category" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="silase">Silase</option>
                        <option value="cf_jember">CF Jember</option>
                        <option value="jagung_halus">Jagung Halus</option>
                        <option value="konsentrat">Konsentrat</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium mb-1">Stok Awal (kg)</label><input type="number" step="0.01" name="current_stock" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"></div>
                <div><label class="block text-sm font-medium mb-1">Harga per kg</label><input type="number" step="0.01" name="price_per_kg" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"></div>
                <div><label class="block text-sm font-medium mb-1">Satuan</label><input type="text" name="unit" value="kg" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"></div>
                <div><label class="block text-sm font-medium mb-1">Status</label>
                    <select name="is_active" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddFeedModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="submitAddFeedForm()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Impor Excel Pakan -->
<div id="import-feed-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-xl p-6 max-w-lg w-full z-10 shadow-2xl">
            <h3 class="text-xl font-bold mb-5">Impor Data Pakan dari Excel</h3>
            <form id="import-feed-form" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Pilih file Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full border border-gray-300 rounded-lg p-2">
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Format kolom: <code>nama, kategori, stok_awal, harga_per_kg, satuan, aktif</code>. Baris pertama harus header.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImportFeedModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Impor</button>
                </div>
            </form>
            <div id="import-feed-progress" class="hidden mt-4 text-center">
                <div class="loading-spinner mx-auto"></div>
                <p class="text-sm text-gray-500 mt-2">Memproses...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadFeedsData();
});

async function loadFeedsData() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/feeds/data');
        if (res.success) {
            renderFeedsTable(res.data.feed_types);
            updateFeedsStats(res.data);
        } else showError(res.message);
    } catch (error) {
        console.error(error);
        document.getElementById('feeds-table-body').innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">Koneksi error</td></tr>';
    }
}

function renderFeedsTable(feeds) {
    const tbody = document.getElementById('feeds-table-body');
    if (!feeds || feeds.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada data</td></tr>';
        return;
    }
    tbody.innerHTML = feeds.map(f => `
        <tr>
            <td class="px-6 py-4">${f.name}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">${f.category}</span></td>
            <td class="px-6 py-4">${f.current_stock}</td>
            <td class="px-6 py-4">${TernakPark.format.currency(f.price_per_kg)}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs ${f.is_stock_low ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">${f.is_stock_low ? 'Stok Rendah' : 'Aman'}</span></td>
            <td class="px-6 py-4"><button class="text-blue-600 hover:text-blue-900">Edit</button></td>
        </tr>
    `).join('');
}

function updateFeedsStats(data) {
    document.getElementById('total-feed-types').textContent = data.total_types || 0;
    document.getElementById('total-stock').textContent = data.stock_summary?.total_stock_kg || 0;
    document.getElementById('low-stock').textContent = data.low_stock_count || 0;
    document.getElementById('stock-value').textContent = TernakPark.format.currency(data.stock_summary?.total_value || 0);
}

function openAddFeedModal() { document.getElementById('add-feed-modal').classList.remove('hidden'); }
function closeAddFeedModal() {
    document.getElementById('add-feed-modal').classList.add('hidden');
    document.getElementById('add-feed-form').reset();
}
async function submitAddFeedForm() {
    const form = document.getElementById('add-feed-form');
    const data = Object.fromEntries(new FormData(form));
    data.is_active = data.is_active === '1';
    const btn = form.querySelector('button[type="button"]:last-child');
    const orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = 'Menyimpan...';
    try {
        const res = await fetch('/web-api/feeds/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            closeAddFeedModal();
            loadFeedsData();
            TernakPark.ui.showToast('Pakan ditambahkan');
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false; btn.innerHTML = orig;
    }
}

function openImportFeedModal() {
    document.getElementById('import-feed-modal').classList.remove('hidden');
}
function closeImportFeedModal() {
    document.getElementById('import-feed-modal').classList.add('hidden');
    document.getElementById('import-feed-form').reset();
    document.getElementById('import-feed-progress').classList.add('hidden');
}

document.getElementById('import-feed-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('import-feed-progress').classList.remove('hidden');

    try {
        const res = await fetch('/web-api/feeds/import', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        });
        const json = await res.json();
        if (json.success) {
            TernakPark.ui.showToast('Data pakan berhasil diimpor: ' + json.imported + ' record');
            closeImportFeedModal();
            loadFeedsData();
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal impor', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
        document.getElementById('import-feed-progress').classList.add('hidden');
    }
});

function showError(m) { TernakPark.ui.showToast(m, 'error'); }
</script>
@endpush
