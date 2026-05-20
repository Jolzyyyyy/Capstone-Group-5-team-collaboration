<style>
    :root {
        --primary-blue: #4F46E5;
        --border-color: #cbd5e1;
        --bg-light: #f4f7fa;
        --success-green: #10b981;
        --accent-red: #ef4444;
        --data-light-gray: #94a3b8; 
    }

    /* Maintain exact font styles from your original code */
    .main-wrapper {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .giant-title { 
        font-family: 'DM Serif Display', serif;
        font-size: 38px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        margin-bottom: 30px;
        color: #1e293b;
    }

    /* SUMMARY CARDS */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 280px);
        gap: 15px;
        margin-bottom: 25px;
    }

    .summary-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .summary-label { 
        font-size: 10px; 
        font-weight: 800; 
        color: var(--accent-red); 
        text-transform: uppercase; 
        letter-spacing: 0.1em;
        margin-bottom: 4px;
    }

    .summary-value { 
        font-family: 'DM Serif Display', serif;
        font-size: 24px; 
        color: #1a202c; 
    }

    /* BOX CONTAINERS */
    .box-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 15px;
        overflow: hidden;
    }

    .filter-header-content {
        padding: 12px 20px; 
        display: flex;
        justify-content: space-between;
        align-items: center; 
    }

    .section-title { 
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        font-style: italic;
        color: #1e293b;
        margin: 0; 
    }

    .action-bar {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-wrapper { position: relative; display: flex; align-items: center; }
    .search-wrapper input {
        padding: 6px 12px 6px 35px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        font-size: 13px;
        width: 200px;
        background: #f8fafc;
        outline: none;
    }
    .search-wrapper svg { position: absolute; left: 10px; color: #94a3b8; }

    .btn-export {
        background-color: var(--primary-blue);
        color: white; 
        font-weight: 700; 
        font-size: 12px;
        padding: 7px 14px; 
        border-radius: 6px; 
        border: none; 
        cursor: pointer;
    }

    /* TABLE STYLING */
    .main-table { width: 100%; border-collapse: collapse; }
    
    .main-table th {
        font-size: 14px; 
        font-weight: 800; 
        color: #475569; 
        text-transform: uppercase;
        padding: 15px 20px;
        text-align: left;
        background-color: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .main-table td {
        padding: 12px 20px;
        font-size: 15px; 
        color: var(--data-light-gray); 
        border-bottom: 1px solid var(--border-color);
    }

    .text-bold { font-weight: 600; color: var(--data-light-gray); }
    .text-id { color: var(--primary-blue); font-weight: 700; }

    .status-paid {
        color: var(--success-green);
        font-weight: 900;
        font-size: 12px;
        text-transform: uppercase;
    }
</style>

<div class="main-wrapper" x-data="salesApp">
    <h1 class="giant-title">Sales Reports</h1>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Gross Sales</div>
            <div class="summary-value" x-text="'₱' + totalRevenue.toLocaleString()"></div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Orders</div>
            <div class="summary-value" x-text="salesData.length"></div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Avg. Order Value</div>
            <div class="summary-value" x-text="'₱' + (totalRevenue / (salesData.length || 1)).toFixed(2)"></div>
        </div>
    </div>

    <div class="box-container">
        <div class="filter-header-content">
            <div class="section-title">Transactions</div>
            
            <div class="action-bar">
                <div class="search-wrapper">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search..." x-model="searchQuery">
                </div>
                <button class="btn-export" @click="alert('Exporting...')">Export CSV</button>
            </div>
        </div>
    </div>

    <div class="box-container">
        <div class="table-wrapper">
            <table class="main-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(sale, index) in filteredSales" :key="index">
                        <tr>
                            <td class="text-id" x-text="sale.id"></td>
                            <td x-text="sale.date"></td>
                            <td class="text-bold" x-text="sale.customer"></td>
                            <td x-text="sale.service"></td>
                            <td class="text-bold" x-text="'₱' + parseFloat(sale.amount).toFixed(2)"></td>
                            <td><span class="status-paid">PAID</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // Para hindi mag-duplicate ang initialization kung nasa SPA ka
        if (!Alpine.store('salesStore')) {
            Alpine.data('salesApp', () => ({
                searchQuery: '',
                salesData: [
                    { id: '#ORD-1001', date: '2024-03-20', customer: 'Juan Dela Cruz', service: 'Document Printing', amount: 45.00 },
                    { id: '#ORD-1002', date: '2024-03-20', customer: 'Maria Clara', service: 'ID Photo Services', amount: 150.00 },
                    { id: '#ORD-1003', date: '2024-03-19', customer: 'Jose Rizal', service: 'Lamination', amount: 500.00 },
                    { id: '#ORD-1004', date: '2024-03-19', customer: 'Andres Bonifacio', service: 'Photocopying', amount: 12.00 },
                    { id: '#ORD-1005', date: '2024-03-18', customer: 'Emilio Aguinaldo', service: 'Large Format Print', amount: 850.00 }
                ],
                get totalRevenue() {
                    return this.salesData.reduce((acc, curr) => acc + curr.amount, 0);
                },
                get filteredSales() {
                    return this.salesData.filter(s => 
                        s.customer.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        s.id.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                }
            }));
            Alpine.store('salesStore', true);
        }
    });
</script>