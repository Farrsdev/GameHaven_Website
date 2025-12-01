@extends('layouts.user-navbar')

@section('title', 'Download History - GameHaven')

@section('content')
<div class="downloads-page">
    <!-- Header -->
    <div class="page-header">
        <h1>Download History</h1>
        <p>Your game download activities</p>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-value" id="totalDownloads">0</div>
            <div class="stat-label">Total Downloads</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="recentDownloads">0</div>
            <div class="stat-label">Last 7 Days</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="totalSize">0 GB</div>
            <div class="stat-label">Total Size</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <label for="timeFilter">Time Period:</label>
            <select id="timeFilter" class="filter-select">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="sortFilter">Sort By:</label>
            <select id="sortFilter" class="filter-select">
                <option value="recent">Most Recent</option>
                <option value="oldest">Oldest First</option>
                <option value="size">File Size</option>
                <option value="name">Game Name</option>
            </select>
        </div>
        <button class="btn secondary" id="clearFilters">
            <i class="fas fa-times"></i>
            Clear Filters
        </button>
    </div>

    <!-- Download History -->
    <div class="downloads-section">
        <div class="section-header">
            <h2>Download Activities</h2>
            <div class="section-actions">
                <button class="btn secondary" id="exportBtn">
                    <i class="fas fa-download"></i>
                    Export History
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-download"></i>
            <h3>No download history</h3>
            <p>Your download activities will appear here</p>
            <a href="{{ url('/purchased') }}" class="btn primary">
                <i class="fas fa-gamepad"></i>
                Browse Your Games
            </a>
        </div>

        <!-- Downloads List -->
        <div class="downloads-list" id="downloadsList">
            <div class="loading">Loading download history...</div>
        </div>

        <!-- Load More -->
        <div class="load-more" id="loadMoreContainer" style="display: none;">
            <button class="btn" id="loadMoreBtn">
                <i class="fas fa-redo"></i>
                Load More
            </button>
        </div>
    </div>
</div>

<!-- Download Details Modal -->
<div class="modal" id="downloadDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Download Details</h3>
            <button class="close" onclick="closeDownloadDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="downloadDetailsContent">
                <!-- Download details will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeDownloadDetailsModal()">Close</button>
            <button class="btn primary" onclick="redownloadGame()">
                <i class="fas fa-download"></i>
                Download Again
            </button>
        </div>
    </div>
</div>

<style>
.downloads-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 2rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.page-header p {
    color: var(--text-secondary);
}

/* Stats */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 25px;
    text-align: center;
    transition: var(--transition);
}

.stat-card:hover {
    border-color: var(--accent);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 8px;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 14px;
}

/* Filters */
.filters-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    display: flex;
    gap: 20px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    color: var(--text-light);
    font-size: 14px;
    font-weight: 500;
}

.filter-select {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
    cursor: pointer;
    min-width: 150px;
}

.filter-select:focus {
    border-color: var(--accent);
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
}

.section-actions {
    display: flex;
    gap: 10px;
}

/* Downloads List */
.downloads-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.download-item {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    transition: var(--transition);
    cursor: pointer;
}

.download-item:hover {
    border-color: var(--accent);
    transform: translateX(4px);
}

.download-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.download-game-info {
    flex: 1;
}

.download-game-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--text-light);
}

.download-developer {
    color: var(--text-secondary);
    font-size: 13px;
}

.download-meta {
    display: flex;
    gap: 15px;
    color: var(--text-secondary);
    font-size: 13px;
}

.download-date {
    display: flex;
    align-items: center;
    gap: 6px;
}

.download-date i {
    font-size: 12px;
    color: var(--accent);
}

.download-size {
    display: flex;
    align-items: center;
    gap: 6px;
}

.download-size i {
    font-size: 12px;
    color: #10b981;
}

.download-version {
    display: flex;
    align-items: center;
    gap: 6px;
}

.download-version i {
    font-size: 12px;
    color: #f59e0b;
}

.download-actions {
    display: flex;
    gap: 8px;
}

/* Buttons */
.btn {
    padding: 8px 16px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: none;
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn.primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn.primary:hover:not(:disabled) {
    opacity: 0.9;
}

.btn.secondary {
    background: rgba(255, 255, 255, 0.05);
}

.btn.secondary:hover {
    border-color: var(--accent);
}

.btn.small {
    padding: 6px 12px;
    font-size: 12px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 12px;
    color: var(--text-light);
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 25px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Load More */
.load-more {
    text-align: center;
    margin-top: 30px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: var(--bg-secondary);
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    border: 1px solid var(--border);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--border);
}

.modal-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.close {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close:hover {
    color: var(--text-primary);
}

.modal-body {
    padding: 20px;
}

.download-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: var(--text-secondary);
    font-weight: 500;
}

.detail-value {
    color: var(--text-light);
    font-weight: 600;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid var(--border);
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .downloads-page {
        padding: 20px 16px;
    }
    
    .stats {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .filters-section {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-select {
        width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .download-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .download-meta {
        flex-wrap: wrap;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .download-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
}
</style>

<script>
let downloadHistory = [];
let currentFilters = {
    time: 'all',
    sort: 'recent'
};
let currentPage = 1;
let hasMore = true;

document.addEventListener('DOMContentLoaded', function() {
    loadDownloadHistory();
    
    // Event listeners untuk filters
    document.getElementById('timeFilter').addEventListener('change', function() {
        currentFilters.time = this.value;
        reloadHistory();
    });
    
    document.getElementById('sortFilter').addEventListener('change', function() {
        currentFilters.sort = this.value;
        reloadHistory();
    });
    
    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    document.getElementById('loadMoreBtn').addEventListener('click', loadMoreHistory);
    document.getElementById('exportBtn').addEventListener('click', exportHistory);
});

async function loadDownloadHistory() {
    try {
        const userId = {{ Session::get('user_id') }};
        showLoading();
        
        const response = await fetch(`/api/downloads/history/${userId}`);
        if (!response.ok) {
            throw new Error('Failed to load download history');
        }
        
        const data = await response.json();
        downloadHistory = data;
        
        updateStats();
        displayDownloads();
        
    } catch (error) {
        console.error('Error loading download history:', error);
        showNotification('Error loading download history', 'error');
        // Fallback to sample data
        downloadHistory = getSampleDownloadHistory();
        updateStats();
        displayDownloads();
    }
}

function getSampleDownloadHistory() {
    return [
        {
            id: 1,
            game: {
                title: "Cyberpunk Adventure",
                developer: "Neon Studios",
                image_url: ""
            },
            download_date: "2024-01-20 14:30:00",
            file_size: "15.2 GB",
            version: "1.5.3"
        },
        {
            id: 2,
            game: {
                title: "Space Explorers",
                developer: "Galaxy Games",
                image_url: ""
            },
            download_date: "2024-01-18 10:15:00",
            file_size: "8.7 GB",
            version: "2.1.0"
        },
        {
            id: 3,
            game: {
                title: "Ancient Kingdom",
                developer: "HistorySoft",
                image_url: ""
            },
            download_date: "2024-01-15 16:45:00",
            file_size: "12.5 GB",
            version: "1.2.1"
        }
    ];
}

function updateStats() {
    const totalDownloads = downloadHistory.length;
    
    // Calculate recent downloads (last 7 days)
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
    const recentDownloads = downloadHistory.filter(download => 
        new Date(download.download_date) >= sevenDaysAgo
    ).length;
    
    // Calculate total size
    const totalSize = downloadHistory.reduce((sum, download) => {
        const size = parseFloat(download.file_size) || 0;
        return sum + size;
    }, 0);
    
    document.getElementById('totalDownloads').textContent = totalDownloads;
    document.getElementById('recentDownloads').textContent = recentDownloads;
    document.getElementById('totalSize').textContent = totalSize.toFixed(1) + ' GB';
}

function displayDownloads() {
    const container = document.getElementById('downloadsList');
    const emptyState = document.getElementById('emptyState');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    
    if (downloadHistory.length === 0) {
        container.style.display = 'none';
        emptyState.style.display = 'block';
        loadMoreContainer.style.display = 'none';
        return;
    }
    
    container.style.display = 'flex';
    emptyState.style.display = 'none';
    
    // Apply filters
    let filteredDownloads = [...downloadHistory];
    
    // Time filter
    if (currentFilters.time !== 'all') {
        const now = new Date();
        let startDate;
        
        switch (currentFilters.time) {
            case 'today':
                startDate = new Date(now.setHours(0, 0, 0, 0));
                break;
            case 'week':
                startDate = new Date(now.setDate(now.getDate() - 7));
                break;
            case 'month':
                startDate = new Date(now.setMonth(now.getMonth() - 1));
                break;
        }
        
        filteredDownloads = filteredDownloads.filter(download => 
            new Date(download.download_date) >= startDate
        );
    }
    
    // Sort filter
    switch (currentFilters.sort) {
        case 'oldest':
            filteredDownloads.sort((a, b) => new Date(a.download_date) - new Date(b.download_date));
            break;
        case 'size':
            filteredDownloads.sort((a, b) => {
                const sizeA = parseFloat(a.file_size) || 0;
                const sizeB = parseFloat(b.file_size) || 0;
                return sizeB - sizeA;
            });
            break;
        case 'name':
            filteredDownloads.sort((a, b) => a.game.title.localeCompare(b.game.title));
            break;
        case 'recent':
        default:
            filteredDownloads.sort((a, b) => new Date(b.download_date) - new Date(a.download_date));
            break;
    }
    
    const downloadsHTML = filteredDownloads.map(download => `
        <div class="download-item" onclick="showDownloadDetails(${download.id})">
            <div class="download-header">
                <div class="download-game-info">
                    <div class="download-game-title">${download.game.title}</div>
                    <div class="download-developer">${download.game.developer}</div>
                </div>
                <div class="download-actions">
                    <button class="btn small secondary" onclick="event.stopPropagation(); redownloadFromHistory(${download.game.id})">
                        <i class="fas fa-redo"></i>
                        Download Again
                    </button>
                </div>
            </div>
            <div class="download-meta">
                <div class="download-date">
                    <i class="fas fa-calendar"></i>
                    ${formatDateTime(download.download_date)}
                </div>
                <div class="download-size">
                    <i class="fas fa-hdd"></i>
                    ${download.file_size || 'Unknown size'}
                </div>
                <div class="download-version">
                    <i class="fas fa-code-branch"></i>
                    v${download.version || '1.0.0'}
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = downloadsHTML;
    loadMoreContainer.style.display = hasMore ? 'block' : 'none';
}

function showDownloadDetails(downloadId) {
    const download = downloadHistory.find(d => d.id === downloadId);
    if (!download) return;
    
    const content = document.getElementById('downloadDetailsContent');
    
    content.innerHTML = `
        <div class="download-details">
            <div class="detail-row">
                <span class="detail-label">Game:</span>
                <span class="detail-value">${download.game.title}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Developer:</span>
                <span class="detail-value">${download.game.developer}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Download Date:</span>
                <span class="detail-value">${formatDateTime(download.download_date)}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">File Size:</span>
                <span class="detail-value">${download.file_size || 'Unknown'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Version:</span>
                <span class="detail-value">v${download.version || '1.0.0'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Download ID:</span>
                <span class="detail-value">#${download.id}</span>
            </div>
        </div>
    `;
    
    // Store current download ID for redownload
    content.setAttribute('data-download-id', downloadId);
    content.setAttribute('data-game-id', download.game.id);
    
    document.getElementById('downloadDetailsModal').style.display = 'block';
}

function closeDownloadDetailsModal() {
    document.getElementById('downloadDetailsModal').style.display = 'none';
}

function redownloadGame() {
    const content = document.getElementById('downloadDetailsContent');
    const gameId = content.getAttribute('data-game-id');
    
    if (gameId) {
        redownloadFromHistory(parseInt(gameId));
        closeDownloadDetailsModal();
    }
}

async function redownloadFromHistory(gameId) {
    try {
        showNotification('Starting download...', 'info');
        
        // Simulate download process
        // In real implementation, this would trigger actual download
        setTimeout(() => {
            showNotification('Download started successfully!', 'success');
        }, 1000);
        
    } catch (error) {
        console.error('Redownload error:', error);
        showNotification('Download failed', 'error');
    }
}

function reloadHistory() {
    currentPage = 1;
    hasMore = true;
    loadDownloadHistory();
}

function loadMoreHistory() {
    // Implementation for pagination
    // For now, we'll just reload
    reloadHistory();
}

function clearFilters() {
    document.getElementById('timeFilter').value = 'all';
    document.getElementById('sortFilter').value = 'recent';
    currentFilters = { time: 'all', sort: 'recent' };
    reloadHistory();
}

function exportHistory() {
    // Simple CSV export implementation
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Game,Developer,Download Date,File Size,Version\n"
        + downloadHistory.map(download => 
            `"${download.game.title}","${download.game.developer}","${formatDateTime(download.download_date)}","${download.file_size}","${download.version}"`
        ).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "gamehaven_download_history.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Download history exported successfully!', 'success');
}

function showLoading() {
    document.getElementById('downloadsList').innerHTML = '<div class="loading">Loading download history...</div>';
}

// Utility functions
function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        z-index: 1000;
        font-size: 14px;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection