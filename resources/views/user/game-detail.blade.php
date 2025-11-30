@extends('layouts.user-navbar')

@section('title', $game->title . ' - GameHaven')

@section('content')
<div class="game-detail-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ url('/home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ url('/games') }}">Games</a>
        <i class="fas fa-chevron-right"></i>
        <span class="current">{{ $game->title }}</span>
    </nav>

    <!-- Game Hero Section -->
    <section class="game-hero">
        <div class="game-hero-content">
            <div class="game-image-section">
                <img src="{{ $game->image_url ?: $placeholderImage }}" 
                     alt="{{ $game->title }}" 
                     class="game-main-image"
                     onerror="this.src='{{ $placeholderImage }}'">
                
                <!-- Game Badges -->
                <div class="game-badges">
                    @if($game->price == 0)
                        <span class="game-badge free">FREE</span>
                    @elseif($game->price < 100000)
                        <span class="game-badge sale">SALE</span>
                    @endif
                    @if($game->rating >= 4.5)
                        <span class="game-badge popular">POPULAR</span>
                    @endif
                    @if($game->stock < 10 && $game->stock > 0)
                        <span class="game-badge limited">LIMITED</span>
                    @endif
                </div>
            </div>

            <div class="game-info-section">
                <h1 class="game-title">{{ $game->title }}</h1>
                <p class="game-developer">By {{ $game->developer }}</p>
                
                <div class="game-rating-section">
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($game->rating))
                                <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $game->rating)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="rating-value">{{ number_format($game->rating, 1) }}</span>
                    </div>
                    <span class="rating-count">Based on user reviews</span>
                </div>

                <div class="game-meta">
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span>{{ $game->category }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $game->release_date ? date('M d, Y', strtotime($game->release_date)) : 'Coming Soon' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-box"></i>
                        <span>{{ $game->stock }} in stock</span>
                    </div>
                </div>

                <div class="game-price-section">
                    @if($game->price == 0)
                        <div class="price-free">FREE</div>
                    @else
                        <div class="price-regular">{{ number_format($game->price, 0, ',', '.') }}</div>
                        @if($game->price < 100000)
                            <div class="price-discount">Save {{ number_format(100000 - $game->price, 0, ',', '.') }}</div>
                        @endif
                    @endif
                </div>

                <div class="game-actions">
                    @if($isPurchased)
                        <button class="btn btn-success purchased-btn" disabled>
                            <i class="fas fa-check"></i>
                            Already Purchased
                        </button>
                        <button class="btn btn-secondary download-btn" onclick="downloadGame({{ $game->id }})">
                            <i class="fas fa-download"></i>
                            Download Now
                        </button>
                    @else
                        <button class="btn btn-primary purchase-btn" onclick="purchaseGame({{ $game->id }})"
                                {{ $game->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $game->stock == 0 ? 'Out of Stock' : 'Buy Now' }}
                        </button>
                        <button class="btn btn-outline wishlist-btn" onclick="addToWishlist({{ $game->id }})">
                            <i class="fas fa-heart"></i>
                            Add to Wishlist
                        </button>
                    @endif
                </div>

                @if($game->stock == 0 && !$isPurchased)
                    <div class="stock-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        This game is currently out of stock. Check back later!
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Game Details Tabs -->
    <section class="game-details-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="description">Description</button>
            <button class="tab-btn" data-tab="specifications">Specifications</button>
            <button class="tab-btn" data-tab="reviews">Reviews</button>
        </div>

        <div class="tabs-content">
            <!-- Description Tab -->
            <div class="tab-pane active" id="description">
                <div class="description-content">
                    <p>{{ $game->description }}</p>
                    
                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="fas fa-gamepad"></i>
                            <h4>Immersive Gameplay</h4>
                            <p>Experience engaging mechanics and smooth controls</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-paint-brush"></i>
                            <h4>Stunning Graphics</h4>
                            <p>High-quality visuals and detailed environments</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <h4>Active Community</h4>
                            <p>Join thousands of players worldwide</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-sync"></i>
                            <h4>Regular Updates</h4>
                            <p>Continuous improvements and new content</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane" id="specifications">
                <div class="specs-grid">
                    <div class="specs-category">
                        <h4>Game Information</h4>
                        <div class="specs-list">
                            <div class="spec-item">
                                <span class="spec-label">Developer:</span>
                                <span class="spec-value">{{ $game->developer }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Publisher:</span>
                                <span class="spec-value">GameHaven Studios</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Release Date:</span>
                                <span class="spec-value">{{ $game->release_date ? date('F d, Y', strtotime($game->release_date)) : 'TBA' }}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Genre:</span>
                                <span class="spec-value">{{ $game->category }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="specs-category">
                        <h4>System Requirements</h4>
                        <div class="specs-list">
                            <div class="spec-item">
                                <span class="spec-label">OS:</span>
                                <span class="spec-value">Windows 10/11 64-bit</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Processor:</span>
                                <span class="spec-value">Intel Core i5 or equivalent</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Memory:</span>
                                <span class="spec-value">8 GB RAM</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Graphics:</span>
                                <span class="spec-value">NVIDIA GTX 1060 or equivalent</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Storage:</span>
                                <span class="spec-value">50 GB available space</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane" id="reviews">
                <div class="reviews-header">
                    <div class="overall-rating">
                        <div class="rating-score">{{ number_format($game->rating, 1) }}</div>
                        <div class="rating-stars">
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
                        <div class="rating-count">Based on 1,234 reviews</div>
                    </div>
                    
                    @if(!$isPurchased)
                        <button class="btn btn-outline" onclick="alert('Please purchase the game to leave a review')">
                            <i class="fas fa-edit"></i>
                            Write a Review
                        </button>
                    @else
                        <button class="btn btn-primary" onclick="openReviewModal()">
                            <i class="fas fa-edit"></i>
                            Write a Review
                        </button>
                    @endif
                </div>

                <div class="reviews-list">
                    <!-- Sample Reviews -->
                    <div class="review-item">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">JD</div>
                            <div class="reviewer-details">
                                <div class="reviewer-name">John Doe</div>
                                <div class="review-date">2 days ago</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= 5 ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <p>Absolutely amazing game! The graphics are stunning and the gameplay is super smooth. Definitely worth every penny!</p>
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">SJ</div>
                            <div class="reviewer-details">
                                <div class="reviewer-name">Sarah Johnson</div>
                                <div class="review-date">1 week ago</div>
                            </div>
                        </div>
                        <div class="review-content">
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= 4 ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <p>Great game with an engaging story. The characters are well-developed and the world feels alive. Some minor bugs but overall excellent experience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Similar Games Section -->
    <section class="similar-games">
        <h2 class="section-title">You Might Also Like</h2>
        <div class="similar-games-grid" id="similarGamesGrid">
            <!-- Similar games will be loaded here -->
        </div>
    </section>
</div>

<!-- Review Modal -->
<div class="modal" id="reviewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Write a Review</h3>
            <button class="modal-close" onclick="closeReviewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="rating-input">
                <span>Your Rating:</span>
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="far fa-star" data-rating="{{ $i }}"></i>
                    @endfor
                </div>
            </div>
            <textarea placeholder="Share your experience with this game..." class="review-textarea"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeReviewModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitReview()">Submit Review</button>
        </div>
    </div>
</div>

<style>
.game-detail-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    padding: 20px 32px 60px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 30px;
    font-size: 14px;
    color: var(--text-gray);
}

.breadcrumb a {
    color: var(--soft-blue);
    text-decoration: none;
    transition: var(--transition);
}

.breadcrumb a:hover {
    color: var(--accent-blue);
}

.breadcrumb .current {
    color: var(--text-light);
}

.breadcrumb i {
    font-size: 12px;
    color: var(--text-gray);
}

/* Game Hero Section */
.game-hero {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 40px;
    margin-bottom: 40px;
    backdrop-filter: blur(10px);
}

.game-hero-content {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 40px;
    align-items: start;
}

.game-image-section {
    position: relative;
}

.game-main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
}

.game-badges {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.game-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.game-badge.free {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.game-badge.sale {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.game-badge.popular {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.game-badge.limited {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
}

/* Game Info Section */
.game-info-section {
    padding: 20px 0;
}

.game-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-light);
    margin-bottom: 8px;
    line-height: 1.2;
}

.game-developer {
    font-size: 1.2rem;
    color: var(--soft-blue);
    margin-bottom: 24px;
}

.game-rating-section {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.rating-stars {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #fbbf24;
}

.rating-stars i {
    font-size: 18px;
}

.rating-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-light);
    margin-left: 8px;
}

.rating-count {
    color: var(--text-gray);
    font-size: 0.9rem;
}

.game-meta {
    display: flex;
    gap: 24px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-gray);
}

.meta-item i {
    color: var(--soft-blue);
}

.game-price-section {
    margin-bottom: 32px;
}

.price-free {
    font-size: 2rem;
    font-weight: 800;
    color: #10b981;
}

.price-regular {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-light);
}

.price-discount {
    color: #ef4444;
    font-weight: 600;
    margin-top: 4px;
}

.game-actions {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 16px 32px;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-secondary {
    background: rgba(59, 130, 246, 0.1);
    color: var(--soft-blue);
    border: 1px solid var(--soft-blue);
}

.btn-outline {
    background: transparent;
    color: var(--text-light);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--soft-blue);
}

.stock-warning {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 12px 16px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Tabs Section */
.game-details-tabs {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    margin-bottom: 40px;
    backdrop-filter: blur(10px);
}

.tabs-header {
    display: flex;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.tab-btn {
    padding: 20px 32px;
    background: none;
    border: none;
    color: var(--text-gray);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.tab-btn.active {
    color: var(--text-light);
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--gradient-primary);
}

.tab-btn:hover:not(.active) {
    color: var(--text-light);
    background: rgba(255, 255, 255, 0.05);
}

.tabs-content {
    padding: 40px;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Description Tab */
.description-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--text-light);
    margin-bottom: 32px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-top: 32px;
}

.feature-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 24px;
    text-align: center;
    transition: var(--transition);
}

.feature-item:hover {
    transform: translateY(-5px);
    border-color: var(--soft-blue);
}

.feature-item i {
    font-size: 2rem;
    color: var(--accent-blue);
    margin-bottom: 16px;
}

.feature-item h4 {
    color: var(--text-light);
    margin-bottom: 8px;
    font-size: 1.1rem;
}

.feature-item p {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin: 0;
}

/* Specifications Tab */
.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.specs-category h4 {
    color: var(--text-light);
    margin-bottom: 20px;
    font-size: 1.2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 8px;
}

.specs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.spec-item {
    display: flex;
    justify-content: between;
    padding: 8px 0;
}

.spec-label {
    color: var(--text-gray);
    font-weight: 500;
    flex: 1;
}

.spec-value {
    color: var(--text-light);
    flex: 2;
}

/* Reviews Tab */
.reviews-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.overall-rating {
    text-align: center;
}

.rating-score {
    font-size: 3rem;
    font-weight: 800;
    color: var(--text-light);
    line-height: 1;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.review-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 24px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.reviewer-name {
    color: var(--text-light);
    font-weight: 600;
}

.review-date {
    color: var(--text-gray);
    font-size: 0.9rem;
}

.review-rating {
    color: #fbbf24;
    margin-bottom: 8px;
}

.review-content p {
    color: var(--text-light);
    line-height: 1.5;
    margin: 0;
}

/* Similar Games */
.similar-games {
    margin-top: 60px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 24px;
}

.similar-games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
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
    z-index: 10000;
    backdrop-filter: blur(5px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: var(--secondary-dark);
    border-radius: var(--border-radius-lg);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-header {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-header h3 {
    color: var(--text-light);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-gray);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    color: var(--text-light);
}

.modal-body {
    padding: 24px;
}

.rating-input {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.star-rating {
    display: flex;
    gap: 4px;
}

.star-rating i {
    font-size: 1.5rem;
    color: #fbbf24;
    cursor: pointer;
    transition: var(--transition);
}

.star-rating i:hover {
    transform: scale(1.2);
}

.review-textarea {
    width: 100%;
    height: 150px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    padding: 16px;
    color: var(--text-light);
    font-size: 1rem;
    resize: vertical;
    outline: none;
}

.review-textarea:focus {
    border-color: var(--accent-blue);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .game-hero-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .game-main-image {
        height: 400px;
    }
}

@media (max-width: 768px) {
    .game-detail-container {
        padding: 15px 20px 40px;
    }
    
    .game-hero {
        padding: 30px 20px;
    }
    
    .game-title {
        font-size: 2rem;
    }
    
    .tabs-header {
        flex-direction: column;
    }
    
    .tab-btn {
        padding: 15px 20px;
        text-align: left;
    }
    
    .tabs-content {
        padding: 30px 20px;
    }
    
    .game-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .game-meta {
        flex-direction: column;
        gap: 12px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
    
    .reviews-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
}
</style>

<script>
// Set placeholder image
const placeholderImage = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDQwMCA1MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iNTAwIiBmaWxsPSJ1cmwoI2dyYWRpZW50KSIvPgo8dGV4dCB4PSIyMDAiIHk9IjI1MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0id2hpdGUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiI+R2FtZSBJbWFnZTwvdGV4dD4KPGRlZnM+CjxsaW5lYXJHcmFkaWVudCBpZD0iZ3JhZGllbnQiIHgxPSIwIiB5MT0iMCIgeDI9IjQwMCIgeTI9IjUwMCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiMxZjI5M2IiLz4KPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMGYxNzJhIi8+CjwvbGluZWFyR3JhZGllbnQ+CjwvZGVmcz4KPC9zdmc+';

document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            
            // Add active class to current button and pane
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Load similar games
    loadSimilarGames();

    // Star rating functionality for modal
    const stars = document.querySelectorAll('.star-rating i');
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.getAttribute('data-rating'));
            updateStarRating(selectedRating);
        });

        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            updateStarRating(rating, false);
        });
    });

    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        updateStarRating(selectedRating, false);
    });
});

function updateStarRating(rating, permanent = true) {
    const stars = document.querySelectorAll('.star-rating i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
    
    if (permanent) {
        selectedRating = rating;
    }
}

function openReviewModal() {
    document.getElementById('reviewModal').style.display = 'block';
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

async function purchaseGame(gameId) {
    try {
        const response = await fetch(`/api/user/games/${gameId}/purchase`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (response.ok) {
            showNotification('Game purchased successfully!', 'success');
            // Reload page to update purchase status
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification(data.error || 'Purchase failed', 'error');
        }
    } catch (error) {
        console.error('Purchase error:', error);
        showNotification('Network error. Please try again.', 'error');
    }
}

function downloadGame(gameId) {
    showNotification('Starting download...', 'success');
    // In real app, this would trigger the actual download
    console.log('Downloading game:', gameId);
}

function addToWishlist(gameId) {
    showNotification('Added to wishlist!', 'success');
    // In real app, this would call the wishlist API
    console.log('Adding to wishlist:', gameId);
}

function submitReview() {
    const reviewText = document.querySelector('.review-textarea').value;
    if (!selectedRating) {
        showNotification('Please select a rating', 'error');
        return;
    }

    if (!reviewText.trim()) {
        showNotification('Please write a review', 'error');
        return;
    }

    showNotification('Review submitted successfully!', 'success');
    closeReviewModal();
    
    // Reset form
    document.querySelector('.review-textarea').value = '';
    updateStarRating(0);
}

async function loadSimilarGames() {
    try {
        const response = await fetch('/api/public/games?per_page=4');
        const data = await response.json();
        const games = data.data || data;

        const container = document.getElementById('similarGamesGrid');
        
        if (games.length === 0) {
            container.innerHTML = '<p class="no-games">No similar games found</p>';
            return;
        }

        const gamesHTML = games.map(game => `
            <div class="game-card" onclick="window.location.href='/games/${game.id}'" style="cursor: pointer;">
                <div class="game-image-container">
                    <img src="${game.image_url || placeholderImage}" 
                         alt="${game.title}" 
                         class="game-image"
                         onerror="this.src='${placeholderImage}'">
                    <div class="game-badges">
                        ${game.price === 0 ? '<span class="game-badge badge-new">FREE</span>' : ''}
                        ${game.price < 100000 && game.price > 0 ? '<span class="game-badge badge-sale">SALE</span>' : ''}
                    </div>
                </div>
                <div class="game-content">
                    <h3 class="game-title">${game.title}</h3>
                    <p class="game-developer">${game.developer}</p>
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

        container.innerHTML = gamesHTML;
    } catch (error) {
        console.error('Error loading similar games:', error);
        document.getElementById('similarGamesGrid').innerHTML = '<p class="no-games">Error loading similar games</p>';
    }
}

function formatCurrency(amount) {
    if (!amount) return 'Free';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                      type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                      'linear-gradient(135deg, #3b82f6, #1d4ed8)'};
        color: white;
        padding: 16px 24px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
    `;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .no-games {
        text-align: center;
        color: var(--text-gray);
        padding: 40px;
        grid-column: 1 / -1;
    }
`;
document.head.appendChild(style);
</script>
@endsection