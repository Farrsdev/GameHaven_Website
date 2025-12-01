@extends('layouts.user-navbar')

@section('title', 'Store - GameHaven')

@section('content')
<div class="store-page">
    <!-- Header -->
    <div class="page-header">
        <h1>Store</h1>
        <p>Add games to your collection</p>
    </div>

    <!-- Cart Summary -->
    <div class="cart-summary" id="cartSummary">
        <div class="cart-info">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartCount">0 items</span>
            <span id="cartTotal">Rp 0</span>
        </div>
        <button class="btn primary" id="checkoutBtn" disabled>Checkout</button>
    </div>

    <!-- Games Grid -->
    <div class="store-content">
        <div class="games-section">
            <h2>Available Games</h2>
            <div class="games-grid" id="gamesGrid">
                <div class="loading">Loading games...</div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal" id="checkoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Checkout</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="checkoutItems">
                <!-- Items will be loaded here -->
            </div>
            <div class="total">
                Total: <span id="modalTotal">Rp 0</span>
            </div>
            
            <!-- Payment Method -->
            <div class="payment-section">
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
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeModal()">Cancel</button>
            <button class="btn primary" onclick="processPayment()">
                <i class="fas fa-lock"></i>
                Pay Now
            </button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal" id="successModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Payment Successful!</h3>
        </div>
        <div class="modal-body">
            <div style="text-align: center; padding: 20px;">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 20px;"></i>
                <h4>Thank you for your purchase!</h4>
                <p>Your games have been added to your library.</p>
                <div id="successDetails">
                    <!-- Success details will be loaded here -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeSuccessModal()">Continue Shopping</button>
            <a href="{{ url('/purchased') }}" class="btn primary">
                <i class="fas fa-gamepad"></i>
                View My Games
            </a>
        </div>
    </div>
</div>

<style>
.store-page {
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

/* Cart Summary */
.cart-summary {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-info i {
    color: var(--accent);
}

#cartCount {
    font-weight: 500;
}

#cartTotal {
    font-weight: 600;
    color: var(--accent);
}

/* Games Section */
.games-section h2 {
    font-size: 1.5rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
    height: 140px;
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
    margin-bottom: 10px;
}

.game-price {
    font-weight: 600;
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

.add-to-cart {
    width: 100%;
    padding: 8px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: var(--transition);
}

.add-to-cart:hover {
    opacity: 0.9;
}

.add-to-cart:disabled {
    background: var(--text-secondary);
    cursor: not-allowed;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: none;
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
}

.btn.primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn.primary:hover:not(:disabled) {
    opacity: 0.9;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    max-width: 400px;
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

.checkout-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.checkout-item:last-child {
    border-bottom: none;
}

.total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid var(--border);
    font-weight: 600;
    font-size: 1.1rem;
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

@media (max-width: 768px) {
    .store-page {
        padding: 20px 16px;
    }
    
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .cart-summary {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
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

.payment-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.payment-section h4 {
    margin-bottom: 15px;
    color: var(--text-light);
}

.payment-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.payment-option:hover {
    border-color: var(--accent);
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
}

.payment-option input:checked + .checkmark {
    border-color: var(--accent);
}

.payment-option input:checked + .checkmark::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: var(--accent);
    border-radius: 50%;
}

.payment-option i {
    color: var(--accent);
    width: 20px;
}
</style>

<script>
let cart = JSON.parse(localStorage.getItem('gamehaven_cart')) || [];

document.addEventListener('DOMContentLoaded', function() {
    loadGames();
    updateCartSummary();

    document.getElementById('checkoutBtn').addEventListener('click', openCheckoutModal);
});

async function loadGames() {
    try {
        const response = await fetch('/api/public/games?per_page=12');
        const data = await response.json();
        const games = data.data || data;

        const container = document.getElementById('gamesGrid');
        
        if (games.length === 0) {
            container.innerHTML = '<div class="loading">No games available</div>';
            return;
        }

        const gamesHTML = games.map(game => {
            const isInCart = cart.some(item => item.id === game.id);
            const isOutOfStock = game.stock === 0;
            
            return `
                <div class="game-card">
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
                        ${isOutOfStock ? 
                            `<button class="add-to-cart" disabled>Out of Stock</button>` :
                            `<button class="add-to-cart" 
                                onclick="addToCart(${JSON.stringify(game).replace(/"/g, '&quot;')})"
                                ${isInCart ? 'disabled' : ''}>
                                ${isInCart ? 'In Cart' : 'Add to Cart'}
                            </button>`
                        }
                        ${isOutOfStock ? `<div style="color: #ef4444; font-size: 12px; text-align: center; margin-top: 5px;">Out of stock</div>` : ''}
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = gamesHTML;
    } catch (error) {
        console.error('Error loading games:', error);
        document.getElementById('gamesGrid').innerHTML = '<div class="loading">Error loading games</div>';
    }
}

function addToCart(game) {
    const existingItem = cart.find(item => item.id === game.id);
    
    if (!existingItem) {
        cart.push({
            id: game.id,
            title: game.title,
            price: game.price,
            image_url: game.image_url,
            developer: game.developer,
            quantity: 1
        });
        
        saveCart();
        updateCartSummary();
        loadGames(); // Reload to update button states
        showNotification(`${game.title} added to cart`);
    }
}

function updateCartSummary() {
    const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
    const totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    
    document.getElementById('cartCount').textContent = `${itemCount} ${itemCount === 1 ? 'item' : 'items'}`;
    document.getElementById('cartTotal').textContent = formatCurrency(totalAmount);
    
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.disabled = itemCount === 0;
}

function openCheckoutModal() {
    if (cart.length === 0) return;
    
    const itemsHTML = cart.map(item => `
        <div class="checkout-item">
            <span>${item.title}</span>
            <span>${formatCurrency(item.price * item.quantity)}</span>
        </div>
    `).join('');
    
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('checkoutItems').innerHTML = itemsHTML;
    document.getElementById('modalTotal').textContent = formatCurrency(total);
    document.getElementById('checkoutModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('checkoutModal').style.display = 'none';
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    // Redirect to store page
    window.location.href = '/store';
}

async function processPayment() {
    if (cart.length === 0) return;
    
    const checkoutBtn = document.querySelector('#checkoutModal .btn.primary');
    checkoutBtn.disabled = true;
    checkoutBtn.innerHTML = '<i class="fas fa-sync fa-spin"></i> Processing...';
    
    try {
        // Prepare transaction data
        const transactionData = {
            user_id: {{ Session::get('user_id') }},
            games: cart.map(item => ({
                id: item.id,
                qty: item.quantity
            }))
        };
        
        console.log('Sending transaction data:', transactionData);
        
        // Send transaction to backend
        const response = await fetch('/api/transactions/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(transactionData)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.error || 'Payment failed');
        }
        
        if (!result.success) {
            throw new Error(result.error || 'Payment failed');
        }
        
        // Show success modal
        showSuccessModal(result.transaction, cart);
        
        // Clear cart
        cart = [];
        saveCart();
        updateCartSummary();
        loadGames(); // Reload games to update stock
        
    } catch (error) {
        console.error('Payment error:', error);
        showNotification(error.message || 'Payment failed. Please try again.', 'error');
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = '<i class="fas fa-lock"></i> Pay Now';
    }
}

function showSuccessModal(transaction, purchasedGames) {
    const successDetails = document.getElementById('successDetails');
    
    const detailsHTML = `
        <div style="text-align: left; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 6px; margin: 15px 0;">
            <div><strong>Transaction ID:</strong> #${transaction.id}</div>
            <div><strong>Total Paid:</strong> ${formatCurrency(transaction.total_price)}</div>
            <div><strong>Date:</strong> ${new Date(transaction.date).toLocaleDateString()}</div>
            <div><strong>Games Purchased:</strong></div>
            <ul style="margin: 10px 0; padding-left: 20px;">
                ${purchasedGames.map(game => `<li>${game.title}</li>`).join('')}
            </ul>
        </div>
    `;
    
    successDetails.innerHTML = detailsHTML;
    document.getElementById('checkoutModal').style.display = 'none';
    document.getElementById('successModal').style.display = 'block';
}

function showSuccessModal(transaction, purchasedGames) {
    const successDetails = document.getElementById('successDetails');
    
    const detailsHTML = `
        <div style="text-align: left; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 6px; margin: 15px 0;">
            <div><strong>Transaction ID:</strong> #${transaction.id}</div>
            <div><strong>Total Paid:</strong> ${formatCurrency(transaction.total_price)}</div>
            <div><strong>Games Purchased:</strong></div>
            <ul style="margin: 10px 0; padding-left: 20px;">
                ${purchasedGames.map(game => `<li>${game.title}</li>`).join('')}
            </ul>
        </div>
    `;
    
    successDetails.innerHTML = detailsHTML;
    document.getElementById('checkoutModal').style.display = 'none';
    document.getElementById('successModal').style.display = 'block';
}

function saveCart() {
    localStorage.setItem('gamehaven_cart', JSON.stringify(cart));
}

function formatCurrency(amount) {
    if (!amount) return 'Free';
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function getPlaceholderImage() {
    return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDI1MCAxNDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyNTAiIGhlaWdodD0iMTQwIiBmaWxsPSIjMTExMTExIi8+Cjx0ZXh0IHg9IjEyNSIgeT0iNzAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiMzMzMzMzMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiI+R2FtZSBJbWFnZTwvdGV4dD4KPC9zdmc+';
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