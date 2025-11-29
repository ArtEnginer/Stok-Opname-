<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Location Management</h2>
            <p class="mt-1 text-sm text-gray-600">Manage warehouse locations, racks, and storage areas</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('admin/location/import') ?>"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <i class="fas fa-file-import mr-2"></i> Import
            </a>
            <a href="<?= base_url('admin/location/export') ?>"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="<?= base_url('admin/location/create') ?>"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Location
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                <i class="fas fa-map-marker-alt text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-600">Total Locations</div>
                <div class="text-2xl font-bold text-gray-900" id="totalLocations">-</div>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-600">Active</div>
                <div class="text-2xl font-bold text-green-600" id="activeLocations">-</div>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-600">Departments</div>
                <div class="text-2xl font-bold text-gray-900" id="totalDepartments">-</div>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                <i class="fas fa-times-circle text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-600">Inactive</div>
                <div class="text-2xl font-bold text-red-600" id="inactiveLocations">-</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" id="searchInput"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Search location...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
            <select id="departmentFilter"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Departments</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="statusFilter"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="aktif">Active</option>
                <option value="tidak_aktif">Inactive</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="resetFilters()"
                class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="locationsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Code
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Location Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Department
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Loading...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium" id="showingFrom">0</span> to
                <span class="font-medium" id="showingTo">0</span> of
                <span class="font-medium" id="totalRecords">0</span> results
            </div>
            <div id="paginationButtons" class="flex gap-2">
                <!-- Pagination buttons will be inserted here -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentPage = 1;
    let itemsPerPage = 10;
    let searchTimeout;
    let allLocations = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadLocations();

        // Search with debouncing
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadLocations();
            }, 500);
        });

        // Department filter
        document.getElementById('departmentFilter').addEventListener('change', function() {
            currentPage = 1;
            loadLocations();
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            currentPage = 1;
            loadLocations();
        });
    });

    async function loadLocations() {
        try {
            const search = document.getElementById('searchInput').value;
            const department = document.getElementById('departmentFilter').value;
            const status = document.getElementById('statusFilter').value;

            const response = await fetch('<?= base_url('admin/location/api/list') ?>' +
                `?page=${currentPage}&per_page=${itemsPerPage}&search=${encodeURIComponent(search)}&department=${encodeURIComponent(department)}&status=${status}`);

            if (!response.ok) throw new Error('Failed to load locations');

            const data = await response.json();
            allLocations = data.data;

            displayLocations(data);
            updateStats(data.stats);
            updateDepartmentFilter(data.departments);
        } catch (error) {
            console.error('Error:', error);
            showError('Failed to load locations');
        }
    }

    function displayLocations(data) {
        const tbody = document.getElementById('tableBody');

        if (data.data.length === 0) {
            tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No locations found</p>
                </td>
            </tr>
        `;
            return;
        }

        tbody.innerHTML = data.data.map(location => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${escapeHtml(location.kode_lokasi)}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${escapeHtml(location.nama_lokasi)}</div>
                ${location.keterangan ? `<div class="text-xs text-gray-500 mt-1">${escapeHtml(location.keterangan)}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                    ${location.departemen || '-'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-semibold rounded-full ${location.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${location.status === 'aktif' ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex gap-2">
                    <a href="<?= base_url('admin/location/edit/') ?>${location.id}" 
                       class="text-indigo-600 hover:text-indigo-900 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteLocation(${location.id}, '${escapeHtml(location.nama_lokasi)}')" 
                            class="text-red-600 hover:text-red-900 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

        updatePagination(data.pagination);
    }

    function updateStats(stats) {
        document.getElementById('totalLocations').textContent = stats.total || 0;
        document.getElementById('activeLocations').textContent = stats.active || 0;
        document.getElementById('inactiveLocations').textContent = stats.inactive || 0;
        document.getElementById('totalDepartments').textContent = stats.departments || 0;
    }

    function updateDepartmentFilter(departments) {
        const select = document.getElementById('departmentFilter');
        const currentValue = select.value;

        select.innerHTML = '<option value="">All Departments</option>' +
            departments.map(dept => `<option value="${escapeHtml(dept)}">${escapeHtml(dept)}</option>`).join('');

        select.value = currentValue;
    }

    function updatePagination(pagination) {
        document.getElementById('showingFrom').textContent = pagination.from;
        document.getElementById('showingTo').textContent = pagination.to;
        document.getElementById('totalRecords').textContent = pagination.total;

        const buttonsContainer = document.getElementById('paginationButtons');
        let buttons = '';

        // Previous button
        buttons += `
        <button onclick="changePage(${pagination.current_page - 1})" 
                ${pagination.current_page === 1 ? 'disabled' : ''}
                class="px-3 py-1 border rounded ${pagination.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
            Previous
        </button>
    `;

        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            if (i === 1 || i === pagination.total_pages || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
                buttons += `
                <button onclick="changePage(${i})" 
                        class="px-3 py-1 border rounded ${i === pagination.current_page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                    ${i}
                </button>
            `;
            } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                buttons += '<span class="px-2">...</span>';
            }
        }

        // Next button
        buttons += `
        <button onclick="changePage(${pagination.current_page + 1})" 
                ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}
                class="px-3 py-1 border rounded ${pagination.current_page === pagination.total_pages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
            Next
        </button>
    `;

        buttonsContainer.innerHTML = buttons;
    }

    function changePage(page) {
        currentPage = page;
        loadLocations();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('statusFilter').value = '';
        currentPage = 1;
        loadLocations();
    }

    async function deleteLocation(id, name) {
        const result = await Swal.fire({
            title: 'Delete Location?',
            text: `Are you sure you want to delete "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch('<?= base_url('admin/location/delete/') ?>' + id, {
                method: 'DELETE'
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire('Deleted!', data.message, 'success');
                loadLocations();
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error!', 'Failed to delete location', 'error');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showError(message) {
        Swal.fire('Error', message, 'error');
    }
</script>
<?= $this->endSection() ?>