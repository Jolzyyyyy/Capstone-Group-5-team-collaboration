<div class="main-wrapper" x-data="inventoryApp" x-init="updateCurrentTime(); setInterval(() => updateCurrentTime(), 1000)">
    <header class="top-header">
        <h1 class="giant-title">Product Inventory</h1>
        
        <div class="header-right-date">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-right: 4px; margin-top: -2px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            <span x-text="currentTime"></span>
        </div>
    </header>

    <div class="box-container">
        <div class="filter-header-content">
            <div class="section-title">Manage Services</div>
            <div class="action-bar">
                <div class="search-wrapper">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Search services..." x-model="searchQuery">
                </div>
                
                <button class="btn-filter" :class="filterSoldOut ? 'active-mode' : ''" @click="filterSoldOut = !filterSoldOut">
                    <span x-text="filterSoldOut ? 'Filter: SOLD OUT' : 'Filter'"></span>
                </button>

                <button class="btn-add" @click="openFormModal('add')">+ Add New Product</button>
            </div>
        </div>
    </div>

    <div class="box-container">
        <div class="table-wrapper">
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center;">Image</th>
                        <th style="width: 28%; text-align: left;">Service Name</th>
                        <th style="width: 14%; text-align: left;">Category</th>
                        <th style="width: 13%; text-align: right;">Retail Price</th>
                        <th style="width: 13%; text-align: right;">Bulk Price</th>
                        <th style="width: 12%; text-align: center;">Stock Status</th>
                        <th style="width: 10%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in filteredProducts" :key="item.id">
                        <tr :style="item.stock.toUpperCase() === 'SOLDOUT' || item.stock === '0' ? 'background-color: #fffafb;' : ''">
                            <td style="text-align: center;">
                                <img :src="item.image || 'https://via.placeholder.com/60?text=No+Image'" alt="Product" class="table-img">
                            </td>
                            <td style="text-align: left; vertical-align: top;">
                                <div class="text-bold-data" style="color: var(--primary-blue);" x-text="item.name"></div>
                                <small class="text-desc-snippet" x-text="item.description ? item.description.substring(0, 40) + '...' : 'No description'"></small>
                            </td>
                            <td style="text-align: left;" x-text="item.category"></td>
                            <td style="text-align: right; padding-right: 25px;" class="text-bold-data" x-text="'₱' + parseFloat(item.retail_price || 0).toFixed(2)"></td>
                            <td style="text-align: right; padding-right: 25px;" class="text-bold-data" x-text="'₱' + parseFloat(item.bulk_price || 0).toFixed(2)"></td>
                            <td style="text-align: center;">
                                <template x-if="item.stock.toUpperCase() === 'SOLDOUT' || item.stock === '0'">
                                    <span class="text-soldout">SOLD OUT</span>
                                </template>
                                <template x-if="item.stock.toUpperCase() !== 'SOLDOUT' && item.stock !== '0'">
                                    <span class="status-badge" :class="item.stock === 'Unlimited' ? 'badge-unlimited' : 'badge-limited'" x-text="item.stock"></span>
                                </template>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-group">
                                    <button class="edit-btn" @click="openFormModal('edit', item)">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="delete-btn" @click="deleteProduct(item.id)">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-backdrop" x-show="isModalOpen" x-transition.opacity style="display: none;">
        <div class="modal-container" x-show="isModalOpen" x-transition.scale.85 @click.away="closeModal()">
            <div class="modal-header">
                <h2 x-text="modalMode === 'add' ? 'Add New Product / Service' : 'Edit Product Details'"></h2>
                <button class="close-modal-btn" @click="closeModal()">&times;</button>
            </div>
            
            <form @submit.prevent="saveProduct()">
                <div class="modal-body">
                    <div class="form-grid-left">
                        <label class="form-label">Product Image</label>
                        <div class="image-upload-box" @click="$refs.fileInput.click()">
                            <template x-if="form.image">
                                <img :src="form.image" class="image-preview" alt="Preview">
                            </template>
                            <template x-if="!form.image">
                                <div class="upload-placeholder">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                    <span>Upload Image</span>
                                </div>
                            </template>
                        </div>
                        <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="handleImageUpload($event)">
                        <small style="color: #64748b; margin-top: 5px; display:block; text-align:center;">Click the box above to choose a photo.</small>
                    </div>

                    <div class="form-grid-right">
                        <div class="form-group">
                            <label class="form-label">Service / Product Name</label>
                            <input type="text" x-model="form.name" required class="form-input" placeholder="e.g. Premium Tarpaulin Printing">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select x-model="form.category" required class="form-input">
                                    <option value="">Select Category</option>
                                    <option value="Printing">Printing</option>
                                    <option value="Services">Services</option>
                                    <option value="Photography">Photography</option>
                                    <option value="Finishing">Finishing</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Stock Status / Quantity</label>
                                <input type="text" x-model="form.stock" required class="form-input" placeholder="e.g. Unlimited, 50 pcs">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Retail Price (₱)</label>
                                <input type="number" step="0.01" min="0" x-model="form.retail_price" required class="form-input" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bulk Price (₱)</label>
                                <input type="number" step="0.01" min="0" x-model="form.bulk_price" required class="form-input" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description / Details</label>
                            <textarea x-model="form.description" rows="3" class="form-input" placeholder="Write item specification details here..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" @click="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit" x-text="modalMode === 'add' ? 'Add Product' : 'Save Changes'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-blue: #4F46E5;
        --border-color: #cbd5e1;
        --bg-light: #f4f7fa;
        --success-green: #10b981;
        --danger-red: #ef4444;
        --data-light-gray: #475569;
    }

    .main-wrapper {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px 20px 40px 20px;
    }

    .top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 30px;
    }

    .giant-title { 
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 38px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        margin: 0;
        line-height: 1;
        color: #1e293b;
    }

    .header-right-date {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: white;
        border: 1px solid var(--border-color);
        padding: 8px 14px;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
        height: fit-content;
    }

    .box-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .filter-header-content {
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 20px;
        color: #1e293b;
        margin: 0;
    }

    .action-bar { display: flex; align-items: center; gap: 10px; }

    .search-wrapper { position: relative; display: flex; align-items: center; }
    .search-wrapper input {
        padding: 8px 12px 8px 35px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        font-size: 13px;
        width: 220px;
        background: #f8fafc;
        outline: none;
    }
    .search-wrapper svg { position: absolute; left: 10px; color: #94a3b8; }

    .btn-filter {
        background-color: white;
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        cursor: pointer;
        min-width: 100px;
    }
    .btn-filter.active-mode { border-color: var(--danger-red); color: var(--danger-red); }

    .btn-add {
        background-color: var(--primary-blue);
        color: white; font-weight: 700; font-size: 12px;
        padding: 9px 16px; border-radius: 6px; border: none; cursor: pointer;
    }

    .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .main-table th {
        font-size: 13px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        padding: 15px 20px;
        background-color: #f8fafc;
        border-bottom: 2px solid var(--border-color);
        vertical-align: middle;
    }

    .main-table td {
        padding: 15px 20px;
        font-size: 14px;
        color: var(--data-light-gray);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .text-bold-data { font-weight: 600; color: #334155; font-size: 14px; }
    .text-desc-snippet { color: #64748b; font-size: 12px; display: block; margin-top: 2px;}

    .table-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        vertical-align: middle;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .badge-unlimited { background: #f0f9ff; color: #0284c7; }
    .badge-limited { background: #f0fdf4; color: #16a34a; }
    .text-soldout { color: var(--danger-red); font-weight: 900; font-size: 11px; }

    /* INAYOS NA ACTION GROUP: Ginawang block structure para laging center align */
    .action-group { 
        display: flex; 
        gap: 16px; 
        justify-content: center; 
        align-items: center;
        width: 100%;
        margin: 0 auto;
    }
    .edit-btn, .delete-btn { 
        background: transparent; 
        border: none; 
        cursor: pointer; 
        color: #94a3b8; 
        display: inline-flex; 
        align-items: center;
        justify-content: center;
        padding: 2px;
    }
    .edit-btn:hover { color: var(--primary-blue); }
    .delete-btn:hover { color: var(--danger-red); }

    /* Modal styles remain untouched */
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
    }

    .modal-container {
        background: white;
        width: 100%;
        max-width: 750px;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        overflow: hidden;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center;
        background: #f8fafc;
    }

    .modal-header h2 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
    .close-modal-btn { background: transparent; border: none; font-size: 28px; color: #64748b; cursor: pointer; line-height: 1; }
    .close-modal-btn:hover { color: #0f172a; }

    .modal-body {
        padding: 24px;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 24px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-grid-left { display: flex; flex-direction: column; align-items: stretch; }
    .image-upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        height: 200px;
        display: flex; align-items: center; justify-content: center;
        background: #f8fafc;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .image-upload-box:hover { border-color: var(--primary-blue); background: #f0fdf4; }
    .upload-placeholder { display: flex; flex-direction: column; align-items: center; gap: 8px; color: #64748b; font-size: 13px; font-weight: 500; }
    .image-preview { width: 100%; height: 100%; object-fit: cover; }
    .hidden { display: none; }

    .form-grid-right { display: flex; flex-direction: column; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-label { font-size: 13px; font-weight: 700; color: #334155; }
    
    .form-input {
        padding: 10px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        background: white;
        color: #0f172a;
        transition: border 0.15s;
    }
    .form-input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: none; font-family: inherit; }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex; justify-content: flex-end; gap: 12px;
        background: #f8fafc;
    }

    .btn-cancel {
        background: white; border: 1px solid var(--border-color);
        padding: 10px 18px; font-weight: 600; font-size: 13px; border-radius: 8px; cursor: pointer; color: #475569;
    }
    .btn-cancel:hover { background: #f1f5f9; }

    .btn-submit {
        background: var(--primary-blue); border: none;
        padding: 10px 20px; font-weight: 700; font-size: 13px; border-radius: 8px; cursor: pointer; color: white;
    }
    .btn-submit:hover { opacity: 0.9; }
</style>

<script>
    // AlpineJS script codes remain the same...
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('inventoryData')) {
            Alpine.data('inventoryApp', () => ({
                searchQuery: '',
                filterSoldOut: false,
                currentTime: '',
                
                isModalOpen: false,
                modalMode: 'add', 
                currentEditId: null,

                form: {
                    name: '',
                    category: '',
                    retail_price: '',
                    bulk_price: '',
                    stock: '',
                    description: '',
                    image: ''
                },

                products: [
                    { id: 1, name: 'DOCUMENT PRINTING', category: 'Printing', retail_price: '5.00', bulk_price: '3.50', stock: 'Unlimited', description: 'Standard A4/Short/Long size black and white or colored laser printouts.', image: '' },
                    { id: 2, name: 'PHOTOCOPY & SCANNING', category: 'Services', retail_price: '2.00', bulk_price: '1.20', stock: 'SOLDOUT', description: 'High-speed photocopy and high-resolution document digital scanning.', image: '' },
                    { id: 3, name: 'ID & PHOTO SERVICES', category: 'Photography', retail_price: '50.00', bulk_price: '40.00', stock: '100 sets', description: 'Passport size, 2x2, and 1x1 studio quality packages with free retouch.', image: '' },
                    { id: 4, name: 'LAMINATION & BINDING', category: 'Finishing', retail_price: '25.00', bulk_price: '20.00', stock: '50 pcs', description: 'Heavy-duty document plastic lamination and coil/ring document binding.', image: '' },
                    { id: 5, name: 'LARGE FORMAT PRINTING', category: 'Printing', retail_price: '500.00', bulk_price: '420.00', stock: 'SOLDOUT', description: 'High-quality tarpaulin, canvas, blueprints, and architectural posters.', image: '' },
                    { id: 6, name: 'CUSTOM SPECIAL PRINTING', category: 'Custom', retail_price: '150.00', bulk_price: '120.00', stock: '30 pcs', description: 'Per-order customized mug, t-shirt, tote bag, or premium corporate giveaway prints.', image: '' }
                ],

                updateCurrentTime() {
                    const now = new Date();
                    const options = { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
                    this.currentTime = now.toLocaleDateString('en-US', options).replace(' at', ' |');
                },

                get filteredProducts() {
                    let items = this.products.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
                    if (this.filterSoldOut) {
                        return items.filter(p => p.stock.toUpperCase() === 'SOLDOUT' || p.stock === '0');
                    }
                    return items;
                },

                openFormModal(mode, item = null) {
                    this.modalMode = mode;
                    if (mode === 'edit' && item) {
                        this.currentEditId = item.id;
                        this.form = { 
                            name: item.name, 
                            category: item.category, 
                            retail_price: item.retail_price, 
                            bulk_price: item.bulk_price, 
                            stock: item.stock, 
                            description: item.description || '', 
                            image: item.image || '' 
                        };
                    } else {
                        this.currentEditId = null;
                        this.form = { name: '', category: '', retail_price: '', bulk_price: '', stock: 'Unlimited', description: '', image: '' };
                    }
                    this.isModalOpen = true;
                },

                closeModal() {
                    this.isModalOpen = false;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.form.image = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                saveProduct() {
                    if (this.modalMode === 'add') {
                        const newId = this.products.length ? Math.max(...this.products.map(p => p.id)) + 1 : 1;
                        this.products.unshift({
                            id: newId,
                            name: this.form.name.toUpperCase(),
                            category: this.form.category,
                            retail_price: parseFloat(this.form.retail_price || 0).toFixed(2),
                            bulk_price: parseFloat(this.form.bulk_price || 0).toFixed(2),
                            stock: this.form.stock,
                            description: this.form.description,
                            image: this.form.image
                        });
                    } else if (this.modalMode === 'edit' && this.currentEditId) {
                        const index = this.products.findIndex(p => p.id === this.currentEditId);
                        if (index !== -1) {
                            this.products[index].name = this.form.name.toUpperCase();
                            this.products[index].category = this.form.category;
                            this.products[index].retail_price = parseFloat(this.form.retail_price || 0).toFixed(2);
                            this.products[index].bulk_price = parseFloat(this.form.bulk_price || 0).toFixed(2);
                            this.products[index].stock = this.form.stock;
                            this.products[index].description = this.form.description;
                            this.products[index].image = this.form.image;
                        }
                    }
                    this.closeModal();
                },

                deleteProduct(id) {
                    if (confirm('Are you absolute sure you want to delete this product/service from inventory?')) {
                        this.products = this.products.filter(p => p.id !== id);
                    }
                }
            }));
        }
    });
</script>