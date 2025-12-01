@extends('layouts.user-navbar')

@section('title', 'My Games - GameHaven')

@section('content')
<div class="purchased-page">
    <!-- Header -->
    <div class="page-header">
        <h1>My Games</h1>
        <p>Your purchased game collection</p>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-value" id="totalGames">0</div>
            <div class="stat-label">Total Games</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="totalSpent">Rp 0</div>
            <div class="stat-label">Total Spent</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="recentPurchases">0</div>
            <div class="stat-label">Recent (30 days)</div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="games-section">
        <div class="section-header">
            <h2>Your Game Library</h2>
            <div class="filter-options">
                <select id="sortSelect" class="filter-select">
                    <option value="recent">Recently Added</option>
                    <option value="name">Name A-Z</option>
                    <option value="playtime">Most Played</option>
                </select>
            </div>
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-gamepad"></i>
            <h3>No games yet</h3>
            <p>Start building your collection by purchasing games from our store</p>
            <a href="{{ url('/store') }}" class="btn primary">
                <i class="fas fa-store"></i>
                Browse Store
            </a>
        </div>

        <!-- Games Grid -->
        <div class="games-grid" id="gamesGrid">
            <div class="loading">Loading your games...</div>
        </div>
    </div>
</div>

<!-- Download Modal -->
<div class="modal" id="downloadModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Download Game</h3>
            <button class="close" onclick="closeDownloadModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="downloadContent">
                <!-- Download content will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeDownloadModal()">Cancel</button>
            <button class="btn primary" id="startDownloadBtn">
                <i class="fas fa-download"></i>
                Start Download
            </button>
        </div>
    </div>
</div>

<style>
.purchased-page {
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
    margin-bottom: 40px;
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

.filter-select {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
    cursor: pointer;
}

.filter-select:focus {
    border-color: var(--accent);
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

/* Games Grid */
.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.game-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition);
}

.game-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}

.game-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    background: var(--border);
}

.game-content {
    padding: 20px;
}

.game-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.game-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.purchased-badge {
    background: #10b981;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 10px;
    white-space: nowrap;
}

.game-developer {
    color: var(--text-secondary);
    font-size: 13px;
    margin-bottom: 15px;
}

.game-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 13px;
    color: var(--text-secondary);
}

.purchase-date {
    display: flex;
    align-items: center;
    gap: 5px;
}

.purchase-date i {
    font-size: 12px;
}

.game-actions {
    display: flex;
    gap: 10px;
}

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
    flex: 1;
    justify-content: center;
}

.btn.primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn.primary:hover {
    opacity: 0.9;
}

.btn.secondary {
    background: rgba(255, 255, 255, 0.05);
}

.btn.secondary:hover {
    border-color: var(--accent);
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

.download-info {
    margin-bottom: 20px;
}

.download-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.download-item:last-child {
    border-bottom: none;
}

.download-options {
    margin-top: 20px;
}

.option-group {
    margin-bottom: 15px;
}

.option-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-light);
    font-weight: 500;
}

.option-select {
    width: 100%;
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 14px;
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
    grid-column: 1 / -1;
}

/* Progress Bar */
.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
    margin: 15px 0;
}

.progress-fill {
    height: 100%;
    background: var(--accent);
    width: 0%;
    transition: width 0.3s ease;
}

@media (max-width: 768px) {
    .purchased-page {
        padding: 20px 16px;
    }
    
    .stats {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .game-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .games-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
}
</style>

<script>
let purchasedGames = [];

document.addEventListener('DOMContentLoaded', function() {
    loadPurchasedGames();
    
    document.getElementById('sortSelect').addEventListener('change', sortGames);
});

async function loadPurchasedGames() {
    try {
        const userId = {{ Session::get('user_id') }}; // Get user ID from session
        
        const response = await fetch(`/api/user/${userId}/purchased-games`);
        if (!response.ok) {
            throw new Error('Failed to load purchased games');
        }
        
        const data = await response.json();
        purchasedGames = data.map(item => ({
            id: item.game.id,
            title: item.game.title,
            developer: item.game.developer,
            image_url: item.game.image_url,
            price: item.game.price,
            file_url: item.game.file_url,
            file_size: item.game.file_size || '15.2 GB', // Default fallback
            version: item.game.version || '1.0.0', // Default fallback
            purchased_date: item.purchase_date,
            download_status: item.download_status,
            last_played: item.last_played
        }));
        
        updateStats();
        displayGames();
        
    } catch (error) {
        console.error('Error loading purchased games:', error);
        showNotification('Error loading your games', 'error');
        // Fallback to sample data jika API error
        purchasedGames = getSamplePurchasedGames();
        updateStats();
        displayGames();
    }
}

function getSamplePurchasedGames() {
    return [
        {
            id: 1,
            title: "Cyberpunk Adventure",
            developer: "Neon Studios",
            image_url: "",
            price: 249000,
            purchased_date: "2024-01-15",
            file_size: "15.2 GB",
            version: "1.5.3",
            download_status: "NOT_DOWNLOADED",
            last_played: "2024-01-20"
        },
        {
            id: 2,
            title: "Space Explorers",
            developer: "Galaxy Games",
            image_url: "",
            price: 149000,
            purchased_date: "2024-01-10",
            file_size: "8.7 GB",
            version: "2.1.0",
            download_status: "DOWNLOADED",
            last_played: "2024-01-18"
        }
    ];
}

function updateStats() {
    const totalGames = purchasedGames.length;
    const totalSpent = purchasedGames.reduce((sum, game) => sum + game.price, 0);
    
    // Calculate recent purchases (last 30 days)
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    const recentPurchases = purchasedGames.filter(game => 
        new Date(game.purchased_date) >= thirtyDaysAgo
    ).length;
    
    document.getElementById('totalGames').textContent = totalGames;
    document.getElementById('totalSpent').textContent = formatCurrency(totalSpent);
    document.getElementById('recentPurchases').textContent = recentPurchases;
}

function displayGames() {
    const container = document.getElementById('gamesGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (purchasedGames.length === 0) {
        container.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    container.style.display = 'grid';
    emptyState.style.display = 'none';
    
    const gamesHTML = purchasedGames.map(game => {
        const isDownloaded = game.download_status === 'DOWNLOADED' || game.download_status === 'INSTALLED';
        
        return `
        <div class="game-card">
            <img src="${game.image_url || getPlaceholderImage()}" 
                 alt="${game.title}" 
                 class="game-image"
                 onerror="this.src='${getPlaceholderImage()}'">
            <div class="game-content">
                <div class="game-header">
                    <div class="game-title">${game.title}</div>
                    <div class="purchased-badge">${isDownloaded ? 'INSTALLED' : 'OWNED'}</div>
                </div>
                <div class="game-developer">${game.developer}</div>
                
                <div class="game-meta">
                    <div class="purchase-date">
                        <i class="fas fa-calendar"></i>
                        ${formatDate(game.purchased_date)}
                    </div>
                    ${game.last_played ? `
                        <div class="last-played">
                            <i class="fas fa-clock"></i>
                            Last played ${formatDate(game.last_played)}
                        </div>
                    ` : '<div>Not played yet</div>'}
                </div>
                
                <div class="game-actions">
                    <button class="btn primary" onclick="downloadGame(${game.id})" ${isDownloaded ? 'disabled' : ''}>
                        <i class="fas fa-download"></i>
                        ${isDownloaded ? 'Downloaded' : 'Download'}
                    </button>
                    <button class="btn secondary" onclick="viewGameDetails(${game.id})">
                        <i class="fas fa-info"></i>
                        Details
                    </button>
                </div>
            </div>
        </div>
        `;
    }).join('');
    
    container.innerHTML = gamesHTML;
}

function sortGames() {
    const sortBy = document.getElementById('sortSelect').value;
    
    switch(sortBy) {
        case 'name':
            purchasedGames.sort((a, b) => a.title.localeCompare(b.title));
            break;
        case 'playtime':
            // Sort by last played date (most recent first)
            purchasedGames.sort((a, b) => {
                const dateA = a.last_played ? new Date(a.last_played) : new Date(0);
                const dateB = b.last_played ? new Date(b.last_played) : new Date(0);
                return dateB - dateA;
            });
            break;
        case 'recent':
        default:
            // Sort by purchase date (most recent first)
            purchasedGames.sort((a, b) => new Date(b.purchased_date) - new Date(a.purchased_date));
            break;
    }
    
    displayGames();
}

async function downloadGame(gameId) {
    const game = purchasedGames.find(g => g.id === gameId);
    if (!game) return;
    
    const modal = document.getElementById('downloadModal');
    const content = document.getElementById('downloadContent');
    
    content.innerHTML = `
        <div class="download-info">
            <h4>${game.title}</h4>
            <div class="download-item">
                <span>File Size:</span>
                <span>${game.file_size}</span>
            </div>
            <div class="download-item">
                <span>Version:</span>
                <span>${game.version}</span>
            </div>
            <div class="download-item">
                <span>Purchase Date:</span>
                <span>${formatDate(game.purchased_date)}</span>
            </div>
        </div>
        
        <div class="download-options">
            <div class="option-group">
                <label>Download Location:</label>
                <select class="option-select" id="downloadLocation">
                    <option value="default">Default Downloads Folder</option>
                    <option value="custom">Choose Custom Location</option>
                </select>
            </div>
        </div>
        
        <div id="downloadProgress" style="display: none;">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div style="text-align: center; font-size: 14px; color: var(--text-secondary);">
                <span id="progressText">Preparing download...</span>
            </div>
        </div>
    `;
    
    // Update download button
    const downloadBtn = document.getElementById('startDownloadBtn');
    downloadBtn.innerHTML = '<i class="fas fa-download"></i> Start Download';
    downloadBtn.onclick = () => startDownload(gameId);
    
    modal.style.display = 'block';
}

async function startDownload(gameId) {
    const game = purchasedGames.find(g => g.id === gameId);
    if (!game) return;
    
    const progressSection = document.getElementById('downloadProgress');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const downloadBtn = document.getElementById('startDownloadBtn');
    
    // Show progress section
    progressSection.style.display = 'block';
    downloadBtn.disabled = true;
    downloadBtn.innerHTML = '<i class="fas fa-sync fa-spin"></i> Downloading...';
    
    try {
        // Update download status to DOWNLOADING di purchased_games
        await updateDownloadStatus(gameId, 'DOWNLOADING');
        
        // Simulate download progress
        let progress = 0;
        const interval = setInterval(async () => {
            progress += Math.random() * 10;
            if (progress > 100) progress = 100;
            
            progressFill.style.width = `${progress}%`;
            progressText.textContent = `Downloading... ${Math.round(progress)}%`;
            
            if (progress === 100) {
                clearInterval(interval);
                
                try {
                    // Record download to history and update status
                    const downloadResponse = await fetch(`/api/downloads/${gameId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: {{ Session::get('user_id') }}
                        })
                    });
                    
                    const downloadResult = await downloadResponse.json();
                    
                    if (!downloadResponse.ok || !downloadResult.success) {
                        throw new Error(downloadResult.error || 'Failed to record download');
                    }
                    
                    progressText.textContent = 'Download Complete!';
                    downloadBtn.innerHTML = '<i class="fas fa-check"></i> Complete';
                    downloadBtn.disabled = false;
                    downloadBtn.onclick = () => closeDownloadModal();
                    
                    // Show success notification
                    showNotification(`${game.title} downloaded successfully!`, 'success');
                    
                    // Reload games to update status
                    loadPurchasedGames();
                    
                } catch (downloadError) {
                    console.error('Download recording error:', downloadError);
                    // Fallback: update status manually
                    await updateDownloadStatus(gameId, 'DOWNLOADED');
                    showNotification(`${game.title} downloaded! (History not recorded)`, 'warning');
                    loadPurchasedGames();
                }
            }
        }, 200);
        
    } catch (error) {
        console.error('Download error:', error);
        showNotification('Download failed: ' + error.message, 'error');
        downloadBtn.disabled = false;
        downloadBtn.innerHTML = '<i class="fas fa-download"></i> Start Download';
    }
}

// Update function untuk update download status di purchased_games
async function updateDownloadStatus(gameId, status) {
    try {
        // Cari purchased_game_id berdasarkan game_id dan user_id
        const userId = {{ Session::get('user_id') }};
        const response = await fetch(`/api/user/${userId}/purchased-games`);
        
        if (!response.ok) {
            throw new Error('Failed to get purchased games');
        }
        
        const purchasedGames = await response.json();
        const purchasedGame = purchasedGames.find(pg => pg.game_id === gameId);
        
        if (!purchasedGame) {
            throw new Error('Purchased game not found');
        }
        
        // Update status
        const updateResponse = await fetch(`/api/purchased-games/${purchasedGame.id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                download_status: status
            })
        });
        
        if (!updateResponse.ok) {
            throw new Error('Failed to update download status');
        }
        
        return await updateResponse.json();
    } catch (error) {
        console.error('Error updating download status:', error);
        throw error;
    }
}

function closeDownloadModal() {
    document.getElementById('downloadModal').style.display = 'none';
}

function viewGameDetails(gameId) {
    window.location.href = `/games/${gameId}`;
}

// Utility functions
function formatCurrency(amount) {
    if (!amount) return 'Free';
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function getPlaceholderImage() {
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDI4MCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyODAiIGhlaWdodD0iMTYwIiBmaWxsPSIjMTExMTExIi8+Cjx0ZXh0IHg9IjE0MCIgeT0iODAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiMzMzMzMzMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiI+R2FtZSBJbWFnZTwvdGV4dD4KPC9zdmc+';
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