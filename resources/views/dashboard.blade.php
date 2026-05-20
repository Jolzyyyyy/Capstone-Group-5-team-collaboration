<x-app-layout>
    <!-- Style specific for Dashboard elements if needed -->
    <style>
        .stat-card-modern {
            background: #FFFFFF; padding: 2.5rem; border-radius: 24px;
            border: 1px solid var(--border-color); position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.3s;
        }
        .stat-card-modern:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        
        .table-container {
            background: white; border-radius: 24px; border: 1px solid var(--border-color);
            overflow: hidden; margin-top: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        table { width: 100%; border-collapse: collapse; }
        th { background: #F8FAFC; padding: 1.5rem; text-align: left; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64748B; border-bottom: 1px solid var(--border-color); }
        td { padding: 1.5rem; font-size: 13px; color: #1E293B; border-bottom: 1px solid #F8FAFC; }
        .status-pill { padding: 6px 14px; border-radius: 12px; font-size: 9px; font-weight: 800; text-transform: uppercase; display: inline-block; }
        .status-pill.pending { background: #FFF7ED; color: #C2410C; }
        .status-pill.completed { background: #F0FDF4; color: #15803D; }
    </style>

    <div class="mb-12">
        <h1 class="text-5xl font-brand-italic text-slate-800 uppercase tracking-tighter">Dashboard Overview</h1>
        <p class="text-slate-400 text-xs font-bold mt-3 uppercase tracking-[0.3em]">Track your printing projects and activity.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Card 1 -->
        <div class="stat-card-modern">
            <div class="flex justify-between items-center mb-8">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl"><i data-lucide="package" size="28"></i></div>
                <i data-lucide="trending-up" class="text-slate-300"></i>
            </div>
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Active Orders</h3>
            <p class="text-4xl font-black text-slate-800 mt-2">04</p>
        </div>
        <!-- Card 2, Card 3, etc. follow the same pattern -->
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <!-- ... Paste your table code here ... -->
    </div>
</x-app-layout>
