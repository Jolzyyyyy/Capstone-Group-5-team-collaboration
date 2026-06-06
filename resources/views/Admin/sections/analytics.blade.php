@php
    use App\Models\Order;
    use App\Models\Service;
    use App\Models\User;

    $analyticsOrders = Order::query();
    $analyticsCustomers = User::where('role', \App\Models\User::ROLE_CUSTOMER);
    $analyticsServices = Service::query();
    $weekStart = now()->startOfWeek();
    $days = collect(range(0, 6))->map(fn ($day) => $weekStart->copy()->addDays($day));
    $weeklyOrders = $days->map(fn ($day) => (clone $analyticsOrders)->whereDate('created_at', $day)->count())->values();
    $weeklyCustomers = $days->map(fn ($day) => (clone $analyticsCustomers)->whereDate('created_at', $day)->count())->values();
    $weeklySales = $days->map(fn ($day) => (float) (clone $analyticsOrders)->whereDate('created_at', $day)->sum('total_price'))->values();
    $statusGroups = [
        'Pending' => (clone $analyticsOrders)->where('status', 'Pending')->count(),
        'Processing' => (clone $analyticsOrders)->whereIn('status', ['For Verification', 'Processing'])->count(),
        'Ready' => (clone $analyticsOrders)->where('status', 'Ready')->count(),
        'Completed' => (clone $analyticsOrders)->where('status', 'Completed')->count(),
    ];
    $analyticsSummary = [
        'customers' => (clone $analyticsCustomers)->count(),
        'orders' => (clone $analyticsOrders)->count(),
        'services' => (clone $analyticsServices)->count(),
        'sales' => (float) (clone $analyticsOrders)->sum('total_price'),
    ];
    $trafficGroups = [
        'Customers' => $analyticsSummary['customers'],
        'Orders' => $analyticsSummary['orders'],
        'Products' => $analyticsSummary['services'],
        'Completed' => $statusGroups['Completed'] ?? 0,
    ];
@endphp

<style>
    .analytics-section { display: flex; flex-direction: column; gap: 24px; }
    .analytics-summary-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }
    .analytics-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 10px 24px -18px rgba(15,23,42,0.35);
        padding: 22px;
        overflow: hidden;
    }
    .analytics-card:hover { border-color: #CBD5E1; box-shadow: 0 18px 36px -24px rgba(15,23,42,0.45); }
    .analytics-card-label { font-size: 11px; font-weight: 900; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.18em; margin: 0 0 10px; }
    .analytics-card-note { font-size: 12px; color: #64748B; margin-top: 8px; }
    .analytics-summary-value { font-size: 30px; font-weight: 900; color: #1E293B; line-height: 1; }
    .analytics-grid { display: grid; grid-template-columns: 1.45fr 1fr; gap: 24px; align-items: stretch; }
    .analytics-grid-lower { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; align-items: stretch; }
    .analytics-metric-stack { display: grid; gap: 20px; }
    .chart-box { display: flex; flex-direction: column; min-height: 0; }
    .chart-box .chart-canvas-shell {
        position: relative;
        height: 260px;
        width: 100%;
    }
    .chart-box.compact .chart-canvas-shell { height: 215px; }
    .chart-box canvas {
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
        max-height: 100%;
    }
    .chart-title { font-size: 18px; font-weight: 900; color: #1E293B; margin: 0 0 18px 0; }
    .metric-box { display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
    .metric-box strong { font-size: 34px; color: #1E293B; line-height: 1; }
    .metric-box.small { min-height: 116px; }
    .metric-box.small strong { font-size: 30px; }
    .mini-bars { display:flex; align-items:flex-end; gap:10px; height:70px; margin-top:18px; }
    .mini-bar { flex:1; border-radius:6px 6px 2px 2px; background:#CBD5E1; }
    .mini-sparkline { width:100%; height:76px; margin-top:12px; }
    @media (max-width: 1200px) {
        .analytics-summary-row, .analytics-grid, .analytics-grid-lower { grid-template-columns: 1fr; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="analytics-section">
    <h1 class="giant-title">Analytics</h1>

    <div class="analytics-summary-row">
        <div class="analytics-card">
            <p class="analytics-card-label">Customer Accounts</p>
            <div class="analytics-summary-value">{{ number_format($analyticsSummary['customers']) }}</div>
            <div class="analytics-card-note">Registered customer records</div>
        </div>
        <div class="analytics-card">
            <p class="analytics-card-label">Total Orders</p>
            <div class="analytics-summary-value">{{ number_format($analyticsSummary['orders']) }}</div>
            <svg class="mini-sparkline" viewBox="0 0 180 76" preserveAspectRatio="none">
                <path d="M0 58 L32 36 L64 46 L96 22 L132 34 L180 12" fill="none" stroke="#3B82F6" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="analytics-card">
            <p class="analytics-card-label">Product Inventory</p>
            <div class="analytics-summary-value">{{ number_format($analyticsSummary['services']) }}</div>
            <div class="mini-bars">
                <span class="mini-bar" style="height:48%"></span>
                <span class="mini-bar" style="height:68%; background:#F59E0B"></span>
                <span class="mini-bar" style="height:42%"></span>
                <span class="mini-bar" style="height:56%"></span>
            </div>
        </div>
        <div class="analytics-card">
            <p class="analytics-card-label">Revenue</p>
            <div class="analytics-summary-value">PHP {{ number_format($analyticsSummary['sales'], 2) }}</div>
            <svg class="mini-sparkline" viewBox="0 0 180 76" preserveAspectRatio="none">
                <path d="M0 54 C24 70 42 22 68 30 C96 38 104 60 132 40 C154 25 160 10 180 26" fill="none" stroke="#EC4899" stroke-width="5" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <div class="analytics-grid">
        <div class="analytics-card chart-box">
            <h4 class="chart-title">Weekly Users & Revenue</h4>
            <div class="chart-canvas-shell">
                <canvas id="analyticsLineChart"></canvas>
            </div>
        </div>

        <div class="analytics-card chart-box">
            <h4 class="chart-title">Order Status Mix</h4>
            <div class="chart-canvas-shell">
                <canvas id="analyticsStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="analytics-grid-lower">
        <div class="analytics-card chart-box compact">
            <h4 class="chart-title">Traffic Distribution</h4>
            <div class="chart-canvas-shell">
                <canvas id="analyticsTrafficChart"></canvas>
            </div>
        </div>

        <div class="analytics-card chart-box compact">
            <h4 class="chart-title">Sales Funnel</h4>
            <div class="chart-canvas-shell">
                <canvas id="analyticsFunnelChart"></canvas>
            </div>
        </div>

        <div class="analytics-metric-stack">
            <div class="analytics-card metric-box small">
                <p class="analytics-card-label">Completion Rate</p>
                <strong>{{ $analyticsSummary['orders'] > 0 ? round((($statusGroups['Completed'] ?? 0) / $analyticsSummary['orders']) * 100) : 0 }}%</strong>
            </div>
            <div class="analytics-card metric-box small">
                <p class="analytics-card-label">Avg. Order Value</p>
                <strong>PHP {{ $analyticsSummary['orders'] > 0 ? number_format($analyticsSummary['sales'] / $analyticsSummary['orders'], 2) : '0.00' }}</strong>
            </div>
            <div class="analytics-card metric-box small">
                <p class="analytics-card-label">Customer Accounts</p>
                <strong>{{ number_format($analyticsSummary['customers']) }}</strong>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (!window.Chart) return;

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#475569';

        const weeklyCustomers = @js($weeklyCustomers);
        const weeklySales = @js($weeklySales);
        const statusLabels = @js(array_keys($statusGroups));
        const statusValues = @js(array_values($statusGroups));
        const trafficLabels = @js(array_keys($trafficGroups));
        const trafficValues = @js(array_values($trafficGroups));

        const hasValues = values => values.some(value => Number(value) > 0);
        const normalizeValues = values => hasValues(values) ? values : values.map((_, index) => index === 0 ? 1 : 0);

        new Chart(document.getElementById('analyticsLineChart'), {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Users',
                        data: weeklyCustomers,
                        borderColor: '#2F80C1',
                        backgroundColor: 'rgba(47, 128, 193, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 5,
                    },
                    {
                        label: 'Revenue',
                        data: weeklySales,
                        borderColor: '#D45185',
                        backgroundColor: 'rgba(212, 81, 133, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } } },
                scales: { y: { grid: { color: '#E2E8F0', borderDash: [4, 4] }, beginAtZero: true }, x: { grid: { display: false } } }
            }
        });

        new Chart(document.getElementById('analyticsStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: normalizeValues(statusValues),
                    backgroundColor: ['#44C2C0', '#57A9E5', '#9A7AE8', '#F47DA2'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('analyticsTrafficChart'), {
            type: 'doughnut',
            data: {
                labels: trafficLabels,
                datasets: [{
                    data: normalizeValues(trafficValues),
                    backgroundColor: ['#44C2C0', '#57A9E5', '#9A7AE8', '#F47DA2'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '62%', plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('analyticsFunnelChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#44C2C0', '#57A9E5', '#9A7AE8', '#D45185'],
                    borderRadius: 5,
                    barThickness: 24
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false }, beginAtZero: true }, y: { grid: { display: false } } }
            }
        });
    });
</script>
