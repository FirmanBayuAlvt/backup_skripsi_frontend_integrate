<?php $__env->startSection('title', 'Manajemen Ternak'); ?>
<?php $__env->startSection('header-title', 'Manajemen Ternak'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar Ternak</h2>
        <div class="flex">
            <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Ternak
            </button>
            <button onclick="openImportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center ml-2">
                <i class="fas fa-file-excel mr-2"></i> Impor Excel
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Kandang</label>
            <select id="pen-filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="">Semua Kandang</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status-filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="">Semua</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis</label>
            <select id="breed-filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                <option value="">Semua</option>
                <option value="domba_lokal">Domba Lokal</option>
                <option value="domba_ekor_gemuk">Domba Ekor Gemuk</option>
                <option value="domba_garut">Domba Garut</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="applyFilters()" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-filter mr-2"></i> Terapkan
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ear Tag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berat (kg)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertumbuhan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="livestock-table-body" class="bg-white divide-y divide-gray-200">
                <tr><td colspan="6" class="px-6 py-4 text-center">Memuat...</td></tr>
            </tbody>
        </table>
    </div>
    <div id="pagination" class="mt-4 flex justify-between items-center"></div>
</div>

<!-- Modal Tambah Ternak -->
<div id="add-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg p-6 max-w-lg w-full z-10">
            <h3 class="text-lg font-bold mb-4">Tambah Ternak</h3>
            <form id="add-livestock-form" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Ear Tag</label>
                        <input type="text" name="ear_tag" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Kandang</label>
                        <select name="pen_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Kandang</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Jenis</label>
                        <select name="breed_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="domba_lokal">Domba Lokal</option>
                            <option value="domba_ekor_gemuk">Domba Ekor Gemuk</option>
                            <option value="domba_garut">Domba Garut</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Jenis Kelamin</label>
                        <select name="gender" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <option value="male">Jantan</option>
                            <option value="female">Betina</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Tanggal Lahir</label>
                        <input type="date" name="birth_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Berat Awal (kg)</label>
                        <input type="number" step="0.1" name="initial_weight" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Status Kesehatan</label>
                    <select name="health_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="good">Baik</option>
                        <option value="excellent">Sangat Baik</option>
                        <option value="fair">Cukup</option>
                        <option value="poor">Kurang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 border rounded-md hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="submitAddForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Catat Berat -->
<div id="weight-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg p-6 max-w-md w-full z-10">
            <h3 id="weight-modal-title" class="text-lg font-bold mb-4">Catat Berat Badan</h3>
            <form id="record-weight-form" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="weight-livestock-id" name="livestock_id">
                <div>
                    <label class="block text-sm font-medium">Berat (kg)</label>
                    <input type="number" step="0.1" name="weight_kg" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Pencatatan</label>
                    <input type="date" name="record_date" value="<?php echo e(date('Y-m-d')); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeWeightModal()" class="px-4 py-2 border rounded-md hover:bg-gray-50">Batal</button>
                    <button type="button" onclick="submitWeightForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Impor Excel -->
<div id="import-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg p-6 max-w-lg w-full z-10">
            <h3 class="text-lg font-bold mb-4">Impor Data Ternak dari Excel</h3>
            <form id="import-form" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Pilih file Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full border border-gray-300 rounded-lg p-2">
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Format: kolom harus sesuai (ear_tag, breed_type, gender, birth_date, initial_weight, health_status, notes, pen_id). Download template <a href="#" class="text-green-600 underline">di sini</a>.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 border rounded-md hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Impor</button>
                </div>
            </form>
            <div id="import-progress" class="hidden mt-4">
                <div class="loading-spinner mx-auto"></div>
                <p class="text-center text-sm text-gray-500 mt-2">Memproses...</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentPage = 1;
let filters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadLivestocks();
    loadPensForFilter();
    loadPensForModal(); // <-- pastikan ini dipanggil
});

async function loadLivestocks(page = 1) {
    currentPage = page;
    const params = new URLSearchParams({ page, ...filters });
    try {
        const res = await TernakPark.api.fetchData(`/web-api/livestocks/data?${params}`);
        if (res.success) {
            renderTable(res.data.livestocks);
            renderPagination(res.data.pagination);
        } else {
            document.getElementById('livestock-table-body').innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Gagal memuat data</td></tr>';
        }
    } catch (e) {
        document.getElementById('livestock-table-body').innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Koneksi error</td></tr>';
    }
}

function renderTable(livestocks) {
    const tbody = document.getElementById('livestock-table-body');
    if (!livestocks || livestocks.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Tidak ada data</td></tr>';
        return;
    }
    tbody.innerHTML = livestocks.map(l => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">${l.ear_tag}</td>
            <td class="px-6 py-4 whitespace-nowrap">${l.pen?.name || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">${l.current_weight}</td>
            <td class="px-6 py-4 whitespace-nowrap">${l.performance?.average_daily_gain?.toFixed(3) || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 rounded-full text-xs ${l.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${l.status ? 'Aktif' : 'Tidak Aktif'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
                <button onclick="openWeightModal(${l.id}, '${l.ear_tag}')" class="text-blue-600 hover:text-blue-900 mr-2" title="Catat Berat"><i class="fas fa-weight"></i></button>
                <a href="/livestocks/${l.id}" class="text-green-600 hover:text-green-900" title="Detail"><i class="fas fa-eye"></i></a>
            </td>
        </tr>
    `).join('');
}

function renderPagination(p) {
    const div = document.getElementById('pagination');
    if (!p || p.total <= p.per_page) {
        div.innerHTML = '';
        return;
    }
    div.innerHTML = `
        <div class="text-sm text-gray-600">Menampilkan ${((currentPage-1)*p.per_page)+1} - ${Math.min(currentPage*p.per_page, p.total)} dari ${p.total}</div>
        <div class="flex space-x-2">
            <button onclick="loadLivestocks(${currentPage-1})" ${currentPage===1 ? 'disabled' : ''} class="px-3 py-1 border rounded ${currentPage===1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}">Prev</button>
            <button onclick="loadLivestocks(${currentPage+1})" ${currentPage===p.last_page ? 'disabled' : ''} class="px-3 py-1 border rounded ${currentPage===p.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'}">Next</button>
        </div>
    `;
}

async function loadPensForFilter() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/pens/data');
        const select = document.getElementById('pen-filter');
        if (res.success && res.data.pens && res.data.pens.length > 0) {
            select.innerHTML = '<option value="">Semua Kandang</option>' +
                res.data.pens.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        } else {
            select.innerHTML = '<option value="">Tidak ada kandang</option>';
        }
    } catch (e) {
        console.error('Gagal memuat kandang untuk filter', e);
        document.getElementById('pen-filter').innerHTML = '<option value="">Gagal memuat</option>';
    }
}

async function loadPensForModal() {
    try {
        const res = await TernakPark.api.fetchData('/web-api/pens/data');
        const select = document.querySelector('select[name="pen_id"]');
        if (res.success && res.data.pens && res.data.pens.length > 0) {
            select.innerHTML = '<option value="">Pilih Kandang</option>' +
                res.data.pens.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        } else {
            select.innerHTML = '<option value="">Tidak ada kandang tersedia</option>';
        }
    } catch (e) {
        console.error('Gagal memuat kandang untuk modal', e);
        document.querySelector('select[name="pen_id"]').innerHTML = '<option value="">Gagal memuat</option>';
    }
}

function applyFilters() {
    filters = {
        pen_id: document.getElementById('pen-filter').value,
        status: document.getElementById('status-filter').value,
        breed_type: document.getElementById('breed-filter').value
    };
    Object.keys(filters).forEach(k => !filters[k] && delete filters[k]);
    loadLivestocks(1);
}

function openAddModal() { document.getElementById('add-modal').classList.remove('hidden'); }
function closeAddModal() {
    document.getElementById('add-modal').classList.add('hidden');
    document.getElementById('add-livestock-form').reset();
}

async function submitAddForm() {
    const form = document.getElementById('add-livestock-form');
    const data = Object.fromEntries(new FormData(form));
    const btn = form.querySelector('button[type="button"]:last-child');
    const orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = 'Menyimpan...';
    try {
        const res = await fetch('/web-api/livestocks/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            closeAddModal();
            loadLivestocks();
            TernakPark.ui.showToast('Ternak ditambahkan');
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false; btn.innerHTML = orig;
    }
}

function openWeightModal(id, earTag) {
    document.getElementById('weight-livestock-id').value = id;
    document.getElementById('weight-modal-title').innerText = 'Catat Berat - ' + earTag;
    document.getElementById('weight-modal').classList.remove('hidden');
}
function closeWeightModal() {
    document.getElementById('weight-modal').classList.add('hidden');
    document.getElementById('record-weight-form').reset();
}

async function submitWeightForm() {
    const form = document.getElementById('record-weight-form');
    const data = Object.fromEntries(new FormData(form));
    const id = data.livestock_id;
    const btn = form.querySelector('button[type="button"]:last-child');
    const orig = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = 'Menyimpan...';
    try {
        const res = await fetch(`/web-api/livestocks/${id}/record-weight`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            closeWeightModal();
            loadLivestocks();
            TernakPark.ui.showToast('Berat dicatat');
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false; btn.innerHTML = orig;
    }
}

function openImportModal() { document.getElementById('import-modal').classList.remove('hidden'); }
function closeImportModal() {
    document.getElementById('import-modal').classList.add('hidden');
    document.getElementById('import-form').reset();
    document.getElementById('import-progress').classList.add('hidden');
}

document.getElementById('import-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('import-progress').classList.remove('hidden');

    try {
        const res = await fetch('/web-api/livestocks/import', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        });
        const json = await res.json();
        if (json.success) {
            TernakPark.ui.showToast('Data berhasil diimpor: ' + json.imported + ' record');
            closeImportModal();
            loadLivestocks();
        } else {
            TernakPark.ui.showToast(json.message || 'Gagal impor', 'error');
        }
    } catch (e) {
        TernakPark.ui.showToast('Koneksi error', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
        document.getElementById('import-progress').classList.add('hidden');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Tugas Akhir\Aplikasi_TernakParkWonosalam\ternakpark-frontend\resources\views/livestocks/index.blade.php ENDPATH**/ ?>