@extends('layouts.app')

@section('title', 'Manajemen Kandang')
@section('header-title', 'Manajemen Kandang')

@section('page-header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Manajemen Kandang</h1>
    <div class="flex space-x-2">
        <button onclick="openAddPenModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center shadow">
            <i class="fas fa-plus mr-2"></i> Tambah Kandang
        </button>
        <button onclick="openImportPenModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow">
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
            <div class="p-3 bg-green-100 rounded-xl text-green-600"><i class="fas fa-warehouse text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Kandang</p>
                <p class="text-xl font-bold" id="total-pens">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-xl text-green-600"><i class="fas fa-cow text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Kapasitas Total</p>
                <p class="text-xl font-bold" id="total-capacity">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-xl text-blue-600"><i class="fas fa-percent text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Okupansi</p>
                <p class="text-xl font-bold" id="occupancy-rate">-</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-xl text-yellow-600"><i class="fas fa-check-circle text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Kandang Tersedia</p>
                <p class="text-xl font-bold" id="available-pens">-</p>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700">Daftar Kandang</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Okupansi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody id="pens-table-body" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kandang -->
<div id="add-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-xl p-6 max-w-md w-full z-10 shadow-2xl">
            <h3 class="text-xl font-bold mb-5">Tambah Kandang Baru</h3>
            <form id="add-pen-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Kandang</label>
                    <input type="text" name="name" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kode (opsional)</label>
                    <input type="text" name="code" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="category" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="Fattening">Kandang Fattening</option>
                        <option value="Kambing">Kandang Kambing</option>
                        <option value="Kawin">Kandang Kawin</option>
                        <option value="Melahirkan">Kandang Melahirkan</option>
                        <option value="Menyusui">Kandang Menyusui</option>
                        <option value="Percobaan UB">Kandang Percobaan UB</option>
                        <option value="Prasapih">Kandang Prasapih</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kapasitas (ekor)</label>
                    <input type="number" name="capacity" min="1" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddPenModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="submitAddPenForm()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Impor Excel Kandang -->
<div id="import-pen-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="bg-white rounded-xl p-6 max-w-lg w-full z-10 shadow-2xl">
            <h3 class="text-xl font-bold mb-5">Impor Data Kandang dari Excel</h3>
            <form id="import-pen-form" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Pilih file Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full border border-gray-300 rounded-lg p-2">
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Format kolom: <code>nama, kode, kategori, kapasitas, status</code>. Baris pertama harus header.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImportPenModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Impor</button>
                </div>
            </form>
            <div id="import-pen-progress" class="hidden mt-4 text-center">
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
    loadPensData();
});

async function loadPensData() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/pens/data');
        if (res.success) {
            renderPensTable(res.data.pens);
            updatePensStats(res.data.stats);
        } else {
            showError(res.message);
        }
    } catch (error) {
        console.error(error);
        document.getElementById('pens-table-body').innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">Koneksi error</td></tr>';
    }
}

function renderPensTable(pens) {
    const tbody = document.getElementById('pens-table-body');
    if (!pens || pens.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada data</td></tr>';
        return;
    }
    tbody.innerHTML = pens.map(pen => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">${pen.name}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">${pen.category}</span></td>
            <td class="px-6 py-4">${pen.capacity}</td>
            <td class="px-6 py-4">
                ${pen.current_occupancy || 0} / ${pen.capacity}
                <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1">
                    <div class="bg-green-600 h-1.5 rounded-full" style="width: ${pen.capacity ? (pen.current_occupancy/pen.capacity*100) : 0}%"></div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs ${pen.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${pen.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></td>
            <td class="px-6 py-4">
                <a href="/pens/${pen.id}" class="text-green-600 hover:text-green-900 mr-2"><i class="fas fa-eye"></i></a>
                <a href="/pens/${pen.id}/analytics" class="text-blue-600 hover:text-blue-900"><i class="fas fa-chart-line"></i></a>
            </td>
        </tr>
    `).join('');
}

function updatePensStats(stats) {
    document.getElementById('total-pens').textContent = stats.total_pens || 0;
    document.getElementById('total-capacity').textContent = stats.total_capacity || 0;
    document.getElementById('occupancy-rate').textContent = (stats.occupancy_rate || 0) + '%';
    document.getElementById('available-pens').textContent = stats.available_pens || 0;
}

function openAddPenModal() { document.getElementById('add-pen-modal').classList.remove('hidden'); }
function closeAddPenModal() {
    document.getElementById('add-pen-modal').classList.add('hidden');
    document.getElementById('add-pen-form').reset();
}

async function submitAddPenForm() {
    const form = document.getElementById('add-pen-form');
    const data = Object.fromEntries(new FormData(form));
    const btn = form.querySelector('button[type="button"]:last-child');
    const orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = 'Menyimpan...';
    try {
        const res = await fetch('/web-api/pens/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            closeAddPenModal();
            loadPensData();
            TernakPark.ui.showToast('Kandang ditambahkan');
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false; btn.innerHTML = orig;
    }
}

function openImportPenModal() {
    document.getElementById('import-pen-modal').classList.remove('hidden');
}
function closeImportPenModal() {
    document.getElementById('import-pen-modal').classList.add('hidden');
    document.getElementById('import-pen-form').reset();
    document.getElementById('import-pen-progress').classList.add('hidden');
}

document.getElementById('import-pen-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('import-pen-progress').classList.remove('hidden');

    try {
        const res = await fetch('/web-api/pens/import', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        });
        const json = await res.json();
        if (json.success) {
            TernakPark.ui.showToast('Data kandang berhasil diimpor: ' + json.imported + ' record');
            closeImportPenModal();
            loadPensData();
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal impor', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
        document.getElementById('import-pen-progress').classList.add('hidden');
    }
});

function showError(m) { TernakPark.ui.showToast(m, 'error'); }
</script>
@endpush
