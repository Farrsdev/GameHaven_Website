@extends('layouts.user-navbar')

@section('title', 'Store - GameHaven')

@section('content')
<div class="store-container">
    <!-- Hero Section -->
    <section class="store-hero">
        <div class="hero-content">
            <h1 class="hero-title">Game Store</h1>
            <p class="hero-subtitle">Purchase amazing games and expand your collection</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="store-main">
        <!-- Cart Section -->
        <div class="cart-section" id="cartSection">
            <div class="cart-header">
                <h2 class="cart-title">Your Cart</h2>
                <div class="cart-actions">
                    <button class="btn btn-outline" id="clearCartBtn">
                        <i class="fas fa-trash"></i>
                        Clear Cart
                    </button>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div class="empty-cart" id="emptyCart">
                <div class="empty-cart-content">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Browse our games and add some to your cart!</p>
                    <a href="{{ url('/games') }}" class="btn btn-primary">
                        <i class="fas fa-gamepad"></i>
                        Browse Games
                    </a>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="cart-items" id="cartItems" style="display: none;">
                <div class="cart-items-list" id="cartItemsList">
                    <!-- Cart items will be loaded here -->
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (10%):</span>
                        <span id="tax">Rp 0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">Rp 0</span>
                    </div>

                    <button class="btn btn-primary checkout-btn" id="checkoutBtn">
                        <i class="fas fa-credit-card"></i>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Games -->
        <div class="featured-games">
            <div class="section-header">
                <h2 class="section-title">Featured Games</h2>
                <a href="{{ url('/games') }}" class="view-all">View All Games</a>
            </div>

            <div class="featured-grid" id="featuredGrid">
                <!-- Featured games will be loaded here -->
            </div>
        </div>

        <!-- Special Offers -->
        <div class="special-offers">
            <div class="section-header">
                <h2 class="section-title">Special Offers</h2>
            </div>

            <div class="offers-grid" id="offersGrid">
                <!-- Special offers will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal" id="checkoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Checkout</h3>
            <button class="modal-close" onclick="closeCheckoutModal()">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Order Summary -->
            <div class="checkout-summary">
                <h4>Order Summary</h4>
                <div class="order-items" id="orderItems">
                    <!-- Order items will be loaded here -->
                </div>
                <div class="order-total">
                    <div class="total-row">
                        <span>Total:</span>
                        <span id="orderTotal">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="payment-method">
                <h4>Payment Method</h4>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment" value="credit_card" checked>
                        <span class="checkmark"></span>
                        <i class="fas fa-credit-card"></i>
                        <span>Credit Card</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="bank_transfer">
                        <span class="checkmark"></span>
                        <i class="fas fa-university"></i>
                        <span>Bank Transfer</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="ewallet">
                        <span class="checkmark"></span>
                        <i class="fas fa-mobile-alt"></i>
                        <span>E-Wallet</span>
                    </label>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="payment-details">
                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date</label>
                        <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" placeholder="123" maxlength="3">
                    </div>
                </div>
                <div class="form-group">
                    <label for="cardName">Cardholder Name</label>
                    <input type="text" id="cardName" placeholder="John Doe">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeCheckoutModal()">Cancel</button>
            <button class="btn btn-primary" onclick="processPayment()">
                <i class="fas fa-lock"></i>
                Pay Now
            </button>
        </div>
    </div>
</div>

<style>
.store-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
}

/* Hero Section */
.store-hero {
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
    max-width: 600px;
    margin: 0 auto;
}

/* Main Content */
.store-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 32px;
}

/* Cart Section */
.cart-section {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 32px;
    margin-bottom: 40px;
    backdrop-filter: blur(10px);
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.cart-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-light);
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 60px 20px;
}

.empty-cart-content i {
    font-size: 4rem;
    color: var(--text-gray);
    margin-bottom: 20px;
}

.empty-cart-content h3 {
    font-size: 1.5rem;
    color: var(--text-light);
    margin-bottom: 12px;
}

.empty-cart-content p {
    color: var(--text-gray);
    margin-bottom: 24px;
}

/* Cart Items */
.cart-items-list {
    margin-bottom: 32px;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    margin-bottom: 16px;
    transition: var(--transition);
}

.cart-item:hover {
    border-color: var(--soft-blue);
    background: rgba(255, 255, 255, 0.08);
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius);
}

.cart-item-details {
    flex: 1;
}

.cart-item-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 4px;
}

.cart-item-developer {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.cart-item-price {
    font-weight: 700;
    color: var(--text-light);
    font-size: 1.1rem;
}

.cart-item-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 8px 12px;
}

.quantity-btn {
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: var(--transition);
}

.quantity-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.quantity-value {
    font-weight: 600;
    color: var(--text-light);
    min-width: 30px;
    text-align: center;
}

.remove-btn {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 8px 12px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
}

.remove-btn:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* Cart Summary */
.cart-summary {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 24px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    color: var(--text-light);
}

.summary-row.total {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: 12px;
    padding-top: 16px;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-blue);
}

.checkout-btn {
    width: 100%;
    margin-top: 20px;
    padding: 16px;
    font-size: 1.1rem;
}

/* Featured Games & Special Offers */
.featured-games, .special-offers {
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-light);
}

.view-all {
    color: var(--soft-blue);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.view-all:hover {
    color: var(--accent-blue);
}

.featured-grid, .offers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

.store-game-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.store-game-card:hover {
    transform: translateY(-5px);
    border-color: var(--soft-blue);
    box-shadow: var(--shadow);
}

.store-game-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
}

.store-game-content {
    padding: 20px;
}

.store-game-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.store-game-developer {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 12px;
}

.store-game-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.store-game-price {
    font-weight: 700;
    color: var(--text-light);
}

.store-game-price.free {
    color: #10b981;
}

.store-game-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #fbbf24;
    font-size: 0.9rem;
}

.add-to-cart-btn {
    width: 100%;
    padding: 10px;
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
}

/* Checkout Modal */
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
    justify-content: space-between;
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

.checkout-summary {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.checkout-summary h4 {
    color: var(--text-light);
    margin-bottom: 16px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    color: var(--text-light);
}

.order-item:not(:last-child) {
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.order-total {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--accent-blue);
}

.payment-method {
    margin-bottom: 24px;
}

.payment-method h4 {
    color: var(--text-light);
    margin-bottom: 16px;
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
}

.payment-option:hover {
    border-color: var(--soft-blue);
    background: rgba(255, 255, 255, 0.08);
}

.payment-option input {
    display: none;
}

.payment-option .checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--text-gray);
    border-radius: 50%;
    position: relative;
    transition: var(--transition);
}

.payment-option input:checked + .checkmark {
    border-color: var(--accent-blue);
    background: var(--accent-blue);
}

.payment-option input:checked + .checkmark::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
}

.payment-option i {
    color: var(--soft-blue);
    width: 20px;
    text-align: center;
}

.payment-details {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 16px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-light);
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    color: var(--text-light);
    font-size: 1rem;
    transition: var(--transition);
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group input::placeholder {
    color: var(--text-gray);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .store-main {
        padding: 20px;
    }
    
    .cart-section {
        padding: 24px 20px;
    }
    
    .cart-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .cart-item-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .featured-grid, .offers-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .store-hero {
        padding: 40px 20px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .featured-grid, .offers-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
}
</style>

<script>
// Cart functionality
let cart = JSON.parse(localStorage.getItem('gamehaven_cart')) || [];

document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    loadFeaturedGames();
    loadSpecialOffers();

    // Event listeners
    document.getElementById('clearCartBtn').addEventListener('click', clearCart);
    document.getElementById('checkoutBtn').addEventListener('click', openCheckoutModal);
});

function loadCart() {
    const emptyCart = document.getElementById('emptyCart');
    const cartItems = document.getElementById('cartItems');
    const cartItemsList = document.getElementById('cartItemsList');

    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        cartItems.style.display = 'none';
        return;
    }

    emptyCart.style.display = 'none';
    cartItems.style.display = 'block';

    // Render cart items
    cartItemsList.innerHTML = cart.map(item => `
        <div class="cart-item" data-game-id="${item.id}">
            <img src="${item.image_url || getPlaceholderImage()}" 
                 alt="${item.title}" 
                 class="cart-item-image"
                 onerror="this.src='${getPlaceholderImage()}'">
            <div class="cart-item-details">
                <h3 class="cart-item-title">${item.title}</h3>
                <p class="cart-item-developer">${item.developer}</p>
                <div class="cart-item-price">${formatCurrency(item.price)}</div>
            </div>
            <div class="cart-item-actions">
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="decreaseQuantity(${item.id})">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="quantity-value">${item.quantity}</span>
                    <button class="quantity-btn" onclick="increaseQuantity(${item.id})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button class="remove-btn" onclick="removeFromCart(${item.id})">
                    <i class="fas fa-trash"></i>
                    Remove
                </button>
            </div>
        </div>
    `).join('');

    updateCartSummary();
}

function updateCartSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1; // 10% tax
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('tax').textContent = formatCurrency(tax);
    document.getElementById('total').textContent = formatCurrency(total);
}

function addToCart(game) {
    const existingItem = cart.find(item => item.id === game.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            ...game,
            quantity: 1
        });
    }

    saveCart();
    loadCart();
    showNotification(`${game.title} added to cart!`, 'success');
}

function removeFromCart(gameId) {
    cart = cart.filter(item => item.id !== gameId);
    saveCart();
    loadCart();
    showNotification('Item removed from cart', 'info');
}

function increaseQuantity(gameId) {
    const item = cart.find(item => item.id === gameId);
    if (item) {
        item.quantity += 1;
        saveCart();
        loadCart();
    }
}

function decreaseQuantity(gameId) {
    const item = cart.find(item => item.id === gameId);
    if (item && item.quantity > 1) {
        item.quantity -= 1;
        saveCart();
        loadCart();
    } else {
        removeFromCart(gameId);
    }
}

function clearCart() {
    if (cart.length === 0) return;
    
    if (confirm('Are you sure you want to clear your cart?')) {
        cart = [];
        saveCart();
        loadCart();
        showNotification('Cart cleared', 'info');
    }
}

function saveCart() {
    localStorage.setItem('gamehaven_cart', JSON.stringify(cart));
}

async function loadFeaturedGames() {
    try {
        const response = await fetch('/api/public/games?per_page=4');
        const data = await response.json();
        const games = data.data || data;

        const container = document.getElementById('featuredGrid');
        
        if (games.length === 0) {
            container.innerHTML = '<p class="no-games">No featured games available</p>';
            return;
        }

        const gamesHTML = games.map(game => `
            <div class="store-game-card">
                <img src="${game.image_url || getPlaceholderImage()}" 
                     alt="${game.title}" 
                     class="store-game-image"
                     onerror="this.src='${getPlaceholderImage()}'">
                <div class="store-game-content">
                    <h3 class="store-game-title">${game.title}</h3>
                    <p class="store-game-developer">${game.developer}</p>
                    <div class="store-game-footer">
                        <div class="store-game-price ${game.price === 0 ? 'free' : ''}">
                            ${game.price === 0 ? 'FREE' : formatCurrency(game.price)}
                        </div>
                        ${game.rating ? `
                            <div class="store-game-rating">
                                <i class="fas fa-star"></i>
                                ${game.rating.toFixed(1)}
                            </div>
                        ` : ''}
                    </div>
                    <button class="add-to-cart-btn" onclick="addToCart(${JSON.stringify(game).replace(/"/g, '&quot;')})">
                        <i class="fas fa-cart-plus"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        `).join('');

        container.innerHTML = gamesHTML;
    } catch (error) {
        console.error('Error loading featured games:', error);
        document.getElementById('featuredGrid').innerHTML = '<p class="no-games">Error loading featured games</p>';
    }
}

async function loadSpecialOffers() {
    try {
        const response = await fetch('/api/public/games?price=under100&per_page=4');
        const data = await response.json();
        const games = data.data || data;

        const container = document.getElementById('offersGrid');
        
        if (games.length === 0) {
            container.innerHTML = '<p class="no-games">No special offers available</p>';
            return;
        }

        const gamesHTML = games.map(game => `
            <div class="store-game-card">
                <img src="${game.image_url || getPlaceholderImage()}" 
                     alt="${game.title}" 
                     class="store-game-image"
                     onerror="this.src='${getPlaceholderImage()}'">
                <div class="store-game-content">
                    <h3 class="store-game-title">${game.title}</h3>
                    <p class="store-game-developer">${game.developer}</p>
                    <div class="store-game-footer">
                        <div class="store-game-price ${game.price === 0 ? 'free' : ''}">
                            ${game.price === 0 ? 'FREE' : formatCurrency(game.price)}
                        </div>
                        ${game.rating ? `
                            <div class="store-game-rating">
                                <i class="fas fa-star"></i>
                                ${game.rating.toFixed(1)}
                            </div>
                        ` : ''}
                    </div>
                    <button class="add-to-cart-btn" onclick="addToCart(${JSON.stringify(game).replace(/"/g, '&quot;')})">
                        <i class="fas fa-cart-plus"></i>
                        Add to Cart
                    </button>
                </div>
            </div>
        `).join('');

        container.innerHTML = gamesHTML;
    } catch (error) {
        console.error('Error loading special offers:', error);
        document.getElementById('offersGrid').innerHTML = '<p class="no-games">Error loading special offers</p>';
    }
}

function openCheckoutModal() {
    if (cart.length === 0) {
        showNotification('Your cart is empty', 'error');
        return;
    }

    const orderItems = document.getElementById('orderItems');
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 1.1;

    orderItems.innerHTML = cart.map(item => `
        <div class="order-item">
            <span>${item.title} × ${item.quantity}</span>
            <span>${formatCurrency(item.price * item.quantity)}</span>
        </div>
    `).join('');

    document.getElementById('orderTotal').textContent = formatCurrency(total);
    document.getElementById('checkoutModal').style.display = 'block';
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').style.display = 'none';
}

function processPayment() {
    // Validate form
    const cardNumber = document.getElementById('cardNumber').value;
    const expiryDate = document.getElementById('expiryDate').value;
    const cvv = document.getElementById('cvv').value;
    const cardName = document.getElementById('cardName').value;

    if (!cardNumber || !expiryDate || !cvv || !cardName) {
        showNotification('Please fill in all payment details', 'error');
        return;
    }

    // Simulate payment processing
    showNotification('Processing payment...', 'info');
    
    setTimeout(() => {
        showNotification('Payment successful! Your games are being processed.', 'success');
        closeCheckoutModal();
        
        // Clear cart after successful payment
        cart = [];
        saveCart();
        loadCart();
        
        // In real app, you would redirect to order confirmation page
    }, 2000);
}

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
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgwIiBoZWlnaHQ9IjE2MCIgdmlld0JveD0iMCAwIDI4MCAxNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyODAiIGhlaWdodD0iMTYwIiBmaWxsPSJ1cmwoI2dyYWRpZW50KSIvPgo8dGV4dCB4PSIxNDAiIHk9IjgwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0Ij5HYW1lIEltYWdlPC90ZXh0Pgo8ZGVmcz4KPGxpbmVhckdyYWRpZW50IGlkPSJncmFkaWVudCIgeDE9IjAiIHkxPSIwIiB4Mj0iMjgwIiB5Mj0iMTYwIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iIzFmMjkzYiIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiMwZjE3MmEiLz4KPC9saW5lYXJHcmFkaWVudD4KPC9kZWZzPgo8L3N2Zz4=';
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