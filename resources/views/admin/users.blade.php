@extends('layouts.admin-navbar')

@section('content')
<div class="main-content">
    <div class="content-header">
        <div class="header-title">
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage user accounts, roles, and permissions</p>
        </div>
        <button class="btn-primary" onclick="openAddUserModal()">
            <i class="fas fa-user-plus"></i>
            Add New User
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="totalUsers">0</h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="adminUsers">0</h3>
                <p class="stat-label">Admin Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="activeUsers">0</h3>
                <p class="stat-label">Active Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value" id="usersWithTransactions">0</h3>
                <p class="stat-label">Users with Purchases</p>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search users by username, email...">
        </div>
        <div class="filter-controls">
            <select id="roleFilter" class="filter-select">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <select id="sortBy" class="filter-select">
                <option value="username">Sort by Username</option>
                <option value="email">Sort by Email</option>
                <option value="created_at">Sort by Join Date</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div id="loadingState" class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading users...</p>
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fas fa-users"></i>
            <h3>No Users Found</h3>
            <p>Get started by adding your first user to the system.</p>
            <button class="btn-primary" onclick="openAddUserModal()">
                <i class="fas fa-user-plus"></i>
                Add First User
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

<!-- Add/Edit User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New User</h3>
            <button class="modal-close" onclick="closeUserModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="userForm">
            @csrf
            <input type="hidden" id="userId" name="id">
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password" id="passwordLabel">Password *</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small id="passwordHelp">Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label for="photo">Profile Photo URL</label>
                    <input type="url" id="photo" name="photo" placeholder="https://example.com/photo.jpg">
                    <small>Optional: Enter a direct link to the user's profile photo</small>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeUserModal()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span id="submitButtonText">Add User</span>
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
        <h3>Delete User</h3>
        <p>Are you sure you want to delete user "<span id="deleteUserName"></span>"? This action cannot be undone and will permanently remove all their data.</p>
        <div class="modal-actions">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn-danger" onclick="confirmDelete()">
                Delete User
            </button>
        </div>
    </div>
</div>

<script>
let users = [];
let currentPage = 1;
const usersPerPage = 10;
let currentEditId = null;
let currentDeleteId = null;

// Load users on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    setupEventListeners();
});

function setupEventListeners() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', debounce(function(e) {
        currentPage = 1;
        loadUsers();
    }, 300));
    
    // Filter and sort functionality
    document.getElementById('roleFilter').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });
    
    document.getElementById('sortBy').addEventListener('change', function() {
        loadUsers();
    });
    
    // Pagination
    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadUsers();
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', function() {
        const totalPages = Math.ceil(users.length / usersPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            loadUsers();
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

async function loadUsers() {
    showLoadingState();
    
    try {
        const response = await fetch('/api/users');
        users = await response.json();
        
        updateStatistics();
        filterAndSortUsers();
        renderUsersTable();
        updatePagination();
        
    } catch (error) {
        console.error('Error loading users:', error);
        showError('Failed to load users. Please try again.');
    } finally {
        hideLoadingState();
    }
}

function filterAndSortUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const sortBy = document.getElementById('sortBy').value;
    
    // Filter users
    let filteredUsers = users.filter(user => {
        const matchesSearch = !searchTerm || 
            user.username.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm);
        
        const matchesRole = !roleFilter || 
            (roleFilter === 'admin' && user.role == 1) ||
            (roleFilter === 'user' && user.role == 0);
        
        return matchesSearch && matchesRole;
    });
    
    // Sort users
    filteredUsers.sort((a, b) => {
        switch (sortBy) {
            case 'email':
                return a.email.localeCompare(b.email);
            case 'created_at':
                return new Date(b.created_at) - new Date(a.created_at);
            default:
                return a.username.localeCompare(b.username);
        }
    });
    
    users = filteredUsers;
}

function updateStatistics() {
    const totalUsers = users.length;
    const adminUsers = users.filter(user => user.role == 1).length;
    const activeUsers = users.length; // Assuming all users are active for now
    const usersWithTransactions = users.length; // You can implement this based on transaction data
    
    document.getElementById('totalUsers').textContent = totalUsers;
    document.getElementById('adminUsers').textContent = adminUsers;
    document.getElementById('activeUsers').textContent = activeUsers;
    document.getElementById('usersWithTransactions').textContent = usersWithTransactions;
}

function renderUsersTable() {
    const tbody = document.getElementById('usersTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (users.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    // Calculate pagination
    const startIndex = (currentPage - 1) * usersPerPage;
    const endIndex = startIndex + usersPerPage;
    const paginatedUsers = users.slice(startIndex, endIndex);
    
    tbody.innerHTML = paginatedUsers.map(user => `
        <tr>
            <td>
                <div class="user-info-cell">
                    <div class="user-avatar-table">
                        ${user.photo ? 
                            `<img src="${user.photo}" alt="${user.username}" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.username)}&background=6a9eff&color=fff'">` :
                            `<div class="avatar-placeholder">${user.username.charAt(0).toUpperCase()}</div>`
                        }
                    </div>
                    <div class="user-details">
                        <div class="user-name">${user.username}</div>
                        <div class="user-id">ID: ${user.id}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="email-cell">
                    <a href="mailto:${user.email}" class="email-link">${user.email}</a>
                </div>
            </td>
            <td>
                <span class="role-badge ${user.role == 1 ? 'role-admin' : 'role-user'}">
                    ${user.role == 1 ? 'Admin' : 'User'}
                </span>
            </td>
            <td>
                <div class="date-cell">
                    ${new Date(user.created_at).toLocaleDateString()}
                </div>
            </td>
            <td>
                <div class="status-cell">
                    <span class="status-badge active">Active</span>
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon btn-edit" onclick="editUser(${user.id})" title="Edit User">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-delete" onclick="openDeleteModal(${user.id}, '${user.username.replace(/'/g, "\\'")}')" title="Delete User">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updatePagination() {
    const totalPages = Math.ceil(users.length / usersPerPage);
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
function openAddUserModal() {
    document.getElementById('modalTitle').textContent = 'Add New User';
    document.getElementById('submitButtonText').textContent = 'Add User';
    document.getElementById('passwordLabel').textContent = 'Password *';
    document.getElementById('password').required = true;
    document.getElementById('passwordHelp').style.display = 'block';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    currentEditId = null;
    document.getElementById('userModal').style.display = 'flex';
}

function openEditUserModal(user) {
    document.getElementById('modalTitle').textContent = 'Edit User';
    document.getElementById('submitButtonText').textContent = 'Update User';
    document.getElementById('passwordLabel').textContent = 'Password (leave blank to keep current)';
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').style.display = 'none';
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    document.getElementById('photo').value = user.photo || '';
    document.getElementById('password').value = '';
    currentEditId = user.id;
    document.getElementById('userModal').style.display = 'flex';
}

function closeUserModal() {
    document.getElementById('userModal').style.display = 'none';
}

function openDeleteModal(id, username) {
    document.getElementById('deleteUserName').textContent = username;
    currentDeleteId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    currentDeleteId = null;
}

// Password Toggle
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = passwordInput.parentNode.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleButton.className = 'fas fa-eye';
    }
}

// Form Submission
document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitButtonText');
    const spinner = document.getElementById('submitSpinner');
    const formData = new FormData(this);
    const userData = Object.fromEntries(formData);
    
    // Remove password field if empty during edit
    if (currentEditId && !userData.password) {
        delete userData.password;
    }
    
    submitBtn.style.display = 'none';
    spinner.style.display = 'inline-block';
    
    try {
        const url = currentEditId ? `/api/users/${currentEditId}` : '/api/users';
        const method = currentEditId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(userData)
        });
        
        if (response.ok) {
            closeUserModal();
            loadUsers();
            showSuccess(currentEditId ? 'User updated successfully!' : 'User added successfully!');
        } else {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to save user');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showError('Failed to save user: ' + error.message);
    } finally {
        submitBtn.style.display = 'inline';
        spinner.style.display = 'none';
    }
});

// Edit User
async function editUser(id) {
    try {
        const response = await fetch(`/api/users/${id}`);
        const user = await response.json();
        openEditUserModal(user);
    } catch (error) {
        console.error('Error loading user:', error);
        showError('Failed to load user data.');
    }
}

// Delete User
async function confirmDelete() {
    if (!currentDeleteId) return;
    
    try {
        const response = await fetch(`/api/users/${currentDeleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        if (response.ok) {
            closeDeleteModal();
            loadUsers();
            showSuccess('User deleted successfully!');
        } else {
            throw new Error('Failed to delete user');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        showError('Failed to delete user. Please try again.');
    }
}

// UI State Management
function showLoadingState() {
    document.getElementById('loadingState').style.display = 'flex';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('usersTableBody').innerHTML = '';
    document.getElementById('paginationContainer').style.display = 'none';
}

function hideLoadingState() {
    document.getElementById('loadingState').style.display = 'none';
}

function showSuccess(message) {
    // You can implement a toast notification here
    alert(message); // Temporary simple alert
}

function showError(message) {
    // You can implement a toast notification here
    alert('Error: ' + message); // Temporary simple alert
}

// Close modals when clicking outside
window.onclick = function(event) {
    const userModal = document.getElementById('userModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === userModal) {
        closeUserModal();
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

.table-responsive {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: #f8fafc;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}

.users-table td {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.users-table tr:last-child td {
    border-bottom: none;
}

.users-table tr:hover {
    background: #f8fafc;
}

/* User Info Cell */
.user-info-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar-table {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.user-avatar-table img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #6a9eff, #4a7de8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
}

.user-id {
    color: #64748b;
    font-size: 12px;
}

/* Email Cell */
.email-cell {
    font-size: 14px;
}

.email-link {
    color: #6a9eff;
    text-decoration: none;
}

.email-link:hover {
    text-decoration: underline;
}

/* Role Badge */
.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: #f0f5ff;
    color: #6a9eff;
    border: 1px solid #6a9eff;
}

.role-user {
    background: #f0f9ff;
    color: #0ea5e9;
    border: 1px solid #0ea5e9;
}

/* Date Cell */
.date-cell {
    color: #64748b;
    font-size: 14px;
}

/* Status Cell */
.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
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
    max-width: 600px;
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
.form-group select {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #6a9eff;
    box-shadow: 0 0 0 3px rgba(106, 158, 255, 0.1);
}

.password-input-container {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
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
    
    .users-table {
        font-size: 14px;
    }
    
    .user-info-cell {
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