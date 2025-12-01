@extends('layouts.user-navbar')

@section('title', $game->title . ' - GameHaven')

@section('content')
<div class="game-detail">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="/games">Games</a>
        <span>/</span>
        <span>{{ $game->title }}</span>
    </nav>

    <!-- Game Info -->
    <div class="game-main">
        <div class="game-image">
            <img src="{{ $game->image_url ?: $placeholderImage }}" 
                 alt="{{ $game->title }}"
                 onerror="this.src='{{ $placeholderImage }}'">
        </div>

        <div class="game-info">
            <h1>{{ $game->title }}</h1>
            <p class="developer">By {{ $game->developer }}</p>
            
            <div class="rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($game->rating))
                            <i class="fas fa-star"></i>
                        @elseif($i - 0.5 <= $game->rating)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span>{{ number_format($game->rating, 1) }}</span>
            </div>

            <div class="meta">
                <span>{{ $game->category }}</span>
                <span>{{ $game->release_date ? date('Y', strtotime($game->release_date)) : 'TBA' }}</span>
            </div>

            <div class="price">
                @if($game->price == 0)
                    <span class="free">FREE</span>
                @else
                    <span class="amount">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                @endif
            </div>

            <div class="actions">
                @if($isPurchased)
                    <button class="btn purchased" disabled>
                        <i class="fas fa-check"></i>
                        Purchased
                    </button>
                    <button class="btn download" onclick="downloadGame({{ $game->id }})">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                @else
                    <button class="btn primary" onclick="purchaseGame({{ $game->id }})"
                            {{ $game->stock == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                        {{ $game->stock == 0 ? 'Out of Stock' : 'Buy Now' }}
                    </button>
                    <button class="btn secondary" onclick="addToWishlist({{ $game->id }})">
                        <i class="fas fa-heart"></i>
                        Wishlist
                    </button>
                @endif
            </div>

            @if($game->stock == 0 && !$isPurchased)
                <div class="stock-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Out of stock
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    <div class="game-description">
        <h2>About</h2>
        <p>{{ $game->description }}</p>
    </div>

    <!-- Similar Games -->
    <div class="similar-games">
        <h2>Similar Games</h2>
        <div class="games-grid" id="similarGames">
            <!-- Similar games will load here -->
        </div>
    </div>
</div>

<style>
.game-detail {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
    font-size: 14px;
    color: var(--text-secondary);
}

.breadcrumb a {
    color: var(--accent);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.game-main {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 40px;
    margin-bottom: 40px;
}

.game-image img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 8px;
    background: var(--border);
}

.game-info h1 {
    font-size: 2rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.developer {
    color: var(--text-secondary);
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.rating {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.stars {
    color: #fbbf24;
}

.meta {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.meta span {
    background: var(--bg-secondary);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    color: var(--text-secondary);
}

.price {
    margin-bottom: 25px;
}

.free {
    font-size: 1.5rem;
    font-weight: 600;
    color: #10b981;
}

.amount {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.actions {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: none;
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn.primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn.primary:hover:not(:disabled) {
    opacity: 0.9;
}

.btn.secondary:hover {
    border-color: var(--accent);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn.purchased {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.stock-warning {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 12px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.game-description {
    margin-bottom: 50px;
}

.game-description h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.game-description p {
    line-height: 1.6;
    color: var(--text-secondary);
}

.similar-games h2 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    font-weight: 600;
}

@media (max-width: 1024px) {
    .game-main {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .game-image img {
        height: 400px;
    }
}

@media (max-width: 768px) {
    .game-detail {
        padding: 20px 16px;
    }
    
    .game-info h1 {
        font-size: 1.5rem;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSimilarGames();
});

async function loadSimilarGames() {
    try {
        const response = await fetch('/api/public/games?per_page=4');
        const data = await response.json();
        const games = data.data || data;

        const container = document.getElementById('similarGames');
        
        if (games.length === 0) {
            container.innerHTML = '<p>No similar games found</p>';
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
                    <div class="game-footer">
                        <div class="game-price ${game.price === 0 ? 'free' : ''}">
                            ${game.price === 0 ? 'FREE' : formatCurrency(game.price)}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = gamesHTML;
    } catch (error) {
        console.error('Error loading similar games:', error);
    }
}

function purchaseGame(gameId) {
    showNotification('Purchasing game...');
    // Implementation would go here
}

function downloadGame(gameId) {
    showNotification('Starting download...');
    // Implementation would go here
}

function addToWishlist(gameId) {
    showNotification('Added to wishlist!');
    // Implementation would go here
}

function viewGame(gameId) {
    window.location.href = `/games/${gameId}`;
}

function showNotification(message) {
    // Simple notification implementation
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--accent);
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        z-index: 1000;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
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