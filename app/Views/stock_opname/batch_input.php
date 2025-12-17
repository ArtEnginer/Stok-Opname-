<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Batch Input by Location</h2>
            <p class="mt-1 text-sm text-gray-600">
                Session: <?= esc($session['session_code']) ?> |
                Date: <?= date('d M Y', strtotime($session['session_date'])) ?>
            </p>
        </div>
        <a href="<?= base_url('/stock-opname/' . $session['id']) ?>" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>
</div>

<!-- Batch Input Form -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <form id="batchForm" onsubmit="return false;">
        <input type="hidden" id="sessionId" value="<?= $session['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location / Rack *</label>
                <div class="relative">
                    <input type="text"
                        id="batchLocationSearch"
                        placeholder="Type to search location..."
                        autocomplete="off"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <input type="hidden" id="batch_location_id" name="location_id">
                    <div id="batchLocationResults" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto hidden"></div>
                    <p class="text-xs text-gray-500 mt-1">Search by code or name from master location</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Counted Date *</label>
                <input type="date"
                    id="countedDate"
                    value="<?= date('Y-m-d') ?>"
                    min="<?= $session['session_date'] ?>"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Penghitung <span class="text-red-500">*</span></label>
                <input type="text"
                    id="countedBy"
                    value="<?= esc(auth()->user() ? auth()->user()->username : '') ?>"
                    placeholder="Nama penghitung"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
                <p class="text-xs text-gray-500 mt-1">Otomatis diisi dari user login/silahkan isi menggunakan nama penghitung jika berbeda</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div class="text-sm text-blue-800">
                    <strong>SO Session Date:</strong> <?= date('d M Y', strtotime($session['session_date'])) ?><br>
                    <small>Baseline akan otomatis disesuaikan dengan mutasi dari tanggal SO sampai tanggal hitung untuk setiap item</small>
                </div>
            </div>
        </div>

        <!-- Search/Scan Section -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 mb-6 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-search mr-2"></i> Search / Scan Item
            </h3>

            <div class="flex gap-3 mb-4">
                <div class="flex-1">
                    <input type="text"
                        id="searchInput"
                        placeholder="Scan barcode or type code/PLU/name..."
                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                        autocomplete="off">
                </div>
                <button type="button"
                    onclick="searchItem()"
                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </div>

            <!-- Search Results Dropdown -->
            <div id="searchResults" class="hidden mt-2 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                <!-- Results will be populated here -->
            </div>

            <p class="text-sm text-gray-600 mt-2">
                <i class="fas fa-lightbulb text-yellow-500"></i>
                <strong>Tip:</strong> Use barcode scanner or type product code/PLU/name, then press Enter or click Search
            </p>
        </div>

        <!-- Added Items List -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Items to Count (<span id="itemCount">0</span>)
                </h3>
                <button type="button"
                    onclick="clearAllItems()"
                    class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                    <i class="fas fa-trash mr-1"></i> Clear All
                </button>
            </div>

            <div id="itemsList" class="border rounded-lg overflow-hidden">
                <!-- Items will be added here -->
                <div id="emptyState" class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p>No items added yet</p>
                    <p class="text-sm">Search and add items using the search box above</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Total items: <strong><span id="itemCountBottom">0</span></strong>
            </div>
            <div class="flex gap-2">
                <button type="button"
                    onclick="window.location.href='<?= base_url('/stock-opname/' . $session['id']) ?>'"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                    id="submitBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <i class="fas fa-save mr-2"></i> Save Batch
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let addedItems = [];
    const sessionId = <?= $session['id'] ?>;

    // Focus on search input when page loads
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').focus();

        // Prevent form submission on Enter key for all form elements
        document.getElementById('batchForm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();

                // If Enter is pressed on search input, do search or select item
                if (e.target.id === 'searchInput') {
                    handleSearchEnter();
                } else {
                    // Otherwise, focus to search input
                    document.getElementById('searchInput').focus();
                }
                return false;
            }
        });
    });

    // Handle Enter key on search input
    function handleSearchEnter() {
        const resultsDiv = document.getElementById('searchResults');

        // If search results are visible, select the first item
        if (!resultsDiv.classList.contains('hidden')) {
            const firstItem = resultsDiv.querySelector('div[data-item]');
            if (firstItem) {
                firstItem.click();
                return;
            }
        }

        // Otherwise, do search
        searchItem();
    }

    // Search item
    async function searchItem() {
        const keyword = document.getElementById('searchInput').value.trim();
        const locationId = document.getElementById('batch_location_id').value;

        if (!keyword) {
            return;
        }

        try {
            const response = await fetch(`<?= base_url('/stock-opname/') ?>${sessionId}/search-item?q=${encodeURIComponent(keyword)}&location_id=${locationId}`);
            const result = await response.json();

            console.log('Search result:', result); // Debug log

            if (result.success && result.items && result.items.length > 0) {
                displaySearchResults(result.items);
            } else {
                showNoResults();
            }
        } catch (error) {
            console.error('Search error:', error);
            alert('Error searching item: ' + error.message);
        }
    }

    // Display search results
    function displaySearchResults(items, showCountedStatus = false) {
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';

        items.forEach(item => {
            const div = document.createElement('div');
            const isCountedAtLocation = item.is_counted_at_current_location;
            const countedLocationsCount = item.counted_locations_count || 0;

            div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 flex justify-between items-center';
            div.setAttribute('data-item', JSON.stringify(item));

            // Jika sudah counted di location ini, highlight
            if (isCountedAtLocation) {
                div.className += ' bg-yellow-50';
            }

            // Tetap bisa add item meskipun sudah counted di lokasi lain
            div.onclick = () => addItem(item);

            let statusBadge = '';
            if (isCountedAtLocation) {
                statusBadge = '<span class="px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded ml-2">Sudah dihitung di lokasi ini</span>';
            } else if (countedLocationsCount > 0) {
                let locationInfo = item.counted_locations.map(loc => loc.nama_lokasi).join(', ');
                statusBadge = `<span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded ml-2" title="${locationInfo}">Sudah dihitung di ${countedLocationsCount} lokasi lain</span>`;
            }

            div.innerHTML = `
                <div>
                    <div class="font-semibold text-gray-900">${escapeHtml(item.code)} ${item.plu ? '/ ' + escapeHtml(item.plu) : ''} ${statusBadge}</div>
                    <div class="text-sm text-gray-600">${escapeHtml(item.name)}</div>
                    <div class="text-xs text-gray-500">${escapeHtml(item.category || '-')} | Baseline: ${parseFloat(item.baseline_stock).toFixed(2)}</div>
                </div>
                <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                    <i class="fas fa-plus"></i> Add
                </button>
            `;

            resultsDiv.appendChild(div);
        });

        resultsDiv.classList.remove('hidden');
    }

    // Show no results message
    function showNoResults() {
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No items found</div>';
        resultsDiv.classList.remove('hidden');

        setTimeout(() => {
            resultsDiv.classList.add('hidden');
        }, 2000);
    }

    // Add item to list
    function addItem(item) {
        // Check if already added
        if (addedItems.find(i => i.id === item.id)) {
            alert('Item already added to the list');
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('searchInput').value = '';
            document.getElementById('searchInput').focus();
            return;
        }

        addedItems.push({
            ...item,
            physical_stock: ''
        });

        renderItemsList();

        // Hide search results and clear search input
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('searchInput').value = '';

        // Focus on the physical stock input of the newly added item
        setTimeout(() => {
            const physicalInput = document.getElementById(`physical_${item.id}`);
            if (physicalInput) {
                physicalInput.focus();
                physicalInput.select();
            }
        }, 100);
    }

    // Render items list
    function renderItemsList() {
        const itemsList = document.getElementById('itemsList');
        const emptyState = document.getElementById('emptyState');

        const itemsArray = addedItems;

        if (itemsArray.length === 0) {
            emptyState.classList.remove('hidden');
            itemsList.querySelectorAll('.item-row').forEach(row => row.remove());
            updateItemCount();
            return;
        }

        emptyState.classList.add('hidden');

        // Clear existing items
        itemsList.querySelectorAll('.item-row').forEach(row => row.remove());

        // Add items
        itemsArray.forEach((item, index) => {
            const row = document.createElement('div');
            row.className = 'item-row border-b border-gray-200 p-4 hover:bg-gray-50 flex items-center gap-4';
            row.innerHTML = `
                <div class="flex-1 grid grid-cols-12 gap-3 items-center">
                    <div class="col-span-1 text-center text-sm font-semibold text-gray-600">${index + 1}</div>
                    <div class="col-span-2">
                        <div class="text-sm font-semibold text-gray-900">${escapeHtml(item.code)}</div>
                        ${item.plu ? `<div class="text-xs text-gray-500">${escapeHtml(item.plu)}</div>` : ''}
                    </div>
                    <div class="col-span-4">
                        <div class="text-sm text-gray-900">${escapeHtml(item.name)}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(item.category)}</div>
                    </div>
                    <div class="col-span-2 text-center">
                        <div class="text-xs text-gray-500">Baseline</div>
                        <div class="text-sm font-semibold">${parseFloat(item.baseline_stock).toFixed(2)}</div>
                    </div>
                    <div class="col-span-2">
                        <input type="number" 
                               step="0.01"
                               id="physical_${item.id}"
                               placeholder="Physical stock"
                               value="${item.physical_stock}"
                               onchange="updatePhysicalStock(${item.id}, this.value)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>
                    <div class="col-span-1 text-center">
                        <button type="button" 
                                onclick="removeItem(${item.id})"
                                class="px-2 py-1 text-red-600 hover:bg-red-50 rounded">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            itemsList.appendChild(row);
        });

        updateItemCount();
    }

    // Update physical stock value
    function updatePhysicalStock(itemId, value) {
        const item = addedItems.find(i => i.id === itemId);
        if (item) {
            item.physical_stock = value;
        }
    }

    // Remove item from list
    function removeItem(itemId) {
        const index = addedItems.findIndex(i => i.id === itemId);
        if (index > -1) {
            addedItems.splice(index, 1);
        }
        renderItemsList();
    }

    // Clear all items
    function clearAllItems() {
        if (addedItems.length === 0) {
            return;
        }

        if (confirm('Remove all items from the list?')) {
            addedItems = [];
            renderItemsList();
        }
    }

    // Update item count
    function updateItemCount() {
        const count = addedItems.length;
        document.getElementById('itemCount').textContent = count;
        document.getElementById('itemCountBottom').textContent = count;
        document.getElementById('submitBtn').disabled = count === 0;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Form submission
    document.getElementById('batchForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const locationId = document.getElementById('batch_location_id').value;
        const countedDate = document.getElementById('countedDate').value;
        const countedBy = document.getElementById('countedBy').value;

        if (!locationId) {
            alert('Please select a location from master location');
            return;
        }

        if (!countedBy.trim()) {
            alert('Penghitung harus diisi');
            return;
        }

        // Validate all items have physical stock
        const items = {};
        let hasInvalid = false;

        addedItems.forEach(item => {
            const physicalStock = item.physical_stock;

            if (physicalStock === '' || physicalStock === null) {
                hasInvalid = true;
                return;
            }

            items[item.id] = physicalStock;
        });

        if (hasInvalid) {
            alert('Please enter physical stock for all items');
            return;
        }

        const itemCount = Object.keys(items).length;

        if (itemCount === 0) {
            alert('Please add items to count');
            return;
        }

        if (!confirm(`Save ${itemCount} items for location "${location}"?`)) {
            return;
        }

        // Disable submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

        try {
            const formData = new URLSearchParams();
            formData.append('location_id', document.getElementById('batch_location_id').value);
            formData.append('counted_date', countedDate);
            formData.append('counted_by', countedBy);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            Object.keys(items).forEach(itemId => {
                formData.append(`items[${itemId}]`, items[itemId]);
            });

            const response = await fetch('<?= base_url('/stock-opname/' . $session['id'] . '/batch-save') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                let message = result.message;

                if (result.mutations && result.mutations.length > 0) {
                    message += '\\n\\nMutations detected for ' + result.mutations.length + ' items:';
                    result.mutations.forEach(m => {
                        message += '\\n- Item ID ' + m.item_id + ': ' + (m.mutation > 0 ? '+' : '') + m.mutation.toFixed(2);
                    });
                    message += '\\n\\nBaselines have been adjusted automatically.';
                }

                alert(message);
                window.location.href = '<?= base_url('/stock-opname/' . $session['id']) ?>';
            } else {
                alert('Error: ' + result.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Batch';
            }
        } catch (error) {
            alert('Error saving data: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Batch';
        }
    });

    // Batch Location search with debouncing
    let batchLocationTimeout;
    document.getElementById('batchLocationSearch')?.addEventListener('input', function() {
        clearTimeout(batchLocationTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            document.getElementById('batchLocationResults').classList.add('hidden');
            return;
        }

        batchLocationTimeout = setTimeout(async () => {
            try {
                const response = await fetch('<?= base_url('admin/location/api/search') ?>?q=' + encodeURIComponent(query));
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    const resultsHtml = result.data.map(loc => `
                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b" 
                             onclick="selectBatchLocation(${loc.id}, '${loc.label}')">
                            <div class="font-medium text-sm">${loc.code} - ${loc.name}</div>
                            <div class="text-xs text-gray-500">${loc.department || '-'}</div>
                        </div>
                    `).join('');

                    document.getElementById('batchLocationResults').innerHTML = resultsHtml;
                    document.getElementById('batchLocationResults').classList.remove('hidden');
                } else {
                    document.getElementById('batchLocationResults').innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No locations found</div>';
                    document.getElementById('batchLocationResults').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error searching locations:', error);
            }
        }, 500);
    });

    function selectBatchLocation(id, label) {
        document.getElementById('batch_location_id').value = id;
        document.getElementById('batchLocationSearch').value = label;
        document.getElementById('batchLocationResults').classList.add('hidden');
    }

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#batchLocationSearch') && !e.target.closest('#batchLocationResults')) {
            document.getElementById('batchLocationResults')?.classList.add('hidden');
        }
    });
</script>
<?= $this->endSection() ?>