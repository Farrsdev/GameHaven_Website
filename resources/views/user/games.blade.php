@extends('layouts.user-navbar')

@section('title', 'Games - GameHaven')

@section('content')
<div class="games-page">
    <!-- Header -->
    <div class="page-header">
        <h1>Games</h1>
        <p>Discover your next favorite game</p>
        
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search games...">
        </div>
    </div>

    <!-- Main Content -->
    <div class="games-content">
        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label>Sort by:</label>
                <select id="sortSelect">
                    <option value="newest">Newest</option>
                    <option value="popular">Popular</option>
                    <option value="price">Price</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Price:</label>
                <select id="priceFilter">
                    <option value="all">All</option>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>

        <!-- Games Grid -->
        <div class="games-grid" id="gamesGrid">
            <div class="loading">Loading games...</div>
        </div>

        <!-- Load More -->
        <div class="load-more">
            <button id="loadMoreBtn">Load More</button>
        </div>
    </div>
</div>

<style>
.games-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.page-header {
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 2rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.page-header p {
    color: var(--text-secondary);
    margin-bottom: 25px;
}

.search-box {
    position: relative;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 14px;
    outline: none;
    transition: var(--transition);
}

.search-box input:focus {
    border-color: var(--accent);
}

.filters {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-group label {
    color: var(--text-secondary);
    font-size: 14px;
}

.filter-group select {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
    cursor: pointer;
    transition: var(--transition);
}

.filter-group select:focus {
    border-color: var(--accent);
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.game-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition);
    cursor: pointer;
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
    padding: 15px;
}

.game-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-developer {
    color: var(--text-secondary);
    font-size: 13px;
    margin-bottom: 10px;
}

.game-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.game-price {
    font-weight: 600;
    color: var(--text-primary);
}

.game-price.free {
    color: #10b981;
}

.game-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #fbbf24;
    font-size: 13px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: var(--text-secondary);
    grid-column: 1 / -1;
}

.load-more {
    text-align: center;
}

.load-more button {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    color: var(--text-primary);
    padding: 12px 30px;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
}

.load-more button:hover {
    border-color: var(--accent);
}

@media (max-width: 768px) {
    .games-page {
        padding: 20px 16px;
    }
    
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .filters {
        flex-direction: column;
        gap: 15px;
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
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;

    loadGames();

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', debounce(searchGames, 500));
    document.getElementById('sortSelect').addEventListener('change', reloadGames);
    document.getElementById('priceFilter').addEventListener('change', reloadGames);
    document.getElementById('loadMoreBtn').addEventListener('click', loadMoreGames);

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function searchGames() {
        currentPage = 1;
        hasMore = true;
        loadGames();
    }

    function reloadGames() {
        currentPage = 1;
        hasMore = true;
        loadGames();
    }

    async function loadGames() {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();

        try {
            const search = document.getElementById('searchInput').value;
            const sort = document.getElementById('sortSelect').value;
            const price = document.getElementById('priceFilter').value;

            const params = new URLSearchParams({
                search: search,
                sort: sort,
                price: price,
                page: currentPage,
                per_page: 12
            });

            const response = await fetch(`/api/public/games?${params}`);
            const data = await response.json();
            const games = data.data || data;

            displayGames(games);
            hasMore = games.length === 12;
            
        } catch (error) {
            console.error('Error loading games:', error);
            showError();
        } finally {
            isLoading = false;
            hideLoading();
        }
    }

    async function loadMoreGames() {
        if (isLoading || !hasMore) return;
        
        currentPage++;
        isLoading = true;

        try {
            const search = document.getElementById('searchInput').value;
            const sort = document.getElementById('sortSelect').value;
            const price = document.getElementById('priceFilter').value;

            const params = new URLSearchParams({
                search: search,
                sort: sort,
                price: price,
                page: currentPage,
                per_page: 12
            });

            const response = await fetch(`/api/public/games?${params}`);
            const data = await response.json();
            const games = data.data || data;

            displayGames(games, true);
            hasMore = games.length === 12;
            
        } catch (error) {
            console.error('Error loading more games:', error);
            currentPage--;
        } finally {
            isLoading = false;
        }
    }

    function displayGames(games, append = false) {
        const container = document.getElementById('gamesGrid');
        
        if (!append) {
            container.innerHTML = '';
        }

        if (games.length === 0 && !append) {
            container.innerHTML = '<div class="loading">No games found</div>';
            return;
        }

        const gamesHTML = games.map(game => `
            <div class="game-card" onclick="viewGame(${game.id})">
                <img src="${game.image_url || getPlaceholderImage()}" 
                     alt="${game.title}" 
                     class="game-image"
                     onerror="this.src='${getPlaceholderImage()}'">
                <div class="game-content">
                    <div class="game-title">${game.title}</div>
                    <div class="game-developer">${game.developer}</div>
                    <div class="game-footer">
                        <div class="game-price ${game.price === 0 ? 'free' : ''}">
                            ${game.price === 0 ? 'FREE' : formatCurrency(game.price)}
                        </div>
                        ${game.rating ? `
                            <div class="game-rating">
                                <i class="fas fa-star"></i>
                                ${game.rating.toFixed(1)}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');

        if (append) {
            container.innerHTML += gamesHTML;
        } else {
            container.innerHTML = gamesHTML;
        }

        // Show/hide load more button
        document.getElementById('loadMoreBtn').style.display = hasMore ? 'block' : 'none';
    }

    function showLoading() {
        document.getElementById('gamesGrid').innerHTML = '<div class="loading">Loading games...</div>';
    }

    function hideLoading() {
        // Handled in displayGames
    }

    function showError() {
        document.getElementById('gamesGrid').innerHTML = '<div class="loading">Error loading games</div>';
    }
});

function viewGame(gameId) {
    window.location.href = `/games/${gameId}`;
}

function formatCurrency(amount) {
    if (!amount) return 'Free';
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function getPlaceholderImage() {
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDI4MCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyODAiIGhlaWdodD0iMTYwIiBmaWxsPSIjMTExMTExIi8+Cjx0ZXh0IHg9IjE0MCIgeT0iODAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiMzMzMzMzMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiI+R2FtZSBJbWFnZTwvdGV4dD4KPC9zdmc+';
}
</script>
@endsection