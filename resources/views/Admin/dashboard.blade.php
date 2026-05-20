<x-app-layout>
    @php
        $portalUser = $portalUser ?? auth()->user();
        $headerAuditLogs = isset($recentAuditLogs)
            ? $recentAuditLogs
            : \App\Models\AuditLog::with(['actor', 'targetUser'])->latest()->limit(8)->get();
        $headerNotifications = $headerAuditLogs->map(fn ($log) => [
            'title' => \Illuminate\Support\Str::headline($log->event ?? 'System update'),
            'body' => trim(($log->actor?->name ?? 'System') . ($log->targetUser ? ' updated ' . $log->targetUser->name : ' activity recorded')),
            'time' => optional($log->created_at)->diffForHumans() ?? 'Just now',
        ])->values();
        if ($headerNotifications->isEmpty()) {
            $headerNotifications = collect([
                ['title' => 'Dashboard ready', 'body' => 'Admin workspace is ready for today.', 'time' => 'Now'],
                ['title' => 'System status', 'body' => 'No critical alerts detected.', 'time' => 'Now'],
            ]);
        }
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        
        :root {
            --sidebar-width: 260px;
            --sidebar-closed-width: 85px;
            --primary-purple: #6366F1;
            --bg-light: #F8FAFC;
            --header-height: 230px; 
            --card-radius: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --yellow-text: #EAB308;
            --green-card: #10B981;
            --blue-card: #3B82F6;
            --yellow-card: #F59E0B;
            --pink-card: #EC4899;
            --action-gradient: linear-gradient(135deg, #60A5FA, #E879F9);
            --action-green: #4ADE80;
            --action-yellow: #FBBF24;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            margin: 0; color: #1E293B;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: var(--sidebar-width); background: #FFFFFF;
            border-right: 1px solid #E2E8F0; z-index: 1000;
            display: flex; flex-direction: column;
            transition: var(--transition);
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }
        .sidebar.closed { width: var(--sidebar-closed-width); }
        
        .sidebar-header {
            padding: 1.5rem; display: flex; align-items: center; gap: 15px;
            border-bottom: 1px solid #F1F5F9;
        }
        .menu-toggle {
            cursor: pointer; padding: 10px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: 0.2s; color: #64748B; background: #F8FAFC;
        }
        .menu-toggle:hover { background: #E2E8F0; color: var(--primary-purple); }
        .brand-name { font-weight: 800; font-size: 1.3rem; color: #0F172A; font-style: italic; letter-spacing: -1px; white-space: nowrap; }
        
        .nav-menu { flex: 1; padding: 14px 14px; overflow-y: auto; overflow-x: hidden; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 16px; margin-bottom: 5px;
            border-radius: 12px; color: #64748B;
            text-decoration: none; font-weight: 700; 
            font-size: 10px; text-transform: uppercase;
            transition: background-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
            cursor: pointer; position: relative;
            white-space: nowrap;
        }
        
        .sidebar-link:hover {
            background: #E0E7FF;
            color: #4F46E5;
            box-shadow: 0 8px 18px rgba(79, 70, 229, 0.12);
            transform: translateX(2px);
        }
        .sidebar-link.active {
            background: #EEF2FF; color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }
        .sidebar-link.active::before {
            content: ''; position: absolute; left: 0; top: 25%; bottom: 25%;
            width: 4px; background: var(--primary-purple); border-radius: 0 4px 4px 0;
        }

        .nav-text { transition: opacity 0.2s; }

        /* --- MAIN WRAPPER --- */
        .admin-main-shell { 
            margin-left: var(--sidebar-width); 
            transition: var(--transition); 
        }
        .admin-main-shell.expanded { margin-left: var(--sidebar-closed-width); }

        /* --- HERO HEADER --- */
        .hero-banner {
            height: var(--header-height);
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.7)), url('https://images.unsplash.com/photo-1562654501-a0ccc0fc3fb1?q=80&w=2000');
            background-size: cover; background-position: center;
            padding: 5px 60px;
            color: white; position: relative;
            display: flex; flex-direction: column; justify-content: space-between;
        }

        .top-nav { display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 15px; }
        .header-icon-no-box { 
            position: relative; cursor: pointer; width: 40px; height: 40px; 
            display: flex; align-items: center; justify-content: center; 
            padding: 0;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 12px;
            font: inherit;
            transition: color 0.2s, background-color 0.2s, border-color 0.2s, transform 0.2s;
            color: rgba(255,255,255,0.7);
        }
        .header-icon-no-box:hover, .header-icon-no-box.is-active {
            color: white;
            background: rgba(99, 102, 241, 0.34);
            border-color: rgba(255,255,255,0.18);
            transform: translateY(-1px);
        }
        .red-dot { position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #EF4444; border-radius: 50%; border: 2px solid #000; }
        
        .profile-area { display: flex; align-items: center; gap: 12px; background: rgba(0,0,0,0.3); padding: 5px 15px 5px 5px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); }
        .profile-pic { width: 34px; height: 34px; border-radius: 50%; border: 2px solid white; position: relative; }
        .green-dot { position: absolute; bottom: -2px; right: -2px; width: 10px; height: 10px; background: #10B981; border-radius: 50%; border: 2px solid #1e293b; }

        .hero-title-area { display: flex; flex-direction: column; justify-content: center; flex-grow: 1; margin-top: 40px; }
        .hero-main-title { font-size: 3.2rem; font-weight: 900; color: var(--yellow-text); margin: 0; letter-spacing: -2px; text-shadow: 0 4px 12px rgba(0,0,0,0.4); }

        .quick-actions-container { position: absolute; right: 60px; bottom: 35px; display: flex; align-items: center; gap: 30px; }
        .action-circle-group { display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer; }
        .action-circle {
            width: 55px; height: 55px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; transition: background 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .action-label { font-size: 10px; font-weight: 800; color: #E2E8F0; text-transform: capitalize; letter-spacing: 0.5px; }
        .circle-purple { background: var(--action-gradient); }
        .circle-green { background: var(--action-green); }
        .circle-yellow { background: var(--action-yellow); }

        .content-container { padding: 40px 100px; }
        .overview-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; margin-bottom: 30px; }

        .visual-card {
            background: white; border-radius: 12px; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); position: relative;
            overflow: hidden; display: flex; flex-direction: column;
            transition: box-shadow 0.2s ease, border-color 0.2s ease, transform 0.2s ease; cursor: pointer;
            min-height: 290px;
        }
        .visual-card:hover { border-color: #CBD5E1; box-shadow: 0 14px 25px -10px rgba(15,23,42,0.2); transform: translateY(-2px); }
        .visual-card-body { padding: 24px; flex: 1; min-height: 210px; }
        .visual-card-title { font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: block; }
        
        .donut-wrapper { position: relative; width: 110px; height: 110px; }
        .donut-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 14px; color: #1e293b; }
        
        .bar-chart-container { display: flex; align-items: flex-end; gap: 10px; height: 80px; margin-top: 20px; }
        .bar-item { width: 100%; background: #CBD5E1; border-radius: 4px; position: relative; }

        .visual-footer {
            position: relative;
            padding: 0 26px;
            height: 56px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            overflow: hidden;
            border-radius: 0 0 12px 12px;
        }
        .visual-footer::after {
            content: '';
            position: absolute;
            right: -2px;
            bottom: -1px;
            width: 64px;
            height: 31px;
            background: white;
            border-radius: 28px 0 0 0;
            opacity: 0.94;
        }
        .visual-footer::before {
            content: '';
            position: absolute;
            right: 42px;
            bottom: 0;
            width: 30px;
            height: 30px;
            background: inherit;
            border-bottom-right-radius: 28px;
            box-shadow: 12px 13px 0 white;
        }
        .visual-footer i, .visual-footer svg { position: relative; z-index: 1; }
        .visual-footer span { position: relative; z-index: 1; }

        .table-section { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .table-header-flex { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .table-controls { display: flex; align-items: center; gap: 12px; }
        .btn-filter { display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; border: 1px solid #E2E8F0; background: white; color: #64748B; font-weight: 700; font-size: 13px; cursor: pointer; height: 45px; }
        .btn-export { background: var(--primary-purple); color: white; border: none; padding: 10px 25px; border-radius: 12px; font-weight: 700; font-size: 13px; cursor: pointer; height: 45px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }

        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th { text-align: left; padding: 16px 18px; font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #E2E8F0; background: #F8FAFC; }
        .custom-table td { padding: 16px 18px; font-size: 14px; border-bottom: 1px solid #E2E8F0; }
        
        .status-pill { padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
        .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .status-shipped { background: #DCFCE7; color: #15803D; }
        .status-shipped::before { background: #15803D; }
        .status-pending { background: #FEF3C7; color: #B45309; }
        .status-pending::before { background: #B45309; }

        .action-dots { width: 38px; height: 38px; border-radius: 10px; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; background: white; color: #94A3B8; cursor: pointer; }

        .admin-section-content { padding: 40px 100px; }
        .admin-section-content > .main-wrapper,
        .admin-section-content > .analytics-section,
        .admin-section-content > .settings-section {
            width: 100% !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }
        .admin-section-content .main-wrapper,
        .admin-section-content .analytics-section,
        .admin-section-content .settings-section {
            width: 100% !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }
        .admin-section-content > .settings-section,
        .admin-section-content .settings-section { padding-top: 0 !important; }
        .admin-section-content .top-header { margin: 0 0 12px 0 !important; }
        .admin-section-content .giant-title,
        .admin-section-content .brand-font {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 32px !important;
            font-weight: 900 !important;
            letter-spacing: -1px !important;
            text-transform: none !important;
            margin: 0 0 35px 0 !important;
            color: #1E293B !important;
            line-height: 1.2 !important;
        }
        .admin-section-content .box-container,
        .admin-section-content .summary-card,
        .admin-section-content .setting-card,
        .admin-section-content .glass-card {
            border-radius: 12px !important;
            border: 1px solid #CBD5E1 !important;
            box-shadow: 0 4px 6px -1px rgba(15,23,42,0.04) !important;
        }
        .admin-section-content .main-table,
        .admin-section-content .custom-table,
        .admin-section-content .modern-items-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            table-layout: auto !important;
        }
        .admin-section-content .main-table th,
        .admin-section-content .custom-table th,
        .admin-section-content .modern-items-table th {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            color: #475569 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            padding: 14px 18px !important;
            background: #F8FAFC !important;
            border-bottom: 1px solid #E2E8F0 !important;
            text-align: left !important;
            white-space: nowrap !important;
        }
        .admin-section-content .main-table td,
        .admin-section-content .custom-table td,
        .admin-section-content .modern-items-table td {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 14px !important;
            color: #1E293B !important;
            padding: 14px 18px !important;
            border-bottom: 1px solid #E2E8F0 !important;
            vertical-align: middle !important;
        }
        .custom-table tbody tr:hover,
        .admin-section-content .main-table tbody tr:hover,
        .admin-section-content .custom-table tbody tr:hover,
        .admin-section-content .modern-items-table tbody tr:hover {
            background: #F8FAFC !important;
        }
        .admin-section-content .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 14px !important;
            font-weight: 900 !important;
            color: #1E293B !important;
        }
        .admin-section-content *,
        .sidebar *,
        .admin-main-shell,
        .hero-banner {
            transition-property: background-color, color, border-color, box-shadow, opacity !important;
        }

        .header-search-wrap { display: flex; align-items: center; position: relative; }
        .header-search-box {
            width: 270px;
            border: 1px solid rgba(255,255,255,0.18);
            background: rgba(15,23,42,0.72);
            color: white;
            border-radius: 999px;
            padding: 10px 44px 10px 16px;
            font-size: 12px;
            font-weight: 700;
            outline: none;
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
        }
        .header-search-submit {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: transparent;
            color: rgba(255,255,255,0.78);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }
        .header-search-submit:hover { background: rgba(99,102,241,0.65); color: white; }
        .header-search-box::placeholder { color: rgba(255,255,255,0.62); }
        .header-tool-panel {
            position: absolute;
            top: 52px;
            right: 0;
            width: 320px;
            max-height: 340px;
            overflow-y: auto;
            background: white;
            color: #1E293B;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(15,23,42,0.18);
            padding: 12px;
            z-index: 1200;
        }
        .tool-panel-title { font-size: 11px; font-weight: 900; color: #64748B; text-transform: uppercase; letter-spacing: 1px; padding: 8px 8px 10px; }
        .notification-item, .search-result-item {
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #F1F5F9;
            margin-bottom: 8px;
            background: #F8FAFC;
            cursor: pointer;
            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
        }
        .notification-item:hover, .search-result-item:hover {
            background: #EEF2FF;
            border-color: #C7D2FE;
            transform: translateY(-1px);
        }
        .notification-title, .search-result-title { font-size: 13px; font-weight: 900; color: #1E293B; }
        .notification-body, .search-result-meta { font-size: 11px; color: #64748B; margin-top: 4px; line-height: 1.35; }
        .notification-time { font-size: 10px; font-weight: 800; color: var(--primary-purple); margin-top: 6px; }
        .chat-drawer {
            position: fixed;
            right: 28px;
            bottom: 28px;
            width: 360px;
            height: 500px;
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.22);
            z-index: 1600;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-head { background: #111827; color: white; padding: 14px 16px; display:flex; align-items:center; justify-content:space-between; }
        .chat-thread-select { padding: 10px 12px; border-bottom: 1px solid #E2E8F0; background: white; }
        .chat-thread-select select { width: 100%; border: 1px solid #CBD5E1; border-radius: 10px; padding: 9px 10px; font-size: 12px; font-weight: 800; color: #1E293B; outline: none; background: #F8FAFC; }
        .chat-tools { display: flex; align-items: center; gap: 8px; padding: 10px 12px; border-bottom: 1px solid #E2E8F0; background: white; }
        .chat-tools input { flex: 1; border: 1px solid #E2E8F0; border-radius: 999px; padding: 9px 12px; font-size: 11px; outline: none; }
        .chat-chip { border: 1px solid #E2E8F0; background: #F8FAFC; border-radius: 999px; padding: 8px 10px; font-size: 10px; font-weight: 800; cursor: pointer; color: #475569; }
        .chat-chip:hover { background: #EEF2FF; border-color: #C7D2FE; color: #4F46E5; }
        .chat-title { font-size: 13px; font-weight: 900; }
        .chat-status { font-size: 10px; color: #34D399; font-weight: 800; }
        .chat-body { flex: 1; padding: 14px; overflow-y: auto; background: #F8FAFC; }
        .chat-message { max-width: 82%; padding: 10px 12px; border-radius: 14px; margin-bottom: 10px; font-size: 12px; line-height: 1.4; }
        .chat-message.customer { background: white; color: #1E293B; border: 1px solid #E2E8F0; }
        .chat-message.me { margin-left: auto; background: var(--primary-purple); color: white; border-bottom-right-radius: 5px; }
        .chat-input-row { padding: 12px; display:flex; gap:8px; border-top:1px solid #E2E8F0; }
        .chat-input-row input { flex:1; border:1px solid #CBD5E1; border-radius:999px; padding:10px 12px; font-size:12px; outline:none; }
        .chat-input-row button { width:40px; height:40px; border-radius:50%; border:none; background:var(--primary-purple); color:white; display:flex; align-items:center; justify-content:center; cursor:pointer; }
        .chat-input-row button:hover { background:#4F46E5; }

        .detail-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.8); z-index: 2000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); }
        .modal-card { background: white; width: 450px; border-radius: 28px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes modalPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ 
        sidebarOpen: true, 
        showDetail: false,
        searchOpen: false,
        notificationOpen: false,
        chatOpen: false,
        searchTerm: '',
        chatDraft: '',
        chatSearch: '',
        activeThreadId: 1,
        notificationsRead: false,
        notifications: @js($headerNotifications),
        chatThreads: [
            {
                id: 1,
                customer: 'Maria Santos',
                subject: 'Order pickup inquiry',
                messages: [
                    { from: 'customer', text: 'Hello po, ready na po ba for pickup yung order ko?', time: 'Now' }
                ]
            },
            {
                id: 2,
                customer: 'Juan Dela Cruz',
                subject: 'Print quotation',
                messages: [
                    { from: 'customer', text: 'Magkano po ang 100 pcs flyers, full color?', time: 'Now' }
                ]
            },
            {
                id: 3,
                customer: 'Aly Reyes',
                subject: 'File upload concern',
                messages: [
                    { from: 'customer', text: 'Hindi po ma-upload yung PDF ko. Pwede po pa-check?', time: 'Now' }
                ]
            }
        ],
        init() {
            const savedThreads = window.localStorage.getItem('printifyCustomerInquiryThreads');
            if (savedThreads) {
                try { this.chatThreads = JSON.parse(savedThreads); } catch (error) {}
            }
        },
        searchItems: [
            { title: 'Dashboard', meta: 'Overview and recent transactions', url: '{{ route('admin.dashboard') }}' },
            { title: 'Customer/User', meta: 'Manage registered users', url: '{{ route('admin.customers') }}' },
            { title: 'Orders', meta: 'Order management and status tracking', url: '{{ route('admin.orders') }}' },
            { title: 'Products', meta: 'Product inventory and services', url: '{{ route('admin.products') }}' },
            { title: 'Reports', meta: 'Sales reports and export tools', url: '{{ route('admin.reports') }}' },
            { title: 'Analytics', meta: 'Traffic and performance charts', url: '{{ route('admin.analytics') }}' },
            { title: 'Settings', meta: 'Preferences and system controls', url: '{{ route('admin.settings') }}' }
        ],
        modalTitle: '', modalData: '', modalColor: '',
        get activeThread() {
            return this.chatThreads.find(thread => thread.id == this.activeThreadId) || this.chatThreads[0];
        },
        get filteredSearchItems() {
            if (!this.searchTerm.trim()) return this.searchItems;
            const term = this.searchTerm.toLowerCase();
            return this.searchItems.filter(item => (item.title + ' ' + item.meta).toLowerCase().includes(term));
        },
        get filteredChatMessages() {
            const messages = this.activeThread ? this.activeThread.messages : [];
            if (!this.chatSearch.trim()) return messages;
            const term = this.chatSearch.toLowerCase();
            return messages.filter(message => message.text.toLowerCase().includes(term));
        },
        submitSearch() {
            const firstResult = this.filteredSearchItems[0];
            if (firstResult) window.location.href = firstResult.url;
        },
        openNotifications() {
            this.notificationOpen = !this.notificationOpen;
            this.searchOpen = false;
            if (this.notificationOpen) this.notificationsRead = true;
        },
        addQuickReply(text) {
            this.chatDraft = text;
            this.sendChatMessage();
        },
        clearChat() {
            if (!this.activeThread) return;
            this.activeThread.messages = [
                { from: 'customer', text: 'Conversation cleared. Waiting for the next customer inquiry.', time: 'Now' }
            ];
            window.localStorage.setItem('printifyCustomerInquiryThreads', JSON.stringify(this.chatThreads));
        },
        sendChatMessage() {
            if (!this.chatDraft.trim() || !this.activeThread) return;
            this.activeThread.messages.push({ from: 'me', text: this.chatDraft.trim(), time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) });
            this.chatDraft = '';
            window.localStorage.setItem('printifyCustomerInquiryThreads', JSON.stringify(this.chatThreads));
        },
        openModal(title, info, color) {
            this.modalTitle = title; this.modalData = info;
            this.modalColor = color; this.showDetail = true;
        }
    }">
        
        <!-- SIDEBAR -->
        <aside class="sidebar" :class="!sidebarOpen ? 'closed' : ''">
            <div class="sidebar-header">
                <div class="menu-toggle" @click="sidebarOpen = !sidebarOpen">
                    <i data-lucide="menu"></i>
                </div>
                <span class="brand-name" x-show="sidebarOpen" x-transition>Printify Co.</span>
            </div>
            
            <nav class="nav-menu">
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i data-lucide="external-link"></i> 
                    <span class="nav-text" x-show="sidebarOpen" x-transition>Go to Home</span>
                </a>
                <div class="my-4 border-t border-slate-50 mx-2" style="border-top: 1px solid #F1F5F9; margin: 15px 0;"></div>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ in_array($section, ['dashboard', 'developer-dashboard', 'admin-client-dashboard'], true) ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> 
                    <span class="nav-text" x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>

                @if(isset($portalUser) && $portalUser->isDeveloper())
                    <a href="{{ route('developer.admin-clients.index') }}" class="sidebar-link">
                        <i data-lucide="shield-check"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Manage Admin Clients</span>
                    </a>
                    <a href="{{ route('developer.orders.index') }}" class="sidebar-link">
                        <i data-lucide="shopping-cart"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Orders</span>
                    </a>
                    <a href="{{ route('developer.services.index') }}" class="sidebar-link">
                        <i data-lucide="package"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Services</span>
                    </a>
                    <a href="{{ route('developer.customers.index') }}" class="sidebar-link">
                        <i data-lucide="users"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Customers</span>
                    </a>
                    <a href="{{ route('developer.reports.index') }}" class="sidebar-link">
                        <i data-lucide="file-text"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Reports</span>
                    </a>
                    <a href="{{ route('developer.analytics.index') }}" class="sidebar-link">
                        <i data-lucide="bar-chart-3"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Analytics</span>
                    </a>
                    <a href="{{ route('developer.settings.index') }}" class="sidebar-link">
                        <i data-lucide="settings"></i>
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Settings</span>
                    </a>
                @else
                    <a href="{{ route('admin.customers') }}" class="sidebar-link {{ $section == 'customers' ? 'active' : '' }}">
                        <i data-lucide="users"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Customer/User</span>
                    </a>
                    <a href="{{ route('admin.orders') }}" class="sidebar-link {{ $section == 'orders' ? 'active' : '' }}">
                        <i data-lucide="shopping-cart"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Orders</span>
                    </a>
                    <a href="{{ route('admin.products') }}" class="sidebar-link {{ $section == 'products' ? 'active' : '' }}">
                        <i data-lucide="package"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Products</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="sidebar-link {{ $section == 'analytics' ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Analytics</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="sidebar-link {{ $section == 'reports' ? 'active' : '' }}">
                        <i data-lucide="file-text"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Reports</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ $section == 'settings' ? 'active' : '' }}">
                        <i data-lucide="settings"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Settings</span>
                    </a>
                    <a href="{{ route('admin.helpcenter') }}" class="sidebar-link {{ $section == 'help center' ? 'active' : '' }}">
                        <i data-lucide="help-circle"></i> 
                        <span class="nav-text" x-show="sidebarOpen" x-transition>Help Center</span>
                    </a>
                @endif
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" style="padding: 20px; border-top: 1px solid #F1F5F9;">
                @csrf
                <button type="submit" class="sidebar-link" style="color: #EF4444; background: #FEF2F2; width: 100%; border: none;">
                    <i data-lucide="log-out"></i> <span class="nav-text" x-show="sidebarOpen" x-transition>Log Out</span>
                </button>
            </form>
        </aside>

        <div class="admin-main-shell" :class="!sidebarOpen ? 'expanded' : ''">
                <header class="hero-banner">
                    <div class="top-nav">
                        <div class="header-search-wrap" @click.outside="searchOpen = false">
                            <form x-show="searchOpen" x-transition @submit.prevent="submitSearch()" style="position:relative;">
                                <input class="header-search-box" x-model="searchTerm" type="search" placeholder="Search admin sections..." @keydown.escape="searchOpen = false">
                                <button type="submit" class="header-search-submit" title="Search">
                                    <i data-lucide="search" style="width:17px"></i>
                                </button>
                            </form>
                            <button x-show="!searchOpen" type="button" class="header-icon-no-box" @click="searchOpen = true; notificationOpen = false; setTimeout(() => $el.previousElementSibling.querySelector('input').focus(), 80)" title="Search">
                                <i data-lucide="search" style="width:20px"></i>
                            </button>
                            <div class="header-tool-panel" x-show="searchOpen && searchTerm.length >= 0" x-transition x-cloak>
                                <div class="tool-panel-title">Search Results</div>
                                <template x-for="item in filteredSearchItems" :key="item.title">
                                    <a :href="item.url" class="search-result-item" style="display:block; text-decoration:none;">
                                        <div class="search-result-title" x-text="item.title"></div>
                                        <div class="search-result-meta" x-text="item.meta"></div>
                                    </a>
                                </template>
                            </div>
                        </div>
                        <div style="position:relative;" @click.outside="notificationOpen = false">
                            <button type="button" class="header-icon-no-box" :class="notificationOpen ? 'is-active' : ''" @click="openNotifications()" title="Notifications">
                                <i data-lucide="bell" style="width:20px"></i><div class="red-dot" x-show="!notificationsRead"></div>
                            </button>
                            <div class="header-tool-panel" x-show="notificationOpen" x-transition x-cloak>
                                <div class="tool-panel-title">Notifications</div>
                                <template x-for="note in notifications" :key="note.title + note.time">
                                    <div class="notification-item" @click="openModal(note.title, note.body, '#6366F1')">
                                        <div class="notification-title" x-text="note.title"></div>
                                        <div class="notification-body" x-text="note.body"></div>
                                        <div class="notification-time" x-text="note.time"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <button type="button" class="header-icon-no-box" :class="chatOpen ? 'is-active' : ''" @click="chatOpen = !chatOpen" title="Customer inquiries"><i data-lucide="mail" style="width:20px"></i><div class="red-dot"></div></button>
                        
                        <div class="profile-area">
                            <div class="profile-pic">
                                <img src="https://i.pravatar.cc/150?u=print" style="width:100%; border-radius:50%">
                                <div class="green-dot"></div>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; font-size: 10px; color: white;">ADMIN / DEVELOPER</span>
                                <span style="font-size: 9px; color: #10B981; font-weight: 700;">● ONLINE</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.15fr,0.85fr]">
                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-[#f0e5d8] px-5 py-4">
                        <div>
                            <p class="text-xs font-black uppercase text-[#ff8d2a]">Sales And Orders</p>
                            <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Orders</h2>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-[#b45309] underline">Open order database</a>
                    </div>

                    <div class="hero-title-area">
                        <p style="font-size: 16px; color: white; opacity: 0.8; font-weight: 600; margin-bottom: -5px;">Staff and Developer Portal</p>
                        <h1 class="hero-main-title">ADMIN DASHBOARD</h1>
                    </div>

                    <div class="quick-actions-container">
                        <div class="action-circle-group" @click="openModal('New Print Job', 'Initiating new printer workflow...', '#60A5FA')">
                            <div class="action-circle circle-purple"><i data-lucide="file-plus-2" style="width:24px"></i></div>
                            <span class="action-label">New Print Job</span>
                        </div>
                        <div class="action-circle-group" @click="openModal('System Status', 'All servers are running optimally.', '#4ADE80')">
                            <div class="action-circle circle-green"><i data-lucide="bar-chart-3" style="width:24px"></i></div>
                            <span class="action-label">System Status</span>
                        </div>
                        <div class="action-circle-group" @click="openModal('Printer Queue', '3 jobs currently in queue.', '#FBBF24')">
                            <div class="action-circle circle-yellow"><i data-lucide="layers" style="width:24px"></i></div>
                            <span class="action-label">Printer Queue</span>
                        </div>
                    </div>
                </header>

            @if($section == 'dashboard' || $section == 'developer-dashboard' || $section == 'admin-client-dashboard')
                <main class="content-container">
                    <div class="overview-header">
                        <h2 style="font-size:32px; font-weight:900; letter-spacing:-1px; margin:0">Overview <span style="color:var(--primary-purple)">.</span></h2>
                        <div style="background:white; padding:10px 20px; border-radius:12px; border:1px solid #e2e8f0; display:flex; align-items:center; gap:10px; font-size:13px; font-weight:700;">
                            <i data-lucide="calendar" style="width:16px; color:var(--primary-purple)"></i>
                            May 14, 2026 - Present
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="visual-card" @click="openModal('User Account Status', 'Active accounts: 3,150\nInactive accounts: 630\nOverall active rate: 75%\n\nUse this card to monitor customer account health without leaving the dashboard.', '#10B981')">
                            <div class="visual-card-body">
                                <span class="visual-card-title">Account Status</span>
                                <div style="display:flex; justify-content:center; margin-top:10px">
                                    <div class="donut-wrapper">
                                        <svg viewBox="0 0 36 36" style="transform: rotate(-90deg)">
                                            <circle cx="18" cy="18" r="16" fill="none" stroke="#F1F5F9" stroke-width="3.5"></circle>
                                            <circle cx="18" cy="18" r="16" fill="none" stroke="var(--green-card)" stroke-width="3.5" stroke-dasharray="75, 100" stroke-linecap="round"></circle>
                                        </svg>
                                        <div class="donut-text">75%</div>
                                    </div>
                                </div>
                                <div style="margin-top:20px; display:flex; justify-content:space-between; font-size:11px; font-weight:700">
                                    <span style="color:#64748B">Active</span>
                                    <span style="color:#1e293b">3,150</span>
                                </div>
                                <div style="width:100%; height:4px; background:#F1F5F9; border-radius:10px; margin-top:5px">
                                    <div style="width:75%; height:100%; background:var(--green-card); border-radius:10px"></div>
                                </div>
                            </div>
                            <div class="visual-footer" style="background: var(--green-card)"><span>User Accounts</span> <i data-lucide="arrow-right" style="width:14px"></i></div>
                        </div>

                        <div class="visual-card" @click="openModal('Total Orders', 'Total orders: 1,452\nWeekly growth: +12%\nRecent trend: increasing order activity\n\nThis summary helps you check order movement directly from the dashboard.', '#3B82F6')">
                            <div class="visual-card-body">
                                <span class="visual-card-title">Order Trends</span>
                                <div style="font-size:28px; font-weight:900; color:#1e293b">1,452 <span style="font-size:12px; color:#10B981">+12%</span></div>
                                <svg viewBox="0 0 100 40" style="margin-top:15px">
                                    <path d="M0 35 L 20 20 L 40 25 L 60 10 L 80 15 L 100 5" fill="none" stroke="var(--blue-card)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="visual-footer" style="background: var(--blue-card)"><span>Total Orders</span> <i data-lucide="arrow-right" style="width:14px"></i></div>
                        </div>

                        <div class="visual-card" @click="openModal('Product Inventory', 'Tracked catalog items: 754\nStrongest stock day: Wednesday\nInventory status: stable\n\nUse this popup for quick product and stock visibility.', '#F59E0B')">
                            <div class="visual-card-body">
                                <span class="visual-card-title">Stock Analysis</span>
                                <div class="bar-chart-container">
                                    <div class="bar-item" style="height: 60%"></div>
                                    <div class="bar-item" style="height: 40%"></div>
                                    <div class="bar-item" style="height: 95%; background:var(--yellow-card)"></div>
                                    <div class="bar-item" style="height: 65%"></div>
                                    <div class="bar-item" style="height: 30%"></div>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-top:10px; font-size:9px; font-weight:800; color:#94a3b8">
                                    <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span>
                                </div>
                            </div>
                            <div class="visual-footer" style="background: var(--yellow-card)"><span>Products</span> <i data-lucide="arrow-right" style="width:14px"></i></div>
                        </div>

                        <div class="visual-card" @click="openModal('Revenue Trend', 'Projected revenue stream: $42.8k\nTrend signal: positive movement\nBest action: monitor order completion and payment updates\n\nThis card previews revenue without opening the Analytics section.', '#EC4899')">
                            <div class="visual-card-body">
                                <span class="visual-card-title">Revenue Stream</span>
                                <div style="font-size:28px; font-weight:900; color:#1e293b">$42.8k</div>
                                <svg viewBox="0 0 100 40" style="margin-top:15px">
                                    <path d="M0 30 Q 10 35, 20 20 T 40 15 T 60 25 T 80 10 T 100 15" fill="none" stroke="var(--pink-card)" stroke-width="3" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="visual-footer" style="background: var(--pink-card)"><span>Revenue Trend</span> <i data-lucide="arrow-right" style="width:14px"></i></div>
                        </div>
                    </div>

                    <!-- RECENT TRANSACTIONS TABLE -->
                    <div class="table-section">
                        <div class="table-header-flex">
                            <div>
                                <h3 style="margin:0; font-size:24px; font-weight:900; color: #1E293B;">Recent Transactions</h3>
                                <p style="margin:8px 0 0 0; font-size:12px; color:#64748B; font-weight:500">Managing total of 1,240 records across the platform.</p>
                            </div>
                            <div class="table-controls">
                                <button class="btn-filter">
                                    <i data-lucide="filter" style="width:18px"></i> Filter
                                </button>
                                <button class="btn-export">Export Report</button>
                            </div>
                        </div>
                        
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Order ID</th>
                                    <th style="width: 20%">Customer Name</th>
                                    <th style="width: 20%">Product Item</th>
                                    <th style="width: 15%">Date Ordered</th>
                                    <th style="width: 12%">Total Amount</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="text-align:center; width: 8%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 700; color: #6366F1;">#ORD-3079101</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:12px">
                                            <div style="width:34px; height:34px; border-radius:8px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:11px; color:#6366F1">AH</div>
                                            <span style="font-weight: 700; color: #1E293B;">Amen Hanson</span>
                                        </div>
                                    </td>
                                    <td style="font-weight: 600; color: #475569;">Custom Apparel Mugs</td>
                                    <td style="color: #64748B; font-weight:600">Apr 17, 2026</td>
                                    <td style="font-weight: 800; color: #1E293B;">$120.00</td>
                                    <td><span class="status-pill status-shipped">Shipped</span></td>
                                    <td style="text-align:center">
                                        <button class="action-dots"><i data-lucide="more-horizontal" style="width:18px"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 700; color: #6366F1;">#ORD-3073002</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:12px">
                                            <div style="width:34px; height:34px; border-radius:8px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:11px; color:#6366F1">JS</div>
                                            <span style="font-weight: 700; color: #1E293B;">Jash Savrim</span>
                                        </div>
                                    </td>
                                    <td style="font-weight: 600; color: #475569;">Matte Black Mugs (2x)</td>
                                    <td style="color: #64748B; font-weight:600">Apr 16, 2026</td>
                                    <td style="font-weight: 800; color: #1E293B;">$45.50</td>
                                    <td><span class="status-pill status-pending">Pending</span></td>
                                    <td style="text-align:center">
                                        <button class="action-dots"><i data-lucide="more-horizontal" style="width:18px"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>

            @elseif($section == 'customers')
                <main class="admin-section-content">@include('Admin.sections.customers')</main>
            @elseif($section == 'orders')
                <main class="admin-section-content">@include('Admin.sections.orders')</main>
            @elseif($section == 'products')
                <main class="admin-section-content">@include('Admin.sections.products')</main>
            @elseif($section == 'rates')
                <main class="admin-section-content">@include('Admin.sections.rates')</main>
            @elseif($section == 'help center')
                <main class="admin-section-content">@include('Admin.sections.helpcenter')</main>
            @elseif($section == 'analytics')
                <main class="admin-section-content">@include('Admin.sections.analytics')</main>
            @elseif($section == 'reports')
                <main class="admin-section-content">@include('Admin.sections.reports')</main>
            @elseif($section == 'settings')
                <main class="admin-section-content">@include('Admin.sections.settings')</main>
            @endif
        </div>

        <div class="chat-drawer" x-show="chatOpen" x-transition x-cloak>
            <div class="chat-head">
                <div>
                    <div class="chat-title">Customer Inquiries</div>
                    <div class="chat-status" x-text="activeThread ? activeThread.customer + ' - ' + activeThread.subject : 'Online support'"></div>
                </div>
                <button @click="chatOpen = false" style="background:none;border:none;color:white;cursor:pointer;"><i data-lucide="x"></i></button>
            </div>
            <div class="chat-thread-select">
                <select x-model="activeThreadId">
                    <template x-for="thread in chatThreads" :key="thread.id">
                        <option :value="thread.id" x-text="thread.customer + ' - ' + thread.subject"></option>
                    </template>
                </select>
            </div>
            <div class="chat-tools">
                <input type="search" x-model="chatSearch" placeholder="Search messages...">
                <button class="chat-chip" type="button" @click="addQuickReply('Hi, this is Printify Co. admin support. I am checking your inquiry now.')">Reply</button>
                <button class="chat-chip" type="button" @click="clearChat()">Clear</button>
            </div>
            <div class="chat-body">
                <template x-for="(message, index) in filteredChatMessages" :key="index">
                    <div>
                        <div class="chat-message" :class="message.from === 'me' ? 'me' : 'customer'" x-text="message.text"></div>
                        <div :style="message.from === 'me' ? 'text-align:right' : 'text-align:left'" style="font-size:9px;color:#94A3B8;margin:-6px 4px 8px;" x-text="message.time || 'Now'"></div>
                    </div>
                </template>
            </div>
            <form class="chat-input-row" @submit.prevent="sendChatMessage()">
                <input type="text" x-model="chatDraft" placeholder="Reply to customer...">
                <button type="submit"><i data-lucide="send" style="width:18px"></i></button>
            </form>
        </div>

        <!-- MODAL -->
        <div class="detail-overlay" x-show="showDetail" x-transition.opacity x-cloak>
            <div class="modal-card" @click.away="showDetail = false">
                <div :style="'height: 12px; background:' + modalColor"></div>
                <div style="padding: 45px; text-align: center;">
                    <div :style="'width:70px; height:70px; border-radius:24px; background:' + modalColor + '15; color:' + modalColor + '; display:flex; align-items:center; justify-content:center; margin: 0 auto 25px auto;'">
                        <i data-lucide="activity" style="width:32px; height:32px"></i>
                    </div>
                    <h3 x-text="modalTitle" style="font-size: 26px; font-weight: 900; margin: 0 0 12px 0; color:#1e293b;"></h3>
                    <p x-text="modalData" style="color: #64748b; font-size: 15px; line-height:1.7; margin-bottom: 35px; font-weight:500; white-space:pre-line"></p>
                    <button @click="showDetail = false" :style="'width: 100%; padding: 18px; border-radius: 18px; border: none; background:' + modalColor + '; color:white; font-weight: 800; cursor: pointer;'">
                        Understood, Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => { 
            lucide.createIcons(); 
        });
        // Observer to re-run lucide when Alpine changes the DOM
        window.addEventListener('click', () => { 
            setTimeout(() => lucide.createIcons(), 50); 
        });
    </script>
</x-app-layout>

