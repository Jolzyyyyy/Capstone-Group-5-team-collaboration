<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root {
        --primary-blue: #4F46E5;
        --border-color: #e2e8f0;
        --bg-light: #f8fafc;
        --success-green: #10b981;
        --danger-red: #ef4444;
        --warning-orange: #f59e0b;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --accent-blue: #3b82f6;
    }

    /* GLOBAL RESET & TRANSITIONS */
    * { box-sizing: border-box; transition: all 0.2s ease; }

    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--bg-light); 
        margin: 0; 
        color: var(--text-main); 
        line-height: 1.4;
    }

    .main-wrapper {
        width: 100%;
        max-width: 1100px;
        margin-left: 40px;
        margin-right: auto;
        padding: 5px 20px;
    }

    .header-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 25px; 
        margin-top: 10px;
    }

    .giant-title { 
        font-family: 'DM Serif Display', serif;
        font-size: 32px; 
        text-transform: uppercase; 
        letter-spacing: -0.01em; 
        margin: 0;
        color: #0f172a;
        display: flex;
        align-items: center;
    }

    .header-left-date {
        font-size: 11px;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        background: white;
        padding: 10px 14px; 
        border-radius: 6px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        height: 38px;
    }

    /* User Profile Hover States */
    .user-info-group { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        cursor: pointer; 
        padding: 6px 10px;
        border-radius: 8px;
        width: fit-content;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .user-info-group:hover {
        background-color: #EEF2FF;
    }
    .user-info-group:hover .text-bold-data {
        color: var(--primary-blue);
        text-decoration: underline;
    }

    /* DALAWANG HIWALAY NA BOXES */
    .box-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        width: 100%;
    }

    .filter-header-content {
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title { 
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }

    .action-bar { display: flex; align-items: center; gap: 8px; }

    .search-wrapper { position: relative; }
    .search-wrapper input {
        padding: 8px 10px 8px 32px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 12px;
        width: 220px;
        outline: none;
        height: 34px;
    }
    .search-wrapper input:focus { border-color: var(--primary-blue); }
    .search-wrapper svg { position: absolute; left: 10px; top: 10px; color: #94a3b8; width: 13px; height: 13px; }

    /* Dynamic Filter Colors */
    .btn-filter {
        background: white; 
        border: 1px solid #cbd5e1; 
        padding: 8px 12px; 
        border-radius: 6px; 
        font-weight: 700; 
        font-size: 12px; 
        cursor: pointer;
        display: flex; 
        align-items: center; 
        gap: 5px;
        transition: all 0.3s ease;
        height: 34px;
    }
    .filter-active {
        background-color: #ecfdf5 !important;
        border-color: #10b981 !important;
        color: #10b981 !important;
    }
    .filter-inactive {
        background-color: #fef2f2 !important;
        border-color: #ef4444 !important;
        color: #ef4444 !important;
    }

    .btn-add {
        background: var(--primary-blue); color: white; border: none; padding: 0 15px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer; height: 34px; display: flex; align-items: center;
    }

    .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    
    .main-table th {
        font-size: 14px; 
        font-weight: 800; 
        color: #000000;
        text-transform: capitalize;
        padding: 12px 15px; 
        background-color: #fcfcfd; 
        border-bottom: 2px solid #f1f5f9;
        text-align: left; 
        letter-spacing: 0.02em;
    }
    .main-table td { padding: 10px 15px; font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* COLUMN WIDTHS */
    .col-profile { width: 220px; }
    .col-email { width: 200px; }
    .col-role { width: 120px; }
    .col-status { width: 100px; }
    .col-activity { width: 150px; }
    .col-action { width: 110px; text-align: center; }

    /* BINAGO: Pinalaki ang laki ng table avatar at ginawang flexible */
    .user-avatar {
        width: 40px; height: 40px; background: #1e293b; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;
    }
    .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .text-bold-data { font-weight: 700; color: #1e293b; font-size: 13.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Action Buttons Alignment */
    .action-buttons-container {
        display: flex;
        gap: 12px;
        justify-content: center;
        align-items: center;
    }

    /* STATS BOXES */
    .stats-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
    .stat-box {
        background: #fff; border: 1px solid #f1f5f9; padding: 15px; border-radius: 12px;
        cursor: pointer; position: relative; overflow: hidden;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
    }
    .stat-box:hover { border-color: var(--primary-blue); transform: translateY(-1px); }
    .stat-box .s-label { font-size: 9px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 10px; display: block; }
    .stat-box .s-value { font-size: 22px; font-weight: 900; color: #1e293b; display: block; }
    
    /* MODAL STYLES */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 1000;
        backdrop-filter: blur(8px);
    }
    .modal-content {
        background: white; padding: 30px; border-radius: 24px; width: 850px;
        max-height: 90vh; overflow-y: auto;
    }

    .form-input-styled {
        width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; 
        border-radius: 10px; font-size: 13px; font-weight: 500;
        background: #f8fafc; outline: none; margin-top: 4px;
    }
    
    .profile-item label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; display: block; }
    .profile-item span { font-size: 14px; font-weight: 700; color: #1e293b; }

    /* STATUS BADGES */
    .status-badge { font-size: 9px; font-weight: 900; padding: 3px 10px; border-radius: 5px; text-transform: uppercase; }
    .status-badge.status-active { color: #10b981; background: #ecfdf5; }
    .status-badge.status-inactive { color: #ef4444; background: #fef2f2; }

    .text-red { color: var(--danger-red) !important; font-weight: 700; }
    .text-blue { color: var(--primary-blue) !important; font-weight: 700; }
    .text-green { color: var(--success-green) !important; font-weight: 700; }
    .text-black { color: #000 !important; font-weight: 800; }

    [x-cloak] { display: none !important; }
</style>

<div x-data="userApp" x-init="startClock()" class="main-wrapper">
    <div class="header-title-row">
        <h1 class="giant-title">Customer Database</h1>
        <div class="header-left-date">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted); margin-right: 2px;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span x-text="currentTime" class="text-black"></span>
        </div>
    </div>

    <div class="box-container" style="margin-bottom: 20px;">
        <div class="filter-header-content">
            <div class="section-title">Manage Registered Users</div>
            <div class="action-bar">
                <div class="search-wrapper">
                    <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search records..." x-model="searchQuery">
                </div>
                <button class="btn-filter" 
                        :class="statusFilter === 'ACTIVE' ? 'filter-active' : (statusFilter === 'INACTIVE' ? 'filter-inactive' : '')"
                        @click="toggleStatusFilter()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
                    <button-text x-show="!statusFilter">Filter</button-text>
                    <button-text x-show="statusFilter" x-text="'Filter: ' + statusFilter"></button-text>
                </button>
                <button class="btn-add" @click="addUser()">+ Add New User</button>
            </div>
        </div>
    </div>

    <div class="box-container">
        <table class="main-table">
            <thead>
                <tr>
                    <th class="col-profile">User Profile</th>
                    <th class="col-email">Email Address</th>
                    <th class="col-role">User Role</th>
                    <th class="col-status">Status</th>
                    <th class="col-activity">Last Activity</th>
                    <th class="col-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(user, index) in filteredUsers" :key="user.id || index">
                    <tr>
                        <td>
                            <div class="user-info-group" @click="viewHistory(user)">
                                <div class="user-avatar">
                                    <template x-if="user.profilePic">
                                        <img :src="user.profilePic" alt="Avatar">
                                    </template>
                                    <template x-if="!user.profilePic">
                                        <span style="color:white; font-weight:800; font-size: 13px;" x-text="user.name ? user.name.substring(0, 1).toUpperCase() : 'U'"></span>
                                    </template>
                                </div>
                                <div>
                                    <span class="text-bold-data" x-text="user.name"></span>
                                </div>
                            </div>
                        </td>
                        <td x-text="user.email" style="color: #64748b; font-weight: 500;"></td>
                        <td><span style="font-weight: 600;" x-text="user.role"></span></td>
                        <td>
                            <span class="status-badge" :class="user.status === 'ACTIVE' ? 'status-active' : 'status-inactive'" x-text="user.status"></span>
                        </td>
                        <td x-text="user.lastLogin || 'Just now'" style="font-weight: 500; font-size: 12px; color: #64748b;"></td>
                        <td>
                            <div class="action-buttons-container">
                                <button @click="editUser(user)" style="background:none; border:none; cursor:pointer; color:#10b981; padding: 4px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="deleteUser(user)" style="background:none; border:none; cursor:pointer; color:#ef4444; padding: 4px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" x-show="showHistoryModal" x-cloak x-transition>
        <div class="modal-content" @click.away="showHistoryModal = false">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 28px; margin: 0;">User Details</h2>
                <span class="status-badge" :class="selectedUser.status === 'ACTIVE' ? 'status-active' : 'status-inactive'" x-text="selectedUser.status"></span>
            </div>

            <div style="display: grid; grid-template-columns: 80px 1.5fr 1.5fr 2fr; gap: 20px; margin-bottom: 30px; background: #fafafa; padding: 20px; border-radius: 16px;">
                <div class="profile-item">
                    <label>Profile</label>
                    <div class="user-avatar" style="width: 60px; height: 60px; background: #1e293b; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <template x-if="selectedUser.profilePic">
                            <img :src="selectedUser.profilePic" alt="P">
                        </template>
                        <template x-if="!selectedUser.profilePic">
                            <span style="color:white; font-weight:800; font-size: 18px; display:flex; align-items:center; justify-content:center; height:100%;" x-text="selectedUser.name ? selectedUser.name.substring(0, 1).toUpperCase() : 'U'"></span>
                        </template>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:15px;">
                    <div class="profile-item">
                        <label>Full Name</label>
                        <span class="text-black" x-text="selectedUser.name"></span>
                    </div>
                    <div class="profile-item">
                        <label>Phone No</label>
                        <span style="color:var(--text-muted); font-size:13px;" x-text="selectedUser.phone || 'None'"></span>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:15px;">
                    <div class="profile-item">
                        <label>Gender</label>
                        <span class="text-black" x-text="selectedUser.gender || 'N/A'"></span>
                    </div>
                    <div class="profile-item">
                        <label>Birthdate</label>
                        <span style="color:var(--text-muted); font-size:13px;" x-text="selectedUser.birthdate || 'N/A'"></span>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:15px;">
                    <div class="profile-item">
                        <label>Shipping Address</label>
                        <span style="color:var(--text-muted); font-size:13px;" x-text="selectedUser.address || 'No address provided'"></span>
                    </div>
                    <div class="profile-item">
                        <label>Member Since</label>
                        <span class="text-blue" x-text="selectedUser.regDate"></span>
                    </div>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <span class="s-label">Total Spend</span>
                    <span class="s-value text-black" x-text="'₱' + calculateTotalSpend(selectedUser)"></span>
                </div>
                <div class="stat-box">
                    <span class="s-label">Total Orders</span>
                    <span class="s-value text-black" x-text="selectedUser.orders ? selectedUser.orders.length : 0"></span>
                </div>
                <div class="stat-box">
                    <span class="s-label">Cancelled</span>
                    <span class="s-value text-red" x-text="selectedUser.cancelledCount || 0"></span>
                </div>
                <div class="stat-box">
                    <span class="s-label">Reliability</span>
                    <span class="s-value text-green" x-text="calculateReliability(selectedUser) + '%'"></span>
                </div>
            </div>

            <h3 style="font-family: 'DM Serif Display', serif; font-size: 18px; margin-bottom: 12px;">Transaction History</h3>
            <div style="background: white; border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden;">
                <table class="main-table" style="font-size: 12px; table-layout: auto;">
                    <thead style="background: #fafafa;">
                        <tr>
                            <th style="color:black; font-size:11px;">Order ID</th>
                            <th style="color:black; font-size:11px;">Purchased Items</th>
                            <th style="color:black; font-size:11px;">Date</th>
                            <th style="color:black; font-size:11px;">Amount</th>
                            <th style="color:black; font-size:11px;">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="order in selectedUser.orders" :key="order.id">
                            <tr>
                                <td class="text-blue" x-text="order.id"></td>
                                <td x-text="order.items" style="font-weight: 500;"></td>
                                <td x-text="order.date"></td>
                                <td class="text-black" x-text="'₱' + order.amount"></td>
                                <td>
                                    <span :class="order.status === 'Completed' ? 'status-active' : 'status-inactive'" 
                                          style="font-size: 8px; padding: 2px 6px;" x-text="order.status"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 25px; display: flex; justify-content: flex-end;">
                <button @click="showHistoryModal = false" 
                        style="background: var(--primary-blue); color: white; padding: 12px 30px; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; font-size: 13px;">
                    Close Profile
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" x-show="showEditModal" x-cloak x-transition>
        <div class="modal-content" style="width: 600px;" @click.away="showEditModal = false">
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 24px; margin-bottom: 20px;" x-text="editingIndex === -1 ? 'Register New User' : 'Update User Info'"></h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="profile-item">
                    <label>Full Name</label>
                    <input type="text" class="form-input-styled" x-model="editingUser.name" placeholder="Enter complete name">
                </div>
                <div class="profile-item">
                    <label>Email Address</label>
                    <input type="email" class="form-input-styled" x-model="editingUser.email" placeholder="email@address.com">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="profile-item">
                    <label>Phone Number</label>
                    <input type="text" class="form-input-styled" x-model="editingUser.phone" placeholder="09XX-XXX-XXXX">
                </div>
                <div class="profile-item">
                    <label>User Role</label>
                    <select class="form-input-styled" x-model="editingUser.role">
                        <option value="Customer">Customer</option>
                        <option value="Wholesaler">Wholesaler</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="profile-item">
                    <label>Status</label>
                    <select class="form-input-styled" x-model="editingUser.status">
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                </div>
                <div class="profile-item">
                    <label>Profile Picture URL (Optional)</label>
                    <input type="text" class="form-input-styled" x-model="editingUser.profilePic" placeholder="https://example.com/pic.jpg">
                </div>
            </div>

            <div class="profile-item" style="margin-bottom: 15px;">
                <label>Default Shipping Address</label>
                <input type="text" class="form-input-styled" x-model="editingUser.address" placeholder="Address Details">
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
                <button @click="showEditModal = false" style="background:#f1f5f9; color:#475569; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor:pointer;">Discard</button>
                <button @click="saveUser()" style="background:var(--primary-blue); color:white; border:none; padding: 10px 30px; border-radius: 8px; cursor:pointer; font-weight:800;">Save Records</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userApp', () => ({
            searchQuery: '',
            statusFilter: '',
            currentTime: '',
            showEditModal: false,
            showHistoryModal: false,
            editingIndex: null,
            editingUser: {},
            
            selectedUser: { 
                id: 1,
                name: 'Eyra Mendoza', 
                email: 'eyra@example.com',
                role: 'Customer',
                status: 'ACTIVE',
                phone: '0917-123-4567',
                gender: 'Female',
                birthdate: 'Oct 12, 1995',
                address: '123 Rizal Street, Quezon City',
                regDate: 'Jan 15, 2024',
                lastLogin: 'May 20, 2026',
                cancelledCount: 1,
                profilePic: '', 
                orders: [
                    { id: 'ORD-9021', items: 'Premium Cotton Shirt (x2)', date: 'May 10, 2026', amount: '1,200.00', status: 'Completed' },
                    { id: 'ORD-8812', items: 'Denim Jacket', date: 'Apr 24, 2026', amount: '1,850.00', status: 'Completed' },
                    { id: 'ORD-7540', items: 'Canvas Tote Bag', date: 'Mar 12, 2026', amount: '350.00', status: 'Cancelled' }
                ]
            },
            
            users: @json($users ?? []),

            init() {
                if (this.users.length === 0) {
                    this.users = [JSON.parse(JSON.stringify(this.selectedUser))];
                }
            },

            startClock() {
                const update = () => {
                    const now = new Date();
                    const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                    const month = months[now.getMonth()];
                    const day = now.getDate();
                    const year = now.getFullYear();
                    
                    let hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    
                    this.currentTime = `${month} ${day}, ${year} - ${hours}:${minutes} ${ampm}`;
                };
                update();
                setInterval(update, 1000);
            },

            toggleStatusFilter() {
                if (this.statusFilter === '') this.statusFilter = 'ACTIVE';
                else if (this.statusFilter === 'ACTIVE') this.statusFilter = 'INACTIVE';
                else this.statusFilter = '';
            },

            calculateTotalSpend(user) {
                if (!user.orders || !Array.isArray(user.orders) || user.orders.length === 0) return '0.00';
                const total = user.orders
                    .filter(o => o.status === 'Completed')
                    .reduce((sum, o) => sum + parseFloat(String(o.amount).replace(/,/g, '')), 0);
                return total.toLocaleString(undefined, {minimumFractionDigits: 2});
            },

            calculateReliability(user) {
                if (!user.orders || !Array.isArray(user.orders) || user.orders.length === 0) return 100;
                const completed = user.orders.filter(o => o.status === 'Completed').length;
                return Math.round((completed / user.orders.length) * 100);
            },

            get filteredUsers() {
                let q = this.searchQuery.toLowerCase();
                return this.users.filter(u => {
                    const matchesSearch = (u.name || '').toLowerCase().includes(q) || 
                                          (u.email || '').toLowerCase().includes(q);
                    const matchesStatus = this.statusFilter === '' || u.status === this.statusFilter;
                    return matchesSearch && matchesStatus;
                });
            },

            viewHistory(user) {
                this.selectedUser = user;
                this.showHistoryModal = true;
                this.showEditModal = false;
            },

            addUser() {
                this.editingIndex = -1;
                this.editingUser = { 
                    id: null, name: '', email: '', role: 'Customer', 
                    status: 'ACTIVE', phone: '', address: '', profilePic: '', orders: [],
                    regDate: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                };
                this.showEditModal = true;
            },

            editUser(user) {
                this.editingIndex = this.users.indexOf(user);
                this.editingUser = JSON.parse(JSON.stringify(user));
                this.showEditModal = true;
            },

            async saveUser() {
                try {
                    const response = await fetch('{{ route("admin.customers.save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.editingUser)
                    });
                    const data = await response.json();
                    if (data.success) {
                        if (this.editingIndex === -1) {
                            this.users.unshift(data.user);
                        } else {
                            this.users[this.editingIndex] = data.user;
                        }
                        this.showEditModal = false;
                    }
                } catch (error) {
                    // Fallback para sa localized testing o kung walang real server endpoint
                    if (this.editingIndex === -1) {
                        this.editingUser.id = Date.now();
                        this.users.unshift(JSON.parse(JSON.stringify(this.editingUser)));
                    } else {
                        this.users[this.editingIndex] = JSON.parse(JSON.stringify(this.editingUser));
                    }
                    this.showEditModal = false;
                }
            },

            async deleteUser(user) {
                if(!confirm('Delete this customer?')) return;
                try {
                    const response = await fetch(`/admin/customers/delete/${user.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.users = this.users.filter(u => u.id !== user.id);
                    }
                } catch (error) {
                    this.users = this.users.filter(u => u.id !== user.id);
                }
            }
        }));
    });
</script>