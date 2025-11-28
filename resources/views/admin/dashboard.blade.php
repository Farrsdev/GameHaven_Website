@extends('layouts.admin-navbar')

@section('content')
<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, {{ session('username') ?? 'Admin' }}! Here's what's happening with your store.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $totalUsers }}</h3>
                <p class="stat-label">Total Users</p>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>Active</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-gamepad"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $totalGames }}</h3>
                <p class="stat-label">Total Games</p>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>In Stock</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $totalTransactions }}</h3>
                <p class="stat-label">Total Transactions</p>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>All Time</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">Rp{{ number_format($currentMonthRevenue, 2) }}</h3>
                <p class="stat-label">Monthly Revenue</p>
            </div>
            <div class="stat-trend {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></i>
                <span>{{ number_format(abs($revenueChange), 1) }}%</span>
            </div>
        </div>
    </div>

    <!-- Charts and Additional Stats -->
    <div class="dashboard-grid">
        <!-- Revenue Chart -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Revenue Overview</h3>
                <div class="card-actions">
                    <select id="chart-period" class="period-select">
                        <option value="7">Last 7 Days</option>
                        <option value="30" selected>Last 30 Days</option>
                        <option value="90">Last 3 Months</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>

        <!-- Popular Games -->
        <div class="popular-games-card">
            <div class="card-header">
                <h3>Popular Games</h3>
            </div>
            <div class="games-list">
                @forelse($popularGames as $index => $gameDetail)
                <div class="game-item">
                    <div class="game-rank">{{ $index + 1 }}</div>
                    <div class="game-info">
                        <h4 class="game-title">{{ $gameDetail->game->title ?? 'Unknown Game' }}</h4>
                        <p class="game-sales">{{ $gameDetail->total_sold }} sold</p>
                    </div>
                    <div class="game-revenue">
                        ${{ number_format(($gameDetail->total_sold * $gameDetail->game->price), 2) }}
                    </div>
                </div>
                @empty
                <div class="no-data">
                    <i class="fas fa-gamepad"></i>
                    <p>No sales data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="recent-transactions">
        <div class="card-header">
            <h3>Recent Transactions</h3>
            <a href="/admin/transactions" class="view-all">View All</a>
        </div>
        <div class="transactions-table">
            <div class="table-header">
                <div class="col-user">User</div>
                <div class="col-amount">Amount</div>
                <div class="col-date">Date</div>
                <div class="col-status">Status</div>
            </div>
            <div class="table-body">
                @forelse($recentTransactions as $transaction)
                <div class="table-row">
                    <div class="col-user">
                        <div class="user-avatar-small">
                            {{ substr($transaction->user->username ?? 'U', 0, 1) }}
                        </div>
                        <span class="user-name">{{ $transaction->user->username ?? 'Unknown User' }}</span>
                    </div>
                    <div class="col-amount">${{ number_format($transaction->total_price, 2) }}</div>
                    <div class="col-date">{{ $transaction->date->format('M d, Y') }}</div>
                    <div class="col-status">
                        <span class="status-badge completed">Completed</span>
                    </div>
                </div>
                @empty
                <div class="no-data">
                    <i class="fas fa-receipt"></i>
                    <p>No transactions found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats-grid">
        <div class="quick-stat">
            <div class="quick-stat-icon total-revenue">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="quick-stat-info">
                <h4>${{ number_format($totalRevenue, 2) }}</h4>
                <p>Total Revenue</p>
            </div>
        </div>
        
        <div class="quick-stat">
            <div class="quick-stat-icon avg-order">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <div class="quick-stat-info">
                <h4>${{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 2) : '0.00' }}</h4>
                <p>Average Order Value</p>
            </div>
        </div>
        
        <div class="quick-stat">
            <div class="quick-stat-icon conversion">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="quick-stat-info">
                <h4>{{ $totalUsers > 0 ? number_format(($totalTransactions / $totalUsers) * 100, 1) : '0' }}%</h4>
                <p>Conversion Rate</p>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($weeklyTransactions->pluck('date')) !!},
            datasets: [{
                label: 'Daily Revenue',
                data: {!! json_encode($weeklyTransactions->pluck('daily_revenue')) !!},
                borderColor: '#6a9eff',
                backgroundColor: 'rgba(106, 158, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Update chart based on period selection
    document.getElementById('chart-period').addEventListener('change', function(e) {
        // In a real implementation, you would fetch new data from the server
        console.log('Period changed to:', e.target.value + ' days');
        // You can implement AJAX call to fetch new chart data here
    });
});
</script>

<style>
.main-content {
    padding: 24px;
    background-color: #f8fafc;
    min-height: calc(100vh - 130px);
}

.content-header {
    margin-bottom: 24px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.page-subtitle {
    color: #64748b;
    font-size: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid #6a9eff;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.stat-label {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.stat-trend.positive {
    background: #dcfce7;
    color: #166534;
}

.stat-trend.negative {
    background: #fecaca;
    color: #dc2626;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 30px;
}

.chart-card, .popular-games-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 24px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.period-select {
    padding: 6px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    background: white;
}

.chart-container {
    height: 250px;
}

.games-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.game-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.game-item:last-child {
    border-bottom: none;
}

.game-rank {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #6a9eff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.game-info {
    flex: 1;
}

.game-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
}

.game-sales {
    font-size: 12px;
    color: #64748b;
}

.game-revenue {
    font-weight: 600;
    color: #059669;
    font-size: 14px;
}

.recent-transactions {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 24px;
    margin-bottom: 30px;
}

.view-all {
    color: #6a9eff;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
}

.view-all:hover {
    text-decoration: underline;
}

.transactions-table {
    display: flex;
    flex-direction: column;
}

.table-header, .table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 16px;
    padding: 12px 0;
    align-items: center;
}

.table-header {
    border-bottom: 2px solid #f1f5f9;
    font-weight: 600;
    color: #64748b;
    font-size: 14px;
}

.table-row {
    border-bottom: 1px solid #f8fafc;
}

.table-row:last-child {
    border-bottom: none;
}

.col-user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 12px;
}

.user-name {
    font-weight: 500;
    color: #1e293b;
}

.col-amount {
    font-weight: 600;
    color: #059669;
}

.col-date {
    color: #64748b;
    font-size: 14px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.completed {
    background: #dcfce7;
    color: #166534;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.quick-stat {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
}

.quick-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.quick-stat-icon.total-revenue {
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
}

.quick-stat-icon.avg-order {
    background: linear-gradient(135deg, #10b981, #059669);
}

.quick-stat-icon.conversion {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.quick-stat-info h4 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.quick-stat-info p {
    color: #64748b;
    font-size: 14px;
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.no-data i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header, .table-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .col-user, .col-amount, .col-date, .col-status {
        justify-content: space-between;
        display: flex;
    }
    
    .col-user::before { content: "User: "; font-weight: 600; }
    .col-amount::before { content: "Amount: "; font-weight: 600; }
    .col-date::before { content: "Date: "; font-weight: 600; }
    .col-status::before { content: "Status: "; font-weight: 600; }
}
</style>
@endsection