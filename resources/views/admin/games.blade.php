@extends('layouts.admin-navbar')

@section('content')
<div class="main-content">
    <div class="content-header">
        <div class="header-title">
            <h1 class="page-title">Game Management</h1>
            <p class="page-subtitle">Manage your game catalog, add new games, and update existing ones</p>
        </div>
        <button class="btn-primary" onclick="openAddGameModal()">
            <i class="fas fa-plus"></i>
            Add New Game
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-gamepad"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="totalGames">0</h3>
                <p class="stat-label">Total Games</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="totalStock">0</h3>
                <p class="stat-label">Total Stock</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="totalCategories">0</h3>
                <p class="stat-label">Categories</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="lowStockGames">0</h3>
                <p class="stat-label">Low Stock Games</p>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search games by title, developer, or category...">
        </div>
        <div class="filter-controls">
            <select id="categoryFilter" class="filter-select">
                <option value="">All Categories</option>
            </select>
            <select id="sortBy" class="filter-select">
                <option value="title">Sort by Title</option>
                <option value="price">Sort by Price</option>
                <option value="stock">Sort by Stock</option>
                <option value="release_date">Sort by Release Date</option>
            </select>
        </div>
    </div>

    <!-- Games Table -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="games-table">
                <thead>
                    <tr>
                        <th width="250">Game</th>
                        <th width="120">Category</th>
                        <th width="150">Developer</th>
                        <th width="100">Price</th>
                        <th width="80">Stock</th>
                        <th width="100">Rating</th>
                        <th width="120">Release Date</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody id="gamesTableBody">
                    <!-- Games will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div id="loadingState" class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading games...</p>
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fas fa-gamepad"></i>
            <h3>No Games Found</h3>
            <p>Get started by adding your first game to the catalog.</p>
            <button class="btn-primary" onclick="openAddGameModal()">
                <i class="fas fa-plus"></i>
                Add First Game
            </button>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container" id="paginationContainer" style="display: none;">
            <div class="pagination-controls">
                <button id="prevPage" class="pagination-btn" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="pageInfo" class="page-info">Page 1 of 1</span>
                <button id="nextPage" class="pagination-btn" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Game Modal -->
<div id="gameModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Game</h3>
            <button class="modal-close" onclick="closeGameModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="gameForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="gameId" name="id">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Game Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Action">Action</option>
                        <option value="Adventure">Adventure</option>
                        <option value="RPG">RPG</option>
                        <option value="Strategy">Strategy</option>
                        <option value="Sports">Sports</option>
                        <option value="Racing">Racing</option>
                        <option value="Simulation">Simulation</option>
                        <option value="Puzzle">Puzzle</option>
                        <option value="Horror">Horror</option>
                        <option value="Indie">Indie</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="developer">Developer *</label>
                    <input type="text" id="developer" name="developer" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (Rp) *</label>
                    <input type="number" id="price" name="price" step="1000" min="0" placeholder="0" required>
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" min="0" placeholder="0" required>
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating (1-5)</label>
                    <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" placeholder="0.0">
                </div>
                
                <div class="form-group">
                    <label for="release_date">Release Date</label>
                    <input type="date" id="release_date" name="release_date">
                </div>
                
                <div class="form-group full-width">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter game description..." required></textarea>
                </div>
                
                <!-- Image Upload -->
                <div class="form-group full-width">
                    <label for="image_upload">Game Image *</label>
                    <div class="file-upload-container">
                        <input type="file" id="image_upload" name="image_upload" accept="image/*" class="file-input">
                        <label for="image_upload" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span class="file-text">Choose image file</span>
                            <span class="file-name" id="imageFileName">No file chosen</span>
                        </label>
                    </div>
                    <small>Supported formats: JPG, PNG, GIF. Max size: 5MB</small>
                    <div class="image-preview" id="imagePreview" style="display: none;">
                        <img id="previewImage" src="" alt="Preview">
                    </div>
                    <input type="hidden" id="image_url" name="image_url">
                </div>
                
                <!-- File Upload -->
                <div class="form-group full-width">
                    <label for="file_upload">Game File *</label>
                    <div class="file-upload-container">
                        <input type="file" id="file_upload" name="file_upload" class="file-input">
                        <label for="file_upload" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span class="file-text">Choose game file</span>
                            <span class="file-name" id="fileFileName">No file chosen</span>
                        </label>
                    </div>
                    <small>Supported formats: ZIP, RAR, EXE. Max size: 100MB</small>
                    <input type="hidden" id="file_url" name="file_url">
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeGameModal()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span id="submitButtonText">Add Game</span>
                    <i class="fas fa-spinner fa-spin" id="submitSpinner" style="display: none;"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content confirm-modal">
        <div class="modal-icon danger">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Game</h3>
        <p>Are you sure you want to delete "<span id="deleteGameTitle"></span>"? This action cannot be undone.</p>
        <div class="modal-actions">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn-danger" onclick="confirmDelete()">
                Delete Game
            </button>
        </div>
    </div>
</div>

<script>
let games = [];
let currentPage = 1;
const gamesPerPage = 10;
let currentEditId = null;
let currentDeleteId = null;

// Format Rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Load games on page load
document.addEventListener('DOMContentLoaded', function() {
    loadGames();
    setupEventListeners();
    setupFileUploads();
});

function setupEventListeners() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', debounce(function(e) {
        currentPage = 1;
        loadGames();
    }, 300));
    
    // Filter and sort functionality
    document.getElementById('categoryFilter').addEventListener('change', function() {
        currentPage = 1;
        loadGames();
    });
    
    document.getElementById('sortBy').addEventListener('change', function() {
        loadGames();
    });
    
    // Pagination
    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadGames();
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', function() {
        const totalPages = Math.ceil(games.length / gamesPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            loadGames();
        }
    });
}

function setupFileUploads() {
    // Image upload handling
    const imageUpload = document.getElementById('image_upload');
    const imageFileName = document.getElementById('imageFileName');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            imageFileName.textContent = file.name;
            
            // Show preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        } else {
            imageFileName.textContent = 'No file chosen';
            imagePreview.style.display = 'none';
        }
    });
    
    // File upload handling
    const fileUpload = document.getElementById('file_upload');
    const fileFileName = document.getElementById('fileFileName');
    
    fileUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileFileName.textContent = file.name;
        } else {
            fileFileName.textContent = 'No file chosen';
        }
    });
}

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

async function loadGames() {
    showLoadingState();

    try {
        const response = await fetch('/api/games');
        const result = await response.json();

        games = result.data; // ✅ AMBIL ARRAY-NYA, BUKAN OBJEK LUARNYA

        updateStatistics();
        filterAndSortGames();
        updateCategoriesFilter();
        renderGamesTable();
        updatePagination();

    } catch (error) {
        console.error('Error loading games:', error);
        showError('Failed to load games. Please try again.');
    } finally {
        hideLoadingState();
    }
}

function filterAndSortGames() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const sortBy = document.getElementById('sortBy').value;
    
    // Filter games
    let filteredGames = games.filter(game => {
        const matchesSearch = !searchTerm || 
            game.title.toLowerCase().includes(searchTerm) ||
            game.developer.toLowerCase().includes(searchTerm) ||
            game.category.toLowerCase().includes(searchTerm);
        
        const matchesCategory = !categoryFilter || game.category === categoryFilter;
        
        return matchesSearch && matchesCategory;
    });
    
    // Sort games
    filteredGames.sort((a, b) => {
        switch (sortBy) {
            case 'price':
                return a.price - b.price;
            case 'stock':
                return a.stock - b.stock;
            case 'release_date':
                return new Date(b.release_date) - new Date(a.release_date);
            default:
                return a.title.localeCompare(b.title);
        }
    });
    
    games = filteredGames;
}

function updateStatistics() {
    const totalGames = games.length;
    const totalStock = games.reduce((sum, game) => sum + game.stock, 0);
    const categories = [...new Set(games.map(game => game.category))];
    const lowStockGames = games.filter(game => game.stock < 10).length;
    
    document.getElementById('totalGames').textContent = totalGames;
    document.getElementById('totalStock').textContent = totalStock.toLocaleString();
    document.getElementById('totalCategories').textContent = categories.length;
    document.getElementById('lowStockGames').textContent = lowStockGames;
}

function updateCategoriesFilter() {
    const categories = [...new Set(games.map(game => game.category))];
    const categoryFilter = document.getElementById('categoryFilter');
    
    // Keep the current selection
    const currentValue = categoryFilter.value;
    
    // Clear existing options except "All Categories"
    categoryFilter.innerHTML = '<option value="">All Categories</option>';
    
    // Add categories
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        categoryFilter.appendChild(option);
    });
    
    // Restore selection if it still exists
    if (categories.includes(currentValue)) {
        categoryFilter.value = currentValue;
    }
}

function renderGamesTable() {
    const tbody = document.getElementById('gamesTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (games.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    // Calculate pagination
    const startIndex = (currentPage - 1) * gamesPerPage;
    const endIndex = startIndex + gamesPerPage;
    const paginatedGames = games.slice(startIndex, endIndex);
    
    tbody.innerHTML = paginatedGames.map(game => `
        <tr>
            <td>
                <div class="game-info-cell">
                    <img src="${game.image_url}" alt="${game.title}" class="game-image" onerror="this.src='https://via.placeholder.com/60x80/6a9eff/ffffff?text=G'">
                    <div class="game-details">
                        <div class="game-title">${game.title}</div>
                        <div class="game-description">${game.description.substring(0, 60)}${game.description.length > 60 ? '...' : ''}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="category-badge">${game.category}</span>
            </td>
            <td class="developer-cell">${game.developer}</td>
            <td class="price-cell">${formatRupiah(game.price)}</td>
            <td>
                <div class="stock-cell ${game.stock < 10 ? 'low-stock' : game.stock < 20 ? 'medium-stock' : ''}">
                    ${game.stock}
                    ${game.stock < 10 ? '<i class="fas fa-exclamation-triangle"></i>' : ''}
                </div>
            </td>
            <td>
                <div class="rating-cell">
                    ${game.rating ? `
                        <div class="stars">
                            ${getStarsHTML(game.rating)}
                        </div>
                        <span class="rating-value">${parseFloat(game.rating).toFixed(1)}</span>
                    ` : '<span class="no-rating">-</span>'}
                </div>
            </td>
            <td class="date-cell">${game.release_date ? new Date(game.release_date).toLocaleDateString('id-ID') : '-'}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon btn-edit" onclick="editGame(${game.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-delete" onclick="openDeleteModal(${game.id}, '${game.title.replace(/'/g, "\\'")}')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getStarsHTML(rating) {
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    
    let starsHTML = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        starsHTML += '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if (halfStar) {
        starsHTML += '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        starsHTML += '<i class="far fa-star"></i>';
    }
    
    return starsHTML;
}

function updatePagination() {
    const totalPages = Math.ceil(games.length / gamesPerPage);
    const pageInfo = document.getElementById('pageInfo');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'flex';
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
}

// Modal Functions
function openAddGameModal() {
    document.getElementById('modalTitle').textContent = 'Add New Game';
    document.getElementById('submitButtonText').textContent = 'Add Game';
    document.getElementById('gameForm').reset();
    document.getElementById('gameId').value = '';
    document.getElementById('imageFileName').textContent = 'No file chosen';
    document.getElementById('fileFileName').textContent = 'No file chosen';
    document.getElementById('imagePreview').style.display = 'none';
    currentEditId = null;
    document.getElementById('gameModal').style.display = 'flex';
}

function openEditGameModal(game) {
    document.getElementById('modalTitle').textContent = 'Edit Game';
    document.getElementById('submitButtonText').textContent = 'Update Game';
    document.getElementById('gameId').value = game.id;
    document.getElementById('title').value = game.title;
    document.getElementById('description').value = game.description;
    document.getElementById('developer').value = game.developer;
    document.getElementById('category').value = game.category;
    document.getElementById('price').value = game.price;
    document.getElementById('stock').value = game.stock;
    document.getElementById('rating').value = game.rating || '';
    document.getElementById('release_date').value = game.release_date || '';
    document.getElementById('image_url').value = game.image_url;
    document.getElementById('file_url').value = game.file_url;
    
    // Show current image
    if (game.image_url) {
        document.getElementById('previewImage').src = game.image_url;
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('imageFileName').textContent = 'Current image';
    }
    
    document.getElementById('fileFileName').textContent = 'Current file';
    currentEditId = game.id;
    document.getElementById('gameModal').style.display = 'flex';
}

function closeGameModal() {
    document.getElementById('gameModal').style.display = 'none';
}

function openDeleteModal(id, title) {
    document.getElementById('deleteGameTitle').textContent = title;
    currentDeleteId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    currentDeleteId = null;
}

// Form Submission
document.getElementById('gameForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitButtonText');
    const spinner = document.getElementById('submitSpinner');
    const formData = new FormData(this);
    
    // Add file uploads to form data
    const imageFile = document.getElementById('image_upload').files[0];
    const gameFile = document.getElementById('file_upload').files[0];
    
    if (imageFile) {
        formData.append('image', imageFile);
    }
    if (gameFile) {
        formData.append('game_file', gameFile);
    }
    
    submitBtn.style.display = 'none';
    spinner.style.display = 'inline-block';
    
    try {
        const url = currentEditId ? `/api/games/${currentEditId}` : '/api/games';
        const method = currentEditId ? 'POST' : 'POST'; // Use POST for both, handle with _method
        
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });
        
        if (response.ok) {
            closeGameModal();
            loadGames();
            showSuccess(currentEditId ? 'Game updated successfully!' : 'Game added successfully!');
        } else {
            throw new Error('Failed to save game');
        }
    } catch (error) {
        console.error('Error saving game:', error);
        showError('Failed to save game. Please try again.');
    } finally {
        submitBtn.style.display = 'inline';
        spinner.style.display = 'none';
    }
});

// Edit Game
async function editGame(id) {
    try {
        const response = await fetch(`/api/games/${id}`);
        const game = await response.json();
        openEditGameModal(game);
    } catch (error) {
        console.error('Error loading game:', error);
        showError('Failed to load game data.');
    }
}

// Delete Game
async function confirmDelete() {
    if (!currentDeleteId) return;
    
    try {
        const response = await fetch(`/api/games/${currentDeleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        if (response.ok) {
            closeDeleteModal();
            loadGames();
            showSuccess('Game deleted successfully!');
        } else {
            throw new Error('Failed to delete game');
        }
    } catch (error) {
        console.error('Error deleting game:', error);
        showError('Failed to delete game. Please try again.');
    }
}

// UI State Management
function showLoadingState() {
    document.getElementById('loadingState').style.display = 'flex';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('gamesTableBody').innerHTML = '';
    document.getElementById('paginationContainer').style.display = 'none';
}

function hideLoadingState() {
    document.getElementById('loadingState').style.display = 'none';
}

function showSuccess(message) {
    // Simple success notification
    alert(message);
}

function showError(message) {
    alert('Error: ' + message);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const gameModal = document.getElementById('gameModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === gameModal) {
        closeGameModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>

<style>
.main-content {
    padding: 24px;
    background-color: #f8fafc;
    min-height: calc(100vh - 130px);
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    gap: 20px;
}

.header-title {
    flex: 1;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.page-subtitle {
    color: #64748b;
    font-size: 16px;
    line-height: 1.5;
}

.btn-primary {
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(106, 158, 255, 0.3);
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid #6a9eff;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.stat-label {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

/* Filters Section */
.filters-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
}

.search-box input {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
}

.search-box input:focus {
    outline: none;
    border-color: #6a9eff;
    box-shadow: 0 0 0 3px rgba(106, 158, 255, 0.1);
}

.filter-controls {
    display: flex;
    gap: 12px;
}

.filter-select {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    min-width: 150px;
}

/* Table Styles */
.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
}

.games-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.games-table th {
    background: #f8fafc;
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
    white-space: nowrap;
}

.games-table td {
    padding: 12px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.games-table tr:last-child td {
    border-bottom: none;
}

.games-table tr:hover {
    background: #f8fafc;
}

/* Game Info Cell */
.game-info-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 200px;
}

.game-image {
    width: 50px;
    height: 65px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}

.game-details {
    flex: 1;
    min-width: 0;
}

.game-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
    font-size: 14px;
    line-height: 1.3;
}

.game-description {
    color: #64748b;
    font-size: 12px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Category Badge */
.category-badge {
    background: #f0f5ff;
    color: #6a9eff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

/* Table Cells */
.developer-cell {
    font-size: 14px;
    color: #374151;
    white-space: nowrap;
}

.price-cell {
    font-weight: 600;
    color: #059669;
    white-space: nowrap;
}

.date-cell {
    color: #64748b;
    font-size: 13px;
    white-space: nowrap;
}

/* Stock and Rating Cells */
.stock-cell {
    padding: 6px 8px;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    min-width: 50px;
    justify-content: center;
}

.stock-cell.low-stock {
    background: #fecaca;
    color: #dc2626;
}

.stock-cell.medium-stock {
    background: #fef3c7;
    color: #d97706;
}

.rating-cell {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.stars {
    color: #fbbf24;
    font-size: 12px;
}

.rating-value {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}

.no-rating {
    color: #94a3b8;
    font-style: italic;
    font-size: 13px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 13px;
}

.btn-edit {
    background: #f0f5ff;
    color: #6a9eff;
}

.btn-edit:hover {
    background: #6a9eff;
    color: white;
}

.btn-delete {
    background: #fef2f2;
    color: #ef4444;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
}

/* File Upload Styles */
.file-upload-container {
    position: relative;
    margin-bottom: 8px;
}

.file-input {
    position: absolute;
    left: -9999px;
}

.file-upload-label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f8fafc;
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    border-color: #6a9eff;
    background: #f0f5ff;
}

.file-upload-label i {
    color: #6a9eff;
    font-size: 18px;
}

.file-text {
    font-weight: 500;
    color: #374151;
}

.file-name {
    color: #64748b;
    font-size: 13px;
    margin-left: auto;
}

.image-preview {
    margin-top: 12px;
    text-align: center;
}

.image-preview img {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

/* Loading and Empty States */
.loading-state, .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #64748b;
}

.loading-state i {
    font-size: 32px;
    margin-bottom: 16px;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 8px;
}

.empty-state p {
    margin-bottom: 20px;
    text-align: center;
}

/* Pagination */
.pagination-container {
    padding: 20px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 16px;
}

.pagination-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-btn:not(:disabled):hover {
    border-color: #6a9eff;
    color: #6a9eff;
}

.page-info {
    font-size: 14px;
    color: #64748b;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.confirm-modal {
    max-width: 400px;
    text-align: center;
    padding: 40px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.modal-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
}

.modal-close:hover {
    background: #f1f5f9;
    color: #374151;
}

.modal-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 16px;
}

.modal-icon.danger {
    background: #fef2f2;
    color: #ef4444;
}

/* Form Styles */
form {
    padding: 24px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #6a9eff;
    box-shadow: 0 0 0 3px rgba(106, 158, 255, 0.1);
}

.form-group small {
    color: #64748b;
    font-size: 12px;
    margin-top: 4px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
}

.btn-secondary {
    background: #f8fafc;
    color: #374151;
    border: 1px solid #e2e8f0;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #f1f5f9;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        min-width: auto;
    }
    
    .filter-controls {
        flex-direction: column;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .games-table {
        font-size: 13px;
    }
    
    .game-info-cell {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .main-content {
        padding: 16px;
    }
    
    .modal-content {
        margin: 10px;
        max-height: calc(100vh - 20px);
    }
}
</style>
@endsection