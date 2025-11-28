@extends('layouts.admin-navbar')

@section('content')
<div class="main-content">
    <div class="content-header">
        <div class="header-title">
            <h1 class="page-title">Analytics Dashboard</h1>
            <p class="page-subtitle">Gain insights into your sales performance and customer behavior</p>
        </div>
        <div class="header-actions">
            <select id="dateRange" class="filter-select">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 3 Months</option>
                <option value="365">Last Year</option>
            </select>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon revenue">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="metric-info">
                <h3 class="metric-value" id="totalRevenue">Rp 0</h3>
                <p class="metric-label">Total Revenue</p>
                <div class="metric-change" id="revenueChange">
                    <i class="fas fa-arrow-up"></i>
                    <span>0%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon transactions">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="metric-info">
                <h3 class="metric-value" id="totalTransactions">0</h3>
                <p class="metric-label">Total Transactions</p>
                <div class="metric-change" id="transactionsChange">
                    <i class="fas fa-arrow-up"></i>
                    <span>0%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon customers">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-info">
                <h3 class="metric-value" id="activeCustomers">0</h3>
                <p class="metric-label">Active Customers</p>
                <div class="metric-change" id="customersChange">
                    <i class="fas fa-arrow-up"></i>
                    <span>0%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon average">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="metric-info">
                <h3 class="metric-value" id="averageOrder">Rp 0</h3>
                <p class="metric-label">Average Order Value</p>
                <div class="metric-change" id="averageOrderChange">
                    <i class="fas fa-arrow-up"></i>
                    <span>0%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Revenue Chart -->
        <div class="chart-card large">
            <div class="chart-header">
                <h3>Revenue Trend</h3>
                <div class="chart-actions">
                    <button class="chart-btn active" data-chart="revenue">Revenue</button>
                    <button class="chart-btn" data-chart="transactions">Transactions</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Top Selling Games</h3>
            </div>
            <div class="top-products-list" id="topProductsList">
                <!-- Top products will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="analytics-grid">
        <!-- Sales by Category -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Sales by Category</h3>
            </div>
            <div class="chart-container">
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>

        <!-- Customer Analytics -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Customer Insights</h3>
            </div>
            <div class="customer-metrics">
                <div class="customer-metric">
                    <div class="metric-value" id="newCustomers">0</div>
                    <div class="metric-label">New Customers</div>
                </div>
                <div class="customer-metric">
                    <div class="metric-value" id="repeatCustomers">0</div>
                    <div class="metric-label">Repeat Customers</div>
                </div>
                <div class="customer-metric">
                    <div class="metric-value" id="conversionRate">0%</div>
                    <div class="metric-label">Conversion Rate</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Recent Transactions</h3>
                <a href="/admin/transactions" class="view-all">View All</a>
            </div>
            <div class="recent-activity" id="recentActivity">
                <!-- Recent transactions will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="reports-section">
        <div class="section-header">
            <h3>Detailed Reports</h3>
            <button class="btn-secondary" onclick="exportReports()">
                <i class="fas fa-download"></i>
                Export Report
            </button>
        </div>
        
        <div class="reports-grid">
            <div class="report-card">
                <h4>Daily Sales Performance</h4>
                <div class="report-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transactions</th>
                                <th>Revenue</th>
                                <th>Avg. Order</th>
                            </tr>
                        </thead>
                        <tbody id="dailyPerformance">
                            <!-- Daily performance data -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
// Global variables
let revenueChart, categoryChart;
let currentDateRange = 30;

// Format currency in Rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Format number with thousand separators
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Load analytics data
document.addEventListener('DOMContentLoaded', function() {
    loadAnalyticsData();
    setupEventListeners();
});

function setupEventListeners() {
    // Date range selector
    document.getElementById('dateRange').addEventListener('change', function(e) {
        currentDateRange = parseInt(e.target.value);
        loadAnalyticsData();
    });

    // Chart type buttons
    document.querySelectorAll('.chart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // You can implement chart switching logic here
        });
    });
}

async function loadAnalyticsData() {
    showLoadingState();
    
    try {
        // In a real implementation, you would fetch data from your API
        // For now, we'll use the existing dashboard data and enhance it
        
        const [transactionsResponse, gamesResponse, usersResponse] = await Promise.all([
            fetch('/api/transactions').catch(() => ({ json: () => [] })),
            fetch('/api/games').catch(() => ({ json: () => [] })),
            fetch('/api/users').catch(() => ({ json: () => [] }))
        ]);

        const transactions = await transactionsResponse.json();
        const games = await gamesResponse.json();
        const users = await usersResponse.json();

        // Calculate analytics metrics
        calculateMetrics(transactions, games, users);
        renderCharts(transactions, games);
        renderTopProducts(transactions, games);
        renderRecentActivity(transactions);
        renderDailyPerformance(transactions);
        
    } catch (error) {
        console.error('Error loading analytics data:', error);
        showError('Failed to load analytics data. Please try again.');
    } finally {
        hideLoadingState();
    }
}

function calculateMetrics(transactions, games, users) {
    const now = new Date();
    const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
    
    // Current period data (last 30 days)
    const currentPeriodTransactions = transactions.filter(t => 
        new Date(t.date) >= new Date(now.getTime() - currentDateRange * 24 * 60 * 60 * 1000)
    );
    
    // Previous period data
    const previousPeriodTransactions = transactions.filter(t => {
        const transactionDate = new Date(t.date);
        return transactionDate >= new Date(lastMonth.getTime() - currentDateRange * 24 * 60 * 60 * 1000) &&
               transactionDate < new Date(now.getTime() - currentDateRange * 24 * 60 * 60 * 1000);
    });

    // Calculate metrics
    const totalRevenue = currentPeriodTransactions.reduce((sum, t) => sum + (t.total_price || 0), 0);
    const previousRevenue = previousPeriodTransactions.reduce((sum, t) => sum + (t.total_price || 0), 0);
    const revenueChange = previousRevenue > 0 ? ((totalRevenue - previousRevenue) / previousRevenue) * 100 : 0;

    const totalTransactions = currentPeriodTransactions.length;
    const previousTransactions = previousPeriodTransactions.length;
    const transactionsChange = previousTransactions > 0 ? ((totalTransactions - previousTransactions) / previousTransactions) * 100 : 0;

    const activeCustomers = [...new Set(currentPeriodTransactions.map(t => t.user_id))].length;
    const previousCustomers = [...new Set(previousPeriodTransactions.map(t => t.user_id))].length;
    const customersChange = previousCustomers > 0 ? ((activeCustomers - previousCustomers) / previousCustomers) * 100 : 0;

    const averageOrder = totalTransactions > 0 ? totalRevenue / totalTransactions : 0;
    const previousAverage = previousTransactions > 0 ? previousRevenue / previousTransactions : 0;
    const averageOrderChange = previousAverage > 0 ? ((averageOrder - previousAverage) / previousAverage) * 100 : 0;

    // Update metric displays
    document.getElementById('totalRevenue').textContent = formatRupiah(totalRevenue);
    document.getElementById('totalTransactions').textContent = formatNumber(totalTransactions);
    document.getElementById('activeCustomers').textContent = formatNumber(activeCustomers);
    document.getElementById('averageOrder').textContent = formatRupiah(averageOrder);

    // Update change indicators
    updateChangeIndicator('revenueChange', revenueChange);
    updateChangeIndicator('transactionsChange', transactionsChange);
    updateChangeIndicator('customersChange', customersChange);
    updateChangeIndicator('averageOrderChange', averageOrderChange);

    // Calculate additional customer metrics
    const totalUsers = users.length;
    const conversionRate = totalUsers > 0 ? (activeCustomers / totalUsers) * 100 : 0;
    const newCustomers = activeCustomers; // Simplified - in reality you'd track new vs returning
    
    document.getElementById('newCustomers').textContent = formatNumber(newCustomers);
    document.getElementById('repeatCustomers').textContent = formatNumber(activeCustomers - newCustomers);
    document.getElementById('conversionRate').textContent = conversionRate.toFixed(1) + '%';
}

function updateChangeIndicator(elementId, change) {
    const element = document.getElementById(elementId);
    const arrow = element.querySelector('i');
    const span = element.querySelector('span');
    
    span.textContent = Math.abs(change).toFixed(1) + '%';
    
    if (change > 0) {
        element.className = 'metric-change positive';
        arrow.className = 'fas fa-arrow-up';
    } else if (change < 0) {
        element.className = 'metric-change negative';
        arrow.className = 'fas fa-arrow-down';
    } else {
        element.className = 'metric-change neutral';
        arrow.className = 'fas fa-minus';
    }
}

function renderCharts(transactions, games) {
    renderRevenueChart(transactions);
    renderCategoryChart(transactions, games);
}

function renderRevenueChart(transactions) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Group transactions by date for the selected period
    const dailyData = {};
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - currentDateRange);
    
    transactions.forEach(transaction => {
        const transactionDate = new Date(transaction.date);
        if (transactionDate >= startDate) {
            const dateKey = transactionDate.toISOString().split('T')[0];
            if (!dailyData[dateKey]) {
                dailyData[dateKey] = {
                    revenue: 0,
                    transactions: 0
                };
            }
            dailyData[dateKey].revenue += transaction.total_price || 0;
            dailyData[dateKey].transactions += 1;
        }
    });
    
    // Fill in missing dates with zero values
    const labels = [];
    const revenueData = [];
    const transactionData = [];
    
    for (let i = currentDateRange - 1; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateKey = date.toISOString().split('T')[0];
        labels.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
        
        if (dailyData[dateKey]) {
            revenueData.push(dailyData[dateKey].revenue);
            transactionData.push(dailyData[dateKey].transactions);
        } else {
            revenueData.push(0);
            transactionData.push(0);
        }
    }
    
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                borderColor: '#6a9eff',
                backgroundColor: 'rgba(106, 158, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return formatRupiah(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ${formatRupiah(context.parsed.y)}`;
                        }
                    }
                }
            }
        }
    });
}

function renderCategoryChart(transactions, games) {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    // Calculate revenue by category
    const categoryRevenue = {};
    
    transactions.forEach(transaction => {
        if (transaction.details) {
            transaction.details.forEach(detail => {
                const game = games.find(g => g.id === detail.game_id);
                if (game) {
                    const category = game.category || 'Uncategorized';
                    const revenue = detail.price * detail.qty;
                    
                    if (!categoryRevenue[category]) {
                        categoryRevenue[category] = 0;
                    }
                    categoryRevenue[category] += revenue;
                }
            });
        }
    });
    
    const categories = Object.keys(categoryRevenue);
    const revenueData = categories.map(category => categoryRevenue[category]);
    
    const colors = [
        '#6a9eff', '#4a7de8', '#8ab4ff', '#3b82f6', '#60a5fa',
        '#10b981', '#059669', '#34d399', '#f59e0b', '#d97706'
    ];
    
    if (categoryChart) {
        categoryChart.destroy();
    }
    
    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categories,
            datasets: [{
                data: revenueData,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${formatRupiah(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function renderTopProducts(transactions, games) {
    const productSales = {};
    
    // Calculate sales for each product
    transactions.forEach(transaction => {
        if (transaction.details) {
            transaction.details.forEach(detail => {
                const gameId = detail.game_id;
                if (!productSales[gameId]) {
                    productSales[gameId] = {
                        quantity: 0,
                        revenue: 0
                    };
                }
                productSales[gameId].quantity += detail.qty;
                productSales[gameId].revenue += detail.price * detail.qty;
            });
        }
    });
    
    // Sort products by revenue
    const topProducts = Object.entries(productSales)
        .map(([gameId, sales]) => {
            const game = games.find(g => g.id == gameId);
            return {
                ...sales,
                game: game || { title: 'Unknown Game', category: 'Unknown' }
            };
        })
        .sort((a, b) => b.revenue - a.revenue)
        .slice(0, 5);
    
    const container = document.getElementById('topProductsList');
    
    if (topProducts.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-chart-bar"></i>
                <p>No sales data available</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = topProducts.map((product, index) => `
        <div class="product-item">
            <div class="product-rank">${index + 1}</div>
            <div class="product-info">
                <div class="product-name">${product.game.title}</div>
                <div class="product-category">${product.game.category}</div>
            </div>
            <div class="product-stats">
                <div class="product-revenue">${formatRupiah(product.revenue)}</div>
                <div class="product-sales">${formatNumber(product.quantity)} sold</div>
            </div>
        </div>
    `).join('');
}

function renderRecentActivity(transactions) {
    const recentTransactions = transactions
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 5);
    
    const container = document.getElementById('recentActivity');
    
    if (recentTransactions.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <p>No recent transactions</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = recentTransactions.map(transaction => `
        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="activity-details">
                <div class="activity-title">Transaction #${transaction.id}</div>
                <div class="activity-meta">
                    ${formatRupiah(transaction.total_price || 0)} • ${new Date(transaction.date).toLocaleDateString('id-ID')}
                </div>
            </div>
        </div>
    `).join('');
}

function renderDailyPerformance(transactions) {
    const dailyData = {};
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 7); // Last 7 days
    
    transactions.forEach(transaction => {
        const transactionDate = new Date(transaction.date);
        if (transactionDate >= startDate) {
            const dateKey = transactionDate.toISOString().split('T')[0];
            if (!dailyData[dateKey]) {
                dailyData[dateKey] = {
                    transactions: 0,
                    revenue: 0
                };
            }
            dailyData[dateKey].transactions += 1;
            dailyData[dateKey].revenue += transaction.total_price || 0;
        }
    });
    
    const container = document.getElementById('dailyPerformance');
    const rows = [];
    
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateKey = date.toISOString().split('T')[0];
        const data = dailyData[dateKey] || { transactions: 0, revenue: 0 };
        const avgOrder = data.transactions > 0 ? data.revenue / data.transactions : 0;
        
        rows.push(`
            <tr>
                <td>${date.toLocaleDateString('id-ID')}</td>
                <td>${formatNumber(data.transactions)}</td>
                <td>${formatRupiah(data.revenue)}</td>
                <td>${formatRupiah(avgOrder)}</td>
            </tr>
        `);
    }
    
    container.innerHTML = rows.join('');
}

function exportReports() {
    // Simple export functionality - in real implementation, generate CSV/PDF
    alert('Export functionality would be implemented here to generate reports in CSV or PDF format.');
}

function showLoadingState() {
    // You can implement a loading spinner here
}

function hideLoadingState() {
    // Hide loading spinner
}

function showError(message) {
    alert('Error: ' + message);
}
</script>

<style>
.main-content {
    padding: 24px;
    background-color: #f8fafc;
    min-height: calc(100vh - 130px);
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    gap: 20px;
}

.header-title {
    flex: 1;
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
    line-height: 1.5;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    min-width: 150px;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.metric-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.metric-icon.revenue {
    background: linear-gradient(135deg, #10b981, #059669);
}

.metric-icon.transactions {
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
}

.metric-icon.customers {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.metric-icon.average {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.metric-info {
    flex: 1;
}

.metric-value {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.metric-label {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 8px;
}

.metric-change {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.metric-change.positive {
    background: #dcfce7;
    color: #166534;
}

.metric-change.negative {
    background: #fecaca;
    color: #dc2626;
}

.metric-change.neutral {
    background: #f1f5f9;
    color: #64748b;
}

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.chart-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 24px;
}

.chart-card.large {
    grid-column: 1 / -1;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.chart-actions {
    display: flex;
    gap: 8px;
}

.chart-btn {
    padding: 6px 12px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.chart-btn.active {
    background: #6a9eff;
    color: white;
    border-color: #6a9eff;
}

.chart-btn:not(.active):hover {
    border-color: #6a9eff;
    color: #6a9eff;
}

.chart-container {
    height: 300px;
    position: relative;
}

/* Top Products */
.top-products-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.product-item:last-child {
    border-bottom: none;
}

.product-rank {
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

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
    margin-bottom: 2px;
}

.product-category {
    color: #64748b;
    font-size: 12px;
}

.product-stats {
    text-align: right;
}

.product-revenue {
    font-weight: 600;
    color: #059669;
    font-size: 14px;
    margin-bottom: 2px;
}

.product-sales {
    color: #64748b;
    font-size: 12px;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

.customer-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    text-align: center;
}

.customer-metric {
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
}

.customer-metric .metric-value {
    font-size: 20px;
    margin-bottom: 4px;
}

.customer-metric .metric-label {
    font-size: 12px;
    margin-bottom: 0;
}

/* Recent Activity */
.recent-activity {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f0f5ff;
    color: #6a9eff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.activity-details {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: #1e293b;
    font-size: 14px;
    margin-bottom: 2px;
}

.activity-meta {
    color: #64748b;
    font-size: 12px;
}

/* Reports Section */
.reports-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 24px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.btn-secondary {
    background: #f8fafc;
    color: #374151;
    border: 1px solid #e2e8f0;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #f1f5f9;
}

.reports-grid {
    display: grid;
    gap: 20px;
}

.report-card h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 16px;
}

.report-table {
    overflow-x: auto;
}

.report-table table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
}

.report-table th {
    font-weight: 600;
    color: #374151;
    background: #f8fafc;
}

.report-table tr:last-child td {
    border-bottom: none;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
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

/* Responsive Design */
@media (max-width: 1024px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .customer-metrics {
        grid-template-columns: 1fr;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    
    .chart-actions {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .main-content {
        padding: 16px;
    }
    
    .metric-card {
        flex-direction: column;
        text-align: center;
    }
    
    .product-item {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .product-stats {
        text-align: center;
    }
}
</style>
@endsection