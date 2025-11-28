@extends('layouts.admin-navbar')

@section('content')
<div class="main-content">
    <div class="content-header">
        <div class="header-title">
            <h1 class="page-title">Profile Settings</h1>
            <p class="page-subtitle">Manage your account settings and profile information</p>
        </div>
    </div>

    <div class="profile-container">
        <!-- Profile Overview -->
        <div class="profile-overview">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-section">
                        <div class="avatar-container">
                            @php
                                $user = Auth::user();
                                $username = $user ? $user->username : 'Admin';
                                $email = $user ? $user->email : 'admin@gamehaven.com';
                                $photo = $user ? $user->photo : null;
                                $createdAt = $user ? $user->created_at : null;
                            @endphp
                            <img src="{{ $photo ? $photo : 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&background=6a9eff&color=fff' }}" 
                                 alt="Profile Photo" 
                                 class="profile-avatar"
                                 id="profileAvatar">
                            <div class="avatar-overlay">
                                <button class="avatar-edit-btn" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <input type="file" id="avatarInput" accept="image/*" style="display: none;" onchange="handleAvatarUpload(event)">
                        </div>
                        <div class="profile-info">
                            <h2 class="profile-name">{{ $username }}</h2>
                            <p class="profile-role">
                                <span class="role-badge admin">Administrator</span>
                            </p>
                            <p class="profile-email">{{ $email }}</p>
                            <p class="profile-join-date">
                                Member since {{ $createdAt ? $createdAt->format('F Y') : 'Unknown' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-number" id="totalActivities">0</div>
                        <div class="stat-label">Total Activities</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="lastLogin">Today</div>
                        <div class="stat-label">Last Login</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="accountStatus">Active</div>
                        <div class="stat-label">Account Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="profile-form-section">
            <div class="form-card">
                <div class="card-header">
                    <h3>Edit Profile Information</h3>
                    <p>Update your account's profile information and email address</p>
                </div>

                <form id="profileForm">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" value="{{ $username }}" required>
                            <small>Your display name that will be visible to other users</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ $email }}" required>
                            <small>Your primary email address for notifications</small>
                        </div>

                        <div class="form-group full-width">
                            <label for="current_password">Current Password</label>
                            <div class="password-input-container">
                                <input type="password" id="current_password" name="current_password">
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small>Enter your current password to make changes</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="password-input-container">
                                <input type="password" id="new_password" name="new_password">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small>Leave blank to keep current password</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <div class="password-input-container">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="photo">Profile Photo URL</label>
                            <input type="url" id="photo" name="photo" value="{{ $photo ?? '' }}" placeholder="https://example.com/your-photo.jpg">
                            <small>Or upload a new photo using the camera button above</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn-primary">
                            <span id="submitButtonText">Update Profile</span>
                            <i class="fas fa-spinner fa-spin" id="submitSpinner" style="display: none;"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Settings -->
            <div class="settings-card">
                <div class="card-header">
                    <h3>Account Settings</h3>
                    <p>Manage your account preferences and security settings</p>
                </div>

                <div class="settings-list">
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Two-Factor Authentication</h4>
                            <p>Add an extra layer of security to your account</p>
                        </div>
                        <div class="setting-action">
                            <label class="toggle-switch">
                                <input type="checkbox" id="twoFactorToggle">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Email Notifications</h4>
                            <p>Receive email updates about system activities</p>
                        </div>
                        <div class="setting-action">
                            <label class="toggle-switch">
                                <input type="checkbox" id="emailNotifications" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Login Notifications</h4>
                            <p>Get notified when someone logs into your account</p>
                        </div>
                        <div class="setting-action">
                            <label class="toggle-switch">
                                <input type="checkbox" id="loginNotifications" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="danger-zone">
                    <h4>Danger Zone</h4>
                    <p>Permanently delete your account and all associated data</p>
                    <button class="btn-danger" onclick="showDeleteAccountModal()">
                        <i class="fas fa-trash"></i>
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content confirm-modal">
        <div class="modal-icon danger">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Account</h3>
        <p>Are you sure you want to delete your account? This action cannot be undone and will permanently remove all your data from our systems.</p>
        <div class="modal-actions">
            <button type="button" class="btn-secondary" onclick="closeDeleteAccountModal()">Cancel</button>
            <button type="button" class="btn-danger" onclick="confirmDeleteAccount()">
                Delete My Account
            </button>
        </div>
    </div>
</div>

<!-- Success Message -->
<div id="successMessage" class="success-message" style="display: none;">
    <i class="fas fa-check-circle"></i>
    <span id="successText">Profile updated successfully!</span>
    <button class="message-close" onclick="hideSuccessMessage()">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
// Current user data
let currentUser = {
    id: {{ Auth::id() ?? 'null' }},
    username: '{{ $username }}',
    email: '{{ $email }}',
    photo: '{{ $photo ?? '' }}'
};

// Initialize profile page
document.addEventListener('DOMContentLoaded', function() {
    loadProfileStats();
    setupEventListeners();
});

function setupEventListeners() {
    // Form submission
    document.getElementById('profileForm').addEventListener('submit', handleProfileUpdate);
    
    // Real-time form validation
    document.getElementById('new_password').addEventListener('input', validatePassword);
    document.getElementById('new_password_confirmation').addEventListener('input', validatePasswordConfirmation);
}

function loadProfileStats() {
    // Simulate loading profile statistics
    setTimeout(() => {
        document.getElementById('totalActivities').textContent = '127';
        document.getElementById('lastLogin').textContent = 'Today';
        document.getElementById('accountStatus').textContent = 'Active';
    }, 500);
}

// Avatar Upload Handling
function handleAvatarUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
        showError('Please select a valid image file.');
        return;
    }

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showError('Image size should be less than 5MB.');
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('profileAvatar').src = e.target.result;
        
        // Simulate upload
        simulateAvatarUpload(file);
    };
    reader.readAsDataURL(file);
}

function simulateAvatarUpload(file) {
    const avatar = document.getElementById('profileAvatar');
    avatar.style.opacity = '0.7';
    
    setTimeout(() => {
        avatar.style.opacity = '1';
        showSuccess('Profile photo updated successfully!');
        
        // Update photo URL input field
        document.getElementById('photo').value = URL.createObjectURL(file);
    }, 1500);
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

// Form Validation
function validatePassword() {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    
    if (password && password.length < 6) {
        showFieldError('new_password', 'Password must be at least 6 characters long');
    } else {
        clearFieldError('new_password');
    }
    
    if (confirmation && password !== confirmation) {
        showFieldError('new_password_confirmation', 'Passwords do not match');
    } else {
        clearFieldError('new_password_confirmation');
    }
}

function validatePasswordConfirmation() {
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;
    
    if (confirmation && password !== confirmation) {
        showFieldError('new_password_confirmation', 'Passwords do not match');
    } else {
        clearFieldError('new_password_confirmation');
    }
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    // Remove existing error
    const existingError = formGroup.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    formGroup.appendChild(errorElement);
    
    // Add error styling
    field.style.borderColor = '#ef4444';
}

function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    // Remove error message
    const existingError = formGroup.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Remove error styling
    field.style.borderColor = '';
}

// Profile Form Submission
async function handleProfileUpdate(event) {
    event.preventDefault();
    
    // Check if user is authenticated
    if (!currentUser.id || currentUser.id === 'null') {
        showError('You must be logged in to update your profile.');
        return;
    }
    
    const submitBtn = document.getElementById('submitButtonText');
    const spinner = document.getElementById('submitSpinner');
    const formData = new FormData(event.target);
    const profileData = Object.fromEntries(formData);
    
    // Basic validation
    if (profileData.new_password && profileData.new_password.length < 6) {
        showError('New password must be at least 6 characters long');
        return;
    }
    
    if (profileData.new_password !== profileData.new_password_confirmation) {
        showError('New passwords do not match');
        return;
    }
    
    // Remove confirmation field and empty password fields
    delete profileData.new_password_confirmation;
    if (!profileData.new_password) {
        delete profileData.new_password;
    }
    if (!profileData.current_password) {
        delete profileData.current_password;
    }
    
    submitBtn.style.display = 'none';
    spinner.style.display = 'inline-block';
    
    try {
        const response = await fetch(`/api/users/${currentUser.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(profileData)
        });
        
        if (response.ok) {
            const updatedUser = await response.json();
            currentUser = { ...currentUser, ...updatedUser };
            showSuccess('Profile updated successfully!');
            updateProfileDisplay(updatedUser);
        } else {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        showError('Failed to update profile: ' + error.message);
    } finally {
        submitBtn.style.display = 'inline';
        spinner.style.display = 'none';
    }
}

function updateProfileDisplay(userData) {
    // Update displayed user information
    document.querySelector('.profile-name').textContent = userData.username;
    document.querySelector('.profile-email').textContent = userData.email;
    
    if (userData.photo) {
        document.getElementById('profileAvatar').src = userData.photo;
    }
}

function resetForm() {
    document.getElementById('profileForm').reset();
    document.getElementById('current_password').value = '';
    document.getElementById('new_password').value = '';
    document.getElementById('new_password_confirmation').value = '';
    
    // Clear any error messages
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.form-group input').forEach(input => {
        input.style.borderColor = '';
    });
}

// Delete Account Functions
function showDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'flex';
}

function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'none';
}

async function confirmDeleteAccount() {
    if (!currentUser.id || currentUser.id === 'null') {
        showError('You must be logged in to delete your account.');
        return;
    }
    
    try {
        const response = await fetch(`/api/users/${currentUser.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        if (response.ok) {
            // Redirect to logout or home page after account deletion
            window.location.href = '/logout';
        } else {
            throw new Error('Failed to delete account');
        }
    } catch (error) {
        console.error('Error deleting account:', error);
        showError('Failed to delete account. Please try again.');
        closeDeleteAccountModal();
    }
}

// Notification Functions
function showSuccess(message) {
    const successMessage = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    successText.textContent = message;
    successMessage.style.display = 'flex';
    
    // Auto hide after 5 seconds
    setTimeout(hideSuccessMessage, 5000);
}

function hideSuccessMessage() {
    document.getElementById('successMessage').style.display = 'none';
}

function showError(message) {
    // Simple error alert - you can replace this with a better notification system
    alert('Error: ' + message);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteAccountModal');
    if (event.target === deleteModal) {
        closeDeleteAccountModal();
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
    margin-bottom: 24px;
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

.profile-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Profile Overview */
.profile-overview {
    margin-bottom: 30px;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.profile-header {
    padding: 40px;
    border-bottom: 1px solid #f1f5f9;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 24px;
}

.avatar-container {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s ease;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.avatar-container:hover .avatar-overlay {
    opacity: 1;
}

.avatar-edit-btn {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.avatar-edit-btn:hover {
    background: white;
    transform: scale(1.1);
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.profile-role {
    margin-bottom: 8px;
}

.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-badge.admin {
    background: #f0f5ff;
    color: #6a9eff;
    border: 1px solid #6a9eff;
}

.profile-email {
    color: #64748b;
    font-size: 16px;
    margin-bottom: 4px;
}

.profile-join-date {
    color: #94a3b8;
    font-size: 14px;
}

/* Profile Stats */
.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 30px 40px;
    background: #f8fafc;
}

.stat-item {
    text-align: center;
    padding: 0 20px;
}

.stat-item:not(:last-child) {
    border-right: 1px solid #e2e8f0;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.stat-label {
    color: #64748b;
    font-size: 14px;
}

/* Profile Form Section */
.profile-form-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.form-card, .settings-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

.card-header {
    margin-bottom: 24px;
}

.card-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
}

.card-header p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
}

/* Form Styles */
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

.form-group input {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus {
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

.field-error {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
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
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(106, 158, 255, 0.3);
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

/* Settings List */
.settings-list {
    margin-bottom: 30px;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.setting-info p {
    color: #64748b;
    font-size: 12px;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e8f0;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #6a9eff;
}

input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

/* Danger Zone */
.danger-zone {
    padding: 20px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
}

.danger-zone h4 {
    color: #dc2626;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.danger-zone p {
    color: #ef4444;
    font-size: 14px;
    margin-bottom: 16px;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: #dc2626;
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
    max-width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.confirm-modal {
    text-align: center;
    padding: 40px;
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

.confirm-modal h3 {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
}

.confirm-modal p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 24px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

/* Success Message */
.success-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #dcfce7;
    color: #166534;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 1001;
    max-width: 400px;
}

.message-close {
    background: none;
    border: none;
    color: #166534;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
}

.message-close:hover {
    background: rgba(22, 101, 52, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-form-section {
        grid-template-columns: 1fr;
    }
    
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .profile-stats {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 20px;
    }
    
    .stat-item:not(:last-child) {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
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
    
    .profile-header {
        padding: 24px;
    }
    
    .form-card, .settings-card {
        padding: 20px;
    }
    
    .avatar-container {
        width: 80px;
        height: 80px;
    }
}
</style>

@endsection