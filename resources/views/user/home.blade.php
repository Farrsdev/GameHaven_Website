@extends('layouts.user-navbar')

@section('title', 'Dashboard - GameHaven')

@section('content')
<div class="home-container">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="welcome-content">
            <h1 class="welcome-title">Welcome back, <span class="username" id="username">{{ session('username') }}</span>! 👋</h1>
            <p class="welcome-subtitle">Ready for your next gaming adventure?</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number" id="totalGames">0</div>
                        <div class="stat-label">Games Owned</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number" id="totalSpent">Rp 0</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number" id="wishlistCount">0</div>
                        <div class="stat-label">In Wishlist</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Purchased -->
    <section class="section" id="recentPurchasesSection" style="display: none;">
        <div class="section-header">
            <h2 class="section-title">Recently Purchased</h2>
            <a href="{{ url('/purchased') }}" class="view-all">View All</a>
        </div>
        <div class="games-grid" id="recentPurchasesGrid">
            <!-- Data akan diisi oleh JavaScript -->
        </div>
    </section>

    <!-- New Releases -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">New Releases</h2>
            <a href="{{ url('/games') }}" class="view-all">Browse All</a>
        </div>
        <div class="games-grid" id="newGamesGrid">
            <div class="loading">Loading new games...</div>
        </div>
    </section>

    <!-- Popular Games -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Popular Games</h2>
            <a href="{{ url('/games') }}" class="view-all">See More</a>
        </div>
        <div class="games-grid" id="popularGamesGrid">
            <div class="loading">Loading popular games...</div>
        </div>
    </section>

    <!-- Special Offers -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Special Offers</h2>
            <a href="{{ url('/store') }}" class="view-all">View Store</a>
        </div>
        <div class="games-grid" id="saleGamesGrid">
            <div class="loading">Loading special offers...</div>
        </div>
    </section>
</div>

<style>
.home-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.welcome-section {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
    border-radius: var(--border-radius-lg);
    padding: 40px;
    margin-bottom: 40px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #60a5fa, #dbeafe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-subtitle {
    color: var(--text-gray);
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.username {
    color: var(--soft-blue);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--soft-blue);
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    font-size: 20px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-light);
    margin-bottom: 5px;
}

.stat-label {
    color: var(--text-gray);
    font-size: 0.9rem;
    font-weight: 500;
}

.section {
    margin-bottom: 50px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-light);
}

.view-all {
    color: var(--soft-blue);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    padding: 8px 16px;
    border: 1px solid var(--soft-blue);
    border-radius: var(--border-radius);
}

.view-all:hover {
    background: var(--soft-blue);
    color: var(--primary-dark);
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.game-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.game-card:hover {
    transform: translateY(-10px);
    border-color: var(--soft-blue);
    box-shadow: var(--shadow);
}

.game-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: var(--gradient-secondary);
}

.game-content {
    padding: 20px;
}

.game-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-developer {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 12px;
}

.game-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.game-price {
    font-weight: 700;
    color: var(--text-light);
}

.game-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--soft-blue);
    font-size: 0.9rem;
}

.discount-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.owned-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-gray);
    grid-column: 1 / -1;
}

.error-message {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 20px;
    border-radius: var(--border-radius);
    text-align: center;
    margin: 20px 0;
}

@media (max-width: 768px) {
    .home-container {
        padding: 20px 15px;
    }
    
    .welcome-section {
        padding: 30px 20px;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .games-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load home data
    loadHomeData();
    
    function loadHomeData() {
        fetch('/api/home-data', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update username
            document.getElementById('username').textContent = data.user.username;
            
            // Update stats
            document.getElementById('totalGames').textContent = data.user_stats.total_games;
            document.getElementById('totalSpent').textContent = formatCurrency(data.user_stats.total_spent);
            document.getElementById('wishlistCount').textContent = data.user_stats.wishlist_count;

            // Render recent purchases
            if (data.recent_purchases && data.recent_purchases.length > 0) {
                document.getElementById('recentPurchasesSection').style.display = 'block';
                renderGames(data.recent_purchases, 'recentPurchasesGrid', true);
            } else {
                document.getElementById('recentPurchasesSection').style.display = 'none';
            }

            // Render other sections
            renderGames(data.new_games, 'newGamesGrid');
            renderGames(data.popular_games, 'popularGamesGrid');
            renderGames(data.sale_games, 'saleGamesGrid');
        })
        .catch(error => {
            console.error('Error loading home data:', error);
            showError('Failed to load home data. Please try again.');
        });
    }

    function renderGames(games, containerId, isPurchased = false) {
        const container = document.getElementById(containerId);
        
        if (!games || games.length === 0) {
            container.innerHTML = '<div class="loading">No games found in this category</div>';
            return;
        }

        container.innerHTML = games.map(game => {
            const gameData = isPurchased ? game.game : game;
            if (!gameData) return '';
            
            const price = isPurchased ? 'Owned' : formatCurrency(gameData.price);
            const badge = isPurchased ? 
                '<span class="owned-badge">Owned</span>' : 
                (gameData.price < 100000 ? '<span class="discount-badge">Sale</span>' : '');

            return `
                <div class="game-card">
                    <img src="${gameData.image_url || getPlaceholderImage()}" 
                         alt="${gameData.title}" 
                         class="game-image"
                         onerror="this.src='${getPlaceholderImage()}'">
                    <div class="game-content">
                        <h3 class="game-title">${gameData.title || 'Untitled Game'}</h3>
                        <p class="game-developer">${gameData.developer || 'Unknown Developer'}</p>
                        <div class="game-footer">
                            <div class="game-price">${price}</div>
                            ${gameData.rating ? `
                                <div class="game-rating">
                                    <i class="fas fa-star"></i>
                                    ${gameData.rating.toFixed(1)}
                                </div>
                            ` : ''}
                        </div>
                        ${badge}
                    </div>
                </div>
            `;
        }).join('');
    }

    function formatCurrency(amount) {
        if (!amount) return 'Free';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    function getPlaceholderImage() {
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDI4MCAxODAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyODAiIGhlaWdodD0iMTgwIiBmaWxsPSJ1cmwoI2dyYWRpZW50KSIvPgo8dGV4dCB4PSIxNDAiIHk9IjkwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0Ij5HYW1lIEltYWdlPC90ZXh0Pgo8ZGVmcz4KPGxpbmVhckdyYWRpZW50IGlkPSJncmFkaWVudCIgeDE9IjAiIHkxPSIwIiB4Mj0iMjgwIiB5Mj0iMTgwIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iIzFmMjkzYiIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiMwZjE3MmEiLz4KPC9saW5lYXJHcmFkaWVudD4KPC9kZWZzPgo8L3N2Zz4K';
    }

    function showError(message) {
        const welcomeSection = document.querySelector('.welcome-section');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            ${message}
        `;
        welcomeSection.appendChild(errorDiv);
    }
});
</script>
@endsection