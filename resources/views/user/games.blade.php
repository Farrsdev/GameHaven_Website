@extends('layouts.user-navbar')

@section('title', 'Games - GameHaven')

@section('content')
<div class="games-container">
    <!-- Hero Section -->
    <section class="games-hero">
        <div class="hero-content">
            <h1 class="hero-title">Discover Amazing Games</h1>
            <p class="hero-subtitle">Explore our collection of premium games and find your next adventure</p>
            
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search games, developers, categories..." class="search-input">
                    <button class="search-btn" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="games-main">
        <!-- Sidebar Filters -->
        <aside class="filters-sidebar">
            <div class="filter-section">
                <h3 class="filter-title">Filters</h3>
                
                <!-- Price Filter -->
                <div class="filter-group">
                    <h4 class="filter-group-title">Price Range</h4>
                    <div class="price-filters">
                        <label class="filter-option">
                            <input type="radio" name="price" value="all" checked>
                            <span class="checkmark"></span>
                            All Prices
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="price" value="free">
                            <span class="checkmark"></span>
                            Free
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="price" value="under100">
                            <span class="checkmark"></span>
                            Under Rp 100.000
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="price" value="under500">
                            <span class="checkmark"></span>
                            Under Rp 500.000
                        </label>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="filter-group">
                    <h4 class="filter-group-title">Categories</h4>
                    <div class="category-filters" id="categoryFilters">
                        <!-- Categories will be loaded dynamically -->
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="filter-group">
                    <h4 class="filter-group-title">Rating</h4>
                    <div class="rating-filters">
                        <label class="filter-option">
                            <input type="radio" name="rating" value="all" checked>
                            <span class="checkmark"></span>
                            All Ratings
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="rating" value="4.5">
                            <span class="checkmark"></span>
                            <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                4.5+
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="rating" value="4.0">
                            <span class="checkmark"></span>
                            <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                4.0+
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Clear Filters -->
                <button class="clear-filters-btn" id="clearFilters">
                    <i class="fas fa-times"></i>
                    Clear All Filters
                </button>
            </div>
        </aside>

        <!-- Games Grid -->
        <main class="games-content">
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-info">
                    <h2 class="results-title">All Games</h2>
                    <span class="results-count" id="resultsCount">Loading...</span>
                </div>
                <div class="sort-options">
                    <select id="sortSelect" class="sort-select">
                        <option value="newest">Newest First</option>
                        <option value="popular">Most Popular</option>
                        <option value="rating">Highest Rated</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Loading State -->
            <div class="loading-state" id="loadingState">
                <div class="loading-spinner"></div>
                <p>Loading games...</p>
            </div>

            <!-- Games Grid -->
            <div class="games-grid" id="gamesGrid">
                <!-- Games will be loaded here dynamically -->
            </div>

            <!-- Load More Button -->
            <div class="load-more-container" id="loadMoreContainer">
                <button class="load-more-btn" id="loadMoreBtn">
                    <i class="fas fa-redo"></i>
                    Load More Games
                </button>
            </div>

            <!-- No Results State -->
            <div class="no-results" id="noResults" style="display: none;">
                <i class="fas fa-gamepad"></i>
                <h3>No Games Found</h3>
                <p>Try adjusting your filters or search terms</p>
                <button class="reset-search-btn" id="resetSearchBtn">
                    Reset Search & Filters
                </button>
            </div>
        </main>
    </div>
</div>

<style>
.games-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
}

/* Hero Section */
.games-hero {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
    padding: 60px 32px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    background: linear-gradient(135deg, #60a5fa, #dbeafe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 16px;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: var(--text-gray);
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Search Box */
.search-container {
    max-width: 600px;
    margin: 0 auto;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-lg);
    padding: 8px;
    backdrop-filter: blur(10px);
    transition: var(--transition);
}

.search-box:focus-within {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    color: var(--text-gray);
    margin: 0 16px;
    font-size: 18px;
}

.search-input {
    flex: 1;
    background: none;
    border: none;
    color: var(--text-light);
    font-size: 16px;
    padding: 12px 0;
    outline: none;
}

.search-input::placeholder {
    color: var(--text-gray);
}

.search-btn {
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--border-radius);
    color: white;
    padding: 12px 20px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

/* Main Layout */
.games-main {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 32px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 32px;
}

/* Filters Sidebar */
.filters-sidebar {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 24px;
    height: fit-content;
    backdrop-filter: blur(10px);
    position: sticky;
    top: 100px;
}

.filter-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.filter-group {
    margin-bottom: 32px;
}

.filter-group-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 16px;
}

.filter-option {
    display: flex;
    align-items: center;
    padding: 12px 0;
    cursor: pointer;
    transition: var(--transition);
    border-radius: var(--border-radius);
    padding-left: 8px;
}

.filter-option:hover {
    background: rgba(255, 255, 255, 0.05);
}

.filter-option input {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--text-gray);
    border-radius: 4px;
    margin-right: 12px;
    position: relative;
    transition: var(--transition);
}

.filter-option input:checked + .checkmark {
    background: var(--accent-blue);
    border-color: var(--accent-blue);
}

.filter-option input:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 12px;
    font-weight: bold;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.stars {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #fbbf24;
}

.stars i {
    font-size: 14px;
}

.clear-filters-btn {
    width: 100%;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 12px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    margin-top: 16px;
}

.clear-filters-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: translateY(-2px);
}

/* Games Content */
.games-content {
    flex: 1;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.results-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-light);
}

.results-count {
    color: var(--text-gray);
    font-size: 1rem;
    margin-top: 4px;
}

.sort-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    color: var(--text-light);
    padding: 12px 16px;
    font-size: 14px;
    cursor: pointer;
    transition: var(--transition);
}

.sort-select:focus {
    outline: none;
    border-color: var(--accent-blue);
}

/* Games Grid */
.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.game-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    transition: var(--transition);
    backdrop-filter: blur(10px);
    position: relative;
}

.game-card:hover {
    transform: translateY(-8px);
    border-color: var(--soft-blue);
    box-shadow: var(--shadow);
}

.game-image-container {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.game-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.game-card:hover .game-image {
    transform: scale(1.05);
}

.game-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    gap: 8px;
}

.game-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-new {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-sale {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.badge-popular {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.game-content {
    padding: 20px;
}

.game-title {
    font-size: 1.2rem;
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

.game-description {
    color: var(--text-gray);
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.game-price {
    font-weight: 700;
    color: var(--text-light);
    font-size: 1.1rem;
}

.game-price.free {
    color: #10b981;
}

.game-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #fbbf24;
    font-size: 0.9rem;
    font-weight: 600;
}

.game-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    flex: 1;
    padding: 10px 16px;
    border: none;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-gray);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(59, 130, 246, 0.3);
    border-top: 3px solid var(--accent-blue);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Load More */
.load-more-container {
    text-align: center;
    margin-top: 40px;
}

.load-more-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: var(--text-light);
    padding: 12px 32px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
}

.load-more-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 80px 20px;
    color: var(--text-gray);
}

.no-results i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: var(--text-gray);
}

.no-results h3 {
    font-size: 1.5rem;
    margin-bottom: 12px;
    color: var(--text-light);
}

.reset-search-btn {
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    margin-top: 20px;
}

.reset-search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .games-main {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .filters-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .games-hero {
        padding: 40px 20px;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .games-main {
        padding: 20px;
    }

    .results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .games-grid {
        grid-template-columns: 1fr;
    }

    .hero-title {
        font-size: 2rem;
    }

    .search-box {
        flex-direction: column;
        gap: 12px;
    }

    .search-input {
        width: 100%;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let currentFilters = {
        search: '',
        price: 'all',
        category: 'all',
        rating: 'all',
        sort: 'newest'
    };

    // Initialize games page
    loadGames();
    loadCategories();

    // Event Listeners
    document.getElementById('searchInput').addEventListener('input', function(e) {
        currentFilters.search = e.target.value;
        debouncedSearch();
    });

    document.getElementById('searchBtn').addEventListener('click', performSearch);
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    document.querySelectorAll('input[name="price"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentFilters.price = this.value;
            resetAndLoadGames();
        });
    });

    document.querySelectorAll('input[name="rating"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentFilters.rating = this.value;
            resetAndLoadGames();
        });
    });

    document.getElementById('sortSelect').addEventListener('change', function() {
        currentFilters.sort = this.value;
        resetAndLoadGames();
    });

    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    document.getElementById('resetSearchBtn').addEventListener('click', clearFilters);
    document.getElementById('loadMoreBtn').addEventListener('click', loadMoreGames);

    // Functions
    function debouncedSearch() {
        clearTimeout(debouncedSearch.timeout);
        debouncedSearch.timeout = setTimeout(performSearch, 500);
    }

    function performSearch() {
        resetAndLoadGames();
    }

    function resetAndLoadGames() {
        currentPage = 1;
        hasMore = true;
        loadGames();
    }

    function clearFilters() {
        // Reset form elements
        document.querySelector('input[name="price"][value="all"]').checked = true;
        document.querySelector('input[name="rating"][value="all"]').checked = true;
        document.getElementById('sortSelect').value = 'newest';
        document.getElementById('searchInput').value = '';

        // Reset category filters
        document.querySelectorAll('.category-option input').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Reset filters object
        currentFilters = {
            search: '',
            price: 'all',
            category: 'all',
            rating: 'all',
            sort: 'newest'
        };

        resetAndLoadGames();
    }

    async function loadGames() {
        if (isLoading) return;
        
        isLoading = true;
        showLoadingState();

        try {
            // Build query parameters
            const params = new URLSearchParams();
            if (currentFilters.search) params.append('search', currentFilters.search);
            if (currentFilters.price !== 'all') params.append('price', currentFilters.price);
            if (currentFilters.category !== 'all') params.append('category', currentFilters.category);
            if (currentFilters.rating !== 'all') params.append('rating', currentFilters.rating);
            if (currentFilters.sort !== 'newest') params.append('sort', currentFilters.sort);
            params.append('page', currentPage);
            params.append('per_page', 12);

            const response = await fetch(`/api/public/games?${params}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const games = data.data || data; // Handle both paginated and simple response

            displayGames(games);
            updateResultsCount(data.total || games.length);
            
            // Check if there are more pages
            hasMore = currentPage < (data.last_page || 1);
            
        } catch (error) {
            console.error('Error loading games:', error);
            showErrorState();
        } finally {
            isLoading = false;
            hideLoadingState();
        }
    }

    async function loadMoreGames() {
        if (isLoading || !hasMore) return;
        
        currentPage++;
        isLoading = true;

        try {
            // Build query parameters
            const params = new URLSearchParams();
            if (currentFilters.search) params.append('search', currentFilters.search);
            if (currentFilters.price !== 'all') params.append('price', currentFilters.price);
            if (currentFilters.category !== 'all') params.append('category', currentFilters.category);
            if (currentFilters.rating !== 'all') params.append('rating', currentFilters.rating);
            if (currentFilters.sort !== 'newest') params.append('sort', currentFilters.sort);
            params.append('page', currentPage);
            params.append('per_page', 12);

            const response = await fetch(`/api/public/games?${params}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const newGames = data.data || data;

            displayGames(newGames, true); // Append to existing games
            
            // Check if there are more pages
            hasMore = currentPage < (data.last_page || 1);
            
            if (!hasMore) {
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                loadMoreBtn.innerHTML = '<i class="fas fa-check"></i> All Games Loaded';
                loadMoreBtn.disabled = true;
            }
            
        } catch (error) {
            console.error('Error loading more games:', error);
            currentPage--; // Revert page on error
        } finally {
            isLoading = false;
        }
    }

    async function loadCategories() {
        try {
            // Try to get categories from API
            const response = await fetch('/api/public/games');
            const data = await response.json();
            const games = data.data || data;
            
            // Extract unique categories from games
            const categories = [...new Set(games.map(game => game.category).filter(Boolean))];
            
            // If no categories from API, use default ones
            const finalCategories = categories.length > 0 ? categories : [
                'Action', 'Adventure', 'RPG', 'Strategy', 'Simulation',
                'Sports', 'Racing', 'Puzzle', 'Horror', 'Indie'
            ];

            const container = document.getElementById('categoryFilters');
            container.innerHTML = '';

            finalCategories.forEach(category => {
                const label = document.createElement('label');
                label.className = 'filter-option category-option';
                label.innerHTML = `
                    <input type="checkbox" name="category" value="${category.toLowerCase()}">
                    <span class="checkmark"></span>
                    ${category}
                `;
                
                label.querySelector('input').addEventListener('change', function() {
                    // Handle multiple category selection
                    const selectedCategories = Array.from(document.querySelectorAll('.category-option input:checked'))
                        .map(input => input.value);
                    
                    currentFilters.category = selectedCategories.length > 0 ? selectedCategories[0] : 'all';
                    resetAndLoadGames();
                });
                
                container.appendChild(label);
            });
        } catch (error) {
            console.error('Error loading categories:', error);
            // Fallback to default categories
            loadDefaultCategories();
        }
    }

    function loadDefaultCategories() {
        const categories = [
            'Action', 'Adventure', 'RPG', 'Strategy', 'Simulation',
            'Sports', 'Racing', 'Puzzle', 'Horror', 'Indie'
        ];

        const container = document.getElementById('categoryFilters');
        container.innerHTML = '';

        categories.forEach(category => {
            const label = document.createElement('label');
            label.className = 'filter-option category-option';
            label.innerHTML = `
                <input type="checkbox" name="category" value="${category.toLowerCase()}">
                <span class="checkmark"></span>
                ${category}
            `;
            
            label.querySelector('input').addEventListener('change', function() {
                currentFilters.category = this.checked ? this.value : 'all';
                resetAndLoadGames();
            });
            
            container.appendChild(label);
        });
    }

    function displayGames(games, append = false) {
        const container = document.getElementById('gamesGrid');
        
        if (!append || currentPage === 1) {
            container.innerHTML = '';
        }

        if (games.length === 0 && !append) {
            showNoResults();
            return;
        }

        hideNoResults();

        const gamesHTML = games.map(game => `
            <div class="game-card">
                <div class="game-image-container">
                    <img src="${game.image_url || getPlaceholderImage()}" 
                         alt="${game.title}" 
                         class="game-image"
                         onerror="this.src='${getPlaceholderImage()}'">
                    <div class="game-badges">
                        ${game.price === 0 ? '<span class="game-badge badge-new">FREE</span>' : ''}
                        ${game.price < 100000 && game.price > 0 ? '<span class="game-badge badge-sale">SALE</span>' : ''}
                        ${game.rating >= 4.5 ? '<span class="game-badge badge-popular">POPULAR</span>' : ''}
                        ${isNewGame(game.created_at) ? '<span class="game-badge badge-new">NEW</span>' : ''}
                    </div>
                </div>
                <div class="game-content">
                    <h3 class="game-title">${game.title || 'Untitled Game'}</h3>
                    <p class="game-developer">${game.developer || 'Unknown Developer'}</p>
                    <p class="game-description">${game.description || 'Embark on an epic adventure in this amazing game.'}</p>
                    
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
                    
                    <div class="game-actions">
                        <button class="action-btn btn-primary" onclick="viewGame(${game.id})">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                        <button class="action-btn btn-secondary" onclick="addToWishlist(${game.id})">
                            <i class="fas fa-heart"></i>
                        </button>
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
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        loadMoreContainer.style.display = hasMore ? 'block' : 'none';
    }

    function updateResultsCount(count) {
        const resultsCount = document.getElementById('resultsCount');
        resultsCount.textContent = `${count} ${count === 1 ? 'game' : 'games'} found`;
    }

    function showLoadingState() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('gamesGrid').style.display = 'none';
        document.getElementById('loadMoreContainer').style.display = 'none';
        document.getElementById('noResults').style.display = 'none';
    }

    function hideLoadingState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('gamesGrid').style.display = 'grid';
    }

    function showNoResults() {
        document.getElementById('gamesGrid').style.display = 'none';
        document.getElementById('loadMoreContainer').style.display = 'none';
        document.getElementById('noResults').style.display = 'block';
    }

    function hideNoResults() {
        document.getElementById('noResults').style.display = 'none';
    }

    function showErrorState() {
        const container = document.getElementById('gamesGrid');
        container.innerHTML = `
            <div class="no-results" style="display: block; grid-column: 1 / -1;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error Loading Games</h3>
                <p>Please try again later</p>
                <button class="reset-search-btn" onclick="location.reload()">
                    Reload Page
                </button>
            </div>
        `;
    }

    function isNewGame(createdAt) {
        if (!createdAt) return false;
        const createdDate = new Date(createdAt);
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
        return createdDate > oneWeekAgo;
    }
});

// Utility functions
function formatCurrency(amount) {
    if (!amount) return 'Free';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function getPlaceholderImage() {
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSJ1cmwoI2dyYWRpZW50KSIvPgo8dGV4dCB4PSIxNTAiIHk9IjEwMCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0id2hpdGUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCI+R2FtZSBJbWFnZTwvdGV4dD4KPGRlZnM+CjxsaW5lYXJHcmFkaWVudCBpZD0iZ3JhZGllbnQiIHgxPSIwIiB5MT0iMCIgeDI9IjMwMCIgeTI9IjIwMCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiMxZjI5M2IiLz4KPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMGYxNzJhIi8+CjwvbGluZWFyR3JhZGllbnQ+CjwvZGVmcz4KPC9zdmc+';
}

// Action functions
function viewGame(gameId) {
    // Navigate to game detail page
    window.location.href = `/games/${gameId}`;
}

function addToWishlist(gameId) {
    // Show notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--gradient-primary);
        color: white;
        padding: 12px 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    notification.innerHTML = `<i class="fas fa-heart"></i> Added to wishlist!`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
    
    // In real app, you would make API call here
    console.log('Adding game to wishlist:', gameId);
}
</script>
@endsection