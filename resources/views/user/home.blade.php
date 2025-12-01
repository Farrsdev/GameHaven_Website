@extends('layouts.user-navbar')

@section('title', 'Dashboard - GameHaven')

@section('content')
<div class="dashboard">
    <!-- Welcome Section -->
    <section class="welcome">
        <h1>Welcome, {{ session('username') }}</h1>
        <p>Ready to play?</p>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-value" id="totalGames">0</div>
                <div class="stat-label">Games</div>
            </div>
            <div class="stat">
                <div class="stat-value" id="totalSpent">Rp 0</div>
                <div class="stat-label">Spent</div>
            </div>
            <div class="stat">
                <div class="stat-value" id="wishlistCount">0</div>
                <div class="stat-label">Wishlist</div>
            </div>
        </div>
    </section>

    <!-- Game Sections -->
    <section class="section">
        <div class="section-header">
            <h2>New Releases</h2>
            <a href="{{ url('/games') }}">View All</a>
        </div>
        <div class="games-grid" id="newGamesGrid">
            <div class="loading">Loading...</div>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2>Popular Games</h2>
            <a href="{{ url('/games') }}">See More</a>
        </div>
        <div class="games-grid" id="popularGamesGrid">
            <div class="loading">Loading...</div>
        </div>
    </section>
</div>

<style>
.dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.welcome {
    margin-bottom: 40px;
}

.welcome h1 {
    font-size: 2rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.welcome p {
    color: var(--text-secondary);
    margin-bottom: 25px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    max-width: 400px;
}

.stat {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.section {
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
}

.section-header a {
    color: var(--accent);
    text-decoration: none;
    font-size: 0.875rem;
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
    height: 120px;
    object-fit: cover;
    background: var(--border);
}

.game-content {
    padding: 15px;
}

.game-title {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-price {
    color: var(--text-secondary);
    font-size: 0.75rem;
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-secondary);
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .dashboard {
        padding: 20px 16px;
    }
    
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .game-content {
        padding: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple data loading
    loadDashboardData();
    
    function loadDashboardData() {
        fetch('/api/home-data')
            .then(response => response.json())
            .then(data => {
                // Update stats
                document.getElementById('totalGames').textContent = data.user_stats.total_games;
                document.getElementById('totalSpent').textContent = formatCurrency(data.user_stats.total_spent);
                document.getElementById('wishlistCount').textContent = data.user_stats.wishlist_count;

                // Render games
                renderGames(data.new_games, 'newGamesGrid');
                renderGames(data.popular_games, 'popularGamesGrid');
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
    }

    function renderGames(games, containerId) {
        const container = document.getElementById(containerId);
        
        if (!games || games.length === 0) {
            container.innerHTML = '<div class="loading">No games found</div>';
            return;
        }

        container.innerHTML = games.map(game => `
            <div class="game-card">
                <img src="${game.image_url || getPlaceholderImage()}" 
                     alt="${game.title}" 
                     class="game-image"
                     onerror="this.src='${getPlaceholderImage()}'">
                <div class="game-content">
                    <div class="game-title">${game.title}</div>
                    <div class="game-price">${formatCurrency(game.price)}</div>
                </div>
            </div>
        `).join('');
    }

    function formatCurrency(amount) {
        if (!amount) return 'Free';
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function getPlaceholderImage() {
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDIwMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMTIwIiBmaWxsPSIjMTExMTExIi8+Cjx0ZXh0IHg9IjEwMCIgeT0iNjAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiMzMzMzMzMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiI+R2FtZSBJbWFnZTwvdGV4dD4KPC9zdmc+';
    }
});
</script>
@endsection