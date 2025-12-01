@extends('layouts.user-navbar')

@section('title', 'My Profile - GameHaven')

@section('content')
<div class="profile-page">
    <!-- Header -->
    <div class="page-header">
        <h1>My Profile</h1>
        <p>Manage your account information and preferences</p>
    </div>

    <div class="profile-content">
        <!-- Left Sidebar - Profile Info -->
        <div class="profile-sidebar">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-avatar" id="profileAvatar">
                    <span id="avatarText">{{ substr(Session::get('username', 'U'), 0, 1) }}</span>
                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="profile-info">
                    <h2 id="profileUsername">{{ Session::get('username', 'User') }}</h2>
                    <p id="profileEmail">{{ Session::get('email', 'user@example.com') }}</p>
                    <div class="member-since">
                        <i class="fas fa-calendar"></i>
                        Member since {{ \Carbon\Carbon::now()->format('M Y') }}
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-card">
                <h3>Quick Stats</h3>
                <div class="stats-list">
                    <div class="stat-item">
                        <i class="fas fa-gamepad"></i>
                        <div class="stat-info">
                            <span class="stat-value" id="statsGames">0</span>
                            <span class="stat-label">Games Owned</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-coins"></i>
                        <div class="stat-info">
                            <span class="stat-value" id="statsSpent">Rp 0</span>
                            <span class="stat-label">Total Spent</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-download"></i>
                        <div class="stat-info">
                            <span class="stat-value" id="statsDownloads">0</span>
                            <span class="stat-label">Downloads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content - Profile Forms -->
        <div class="profile-main">
            <!-- Personal Information -->
            <div class="profile-section">
                <div class="section-header">
                    <h2>Personal Information</h2>
                    <button class="btn secondary" id="editPersonalBtn">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                </div>
                <div class="section-content">
                    <form id="personalInfoForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="{{ Session::get('username', '') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ Session::get('email', '') }}" readonly>
                            </div>
                            <div class="form-group full-width">
                                <label for="bio">Bio</label>
                                <textarea id="bio" name="bio" placeholder="Tell us about yourself..." rows="3" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-actions" id="personalFormActions" style="display: none;">
                            <button type="button" class="btn" onclick="cancelEdit('personal')">Cancel</button>
                            <button type="submit" class="btn primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="profile-section">
                <div class="section-header">
                    <h2>Change Password</h2>
                </div>
                <div class="section-content">
                    <form id="passwordForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="new_password" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn primary">
                                <i class="fas fa-lock"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preferences -->
            <div class="profile-section">
                <div class="section-header">
                    <h2>Preferences</h2>
                </div>
                <div class="section-content">
                    <form id="preferencesForm">
                        <div class="preferences-list">
                            <div class="preference-item">
                                <div class="preference-info">
                                    <h4>Email Notifications</h4>
                                    <p>Receive updates about new games and promotions</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="emailNotifications" name="email_notifications" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="preference-item">
                                <div class="preference-info">
                                    <h4>Download Notifications</h4>
                                    <p>Get notified when downloads are complete</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="downloadNotifications" name="download_notifications" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="preference-item">
                                <div class="preference-info">
                                    <h4>Auto-update Games</h4>
                                    <p>Automatically download game updates</p>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="autoUpdate" name="auto_update">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn primary">Save Preferences</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="profile-section danger-zone">
                <div class="section-header">
                    <h2>Danger Zone</h2>
                </div>
                <div class="section-content">
                    <div class="danger-actions">
                        <div class="danger-item">
                            <div class="danger-info">
                                <h4>Delete Account</h4>
                                <p>Permanently delete your account and all associated data</p>
                            </div>
                            <button class="btn danger" onclick="showDeleteConfirmation()">
                                <i class="fas fa-trash"></i>
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Delete Account</h3>
            <button class="close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; padding: 20px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ef4444; margin-bottom: 20px;"></i>
                <h4>Are you sure?</h4>
                <p>This action cannot be undone. This will permanently delete your account and remove all your data from our servers.</p>
                <div class="confirmation-input">
                    <label for="confirmDelete">Type "DELETE" to confirm:</label>
                    <input type="text" id="confirmDelete" placeholder="DELETE" style="width: 100%; margin-top: 10px;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn danger" id="confirmDeleteBtn" disabled onclick="deleteAccount()">
                <i class="fas fa-trash"></i>
                Delete Account
            </button>
        </div>
    </div>
</div>

<style>
.profile-page {
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

/* Profile Layout */
.profile-content {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
}

/* Profile Sidebar */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.profile-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 30px;
    text-align: center;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    position: relative;
    cursor: pointer;
    transition: var(--transition);
}

.profile-avatar:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.profile-info h2 {
    font-size: 1.5rem;
    margin-bottom: 5px;
    color: var(--text-light);
}

.profile-info p {
    color: var(--text-secondary);
    margin-bottom: 15px;
}

.member-since {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Stats Card */
.stats-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 25px;
}

.stats-card h3 {
    margin-bottom: 20px;
    color: var(--text-light);
    font-size: 1.2rem;
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    transition: var(--transition);
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.08);
}

.stat-item i {
    color: var(--accent);
    font-size: 1.2rem;
    width: 24px;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-weight: 700;
    color: var(--text-light);
    font-size: 1.1rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

/* Profile Main */
.profile-main {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.profile-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.profile-section.danger-zone {
    border-color: #ef4444;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.02);
}

.section-header h2 {
    font-size: 1.3rem;
    color: var(--text-light);
    margin: 0;
}

.section-content {
    padding: 25px;
}

/* Forms */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    color: var(--text-light);
    font-weight: 500;
    font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 12px 15px;
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: var(--transition);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group input:read-only,
.form-group textarea:read-only {
    background: rgba(255, 255, 255, 0.02);
    color: var(--text-secondary);
    cursor: not-allowed;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

/* Preferences */
.preferences-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.preference-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.preference-info h4 {
    color: var(--text-light);
    margin-bottom: 5px;
    font-size: 1rem;
}

.preference-info p {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0;
}

/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--border);
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
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

input:checked + .slider {
    background-color: var(--accent);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Danger Zone */
.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.danger-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 8px;
}

.danger-info h4 {
    color: #ef4444;
    margin-bottom: 5px;
}

.danger-info p {
    color: #fca5a5;
    font-size: 0.85rem;
    margin: 0;
}

.btn.danger {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

.btn.danger:hover {
    background: #dc2626;
    border-color: #dc2626;
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
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn.primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.btn.primary:hover:not(:disabled) {
    opacity: 0.9;
}

.btn.secondary {
    background: rgba(255, 255, 255, 0.05);
}

.btn.secondary:hover {
    border-color: var(--accent);
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid var(--border);
}

.confirmation-input {
    margin-top: 20px;
}

.confirmation-input label {
    color: var(--text-light);
    font-weight: 500;
}

.confirmation-input input {
    width: 100%;
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text-primary);
    margin-top: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-page {
        padding: 20px 16px;
    }
    
    .profile-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .preference-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .danger-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProfileData();
    setupEventListeners();
});

function setupEventListeners() {
    // Personal info edit
    document.getElementById('editPersonalBtn').addEventListener('click', () => enableEdit('personal'));
    
    // Form submissions
    document.getElementById('personalInfoForm').addEventListener('submit', updatePersonalInfo);
    document.getElementById('passwordForm').addEventListener('submit', changePassword);
    document.getElementById('preferencesForm').addEventListener('submit', savePreferences);
    
    // Delete confirmation
    document.getElementById('confirmDelete').addEventListener('input', toggleDeleteButton);
    
    // Avatar upload
    document.getElementById('profileAvatar').addEventListener('click', changeAvatar);
}

async function loadProfileData() {
    try {
        // Load user stats
        const statsResponse = await fetch('/api/user/home-data');
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            updateStats(statsData.user_stats);
        }
        
        // Load user profile data - GUNAKAN ENDPOINT BARU
        const profileResponse = await fetch('/api/user/profile');
        if (profileResponse.ok) {
            const profileData = await profileResponse.json();
            populateProfileForm(profileData);
            
            // Update preferences
            if (profileData.email_notifications !== undefined) {
                document.getElementById('emailNotifications').checked = profileData.email_notifications;
            }
            if (profileData.download_notifications !== undefined) {
                document.getElementById('downloadNotifications').checked = profileData.download_notifications;
            }
            if (profileData.auto_update !== undefined) {
                document.getElementById('autoUpdate').checked = profileData.auto_update;
            }
        } else {
            console.error('Failed to load profile data');
        }
        
    } catch (error) {
        console.error('Error loading profile data:', error);
        showNotification('Error loading profile data', 'error');
    }
}
function updateStats(stats) {
    document.getElementById('statsGames').textContent = stats.total_games || 0;
    document.getElementById('statsSpent').textContent = formatCurrency(stats.total_spent || 0);
    document.getElementById('statsDownloads').textContent = stats.download_count || 0;
}

function populateProfileForm(profile) {
    document.getElementById('username').value = profile.username || '';
    document.getElementById('email').value = profile.email || '';
    document.getElementById('bio').value = profile.bio || '';
    
    // Update avatar
    if (profile.photo) {
        document.getElementById('profileAvatar').style.backgroundImage = `url(${profile.photo})`;
        document.getElementById('avatarText').style.display = 'none';
    }
}

function enableEdit(section) {
    const form = document.getElementById(`${section}InfoForm`);
    const inputs = form.querySelectorAll('input, textarea');
    const actions = document.getElementById(`${section}FormActions`);
    
    inputs.forEach(input => input.readOnly = false);
    actions.style.display = 'flex';
}

function cancelEdit(section) {
    const form = document.getElementById(`${section}InfoForm`);
    const inputs = form.querySelectorAll('input, textarea');
    const actions = document.getElementById(`${section}FormActions`);
    
    inputs.forEach(input => input.readOnly = true);
    actions.style.display = 'none';
    
    // Reload original data
    loadProfileData();
}

async function updatePersonalInfo(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/api/user/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            const result = await response.json();
            showNotification('Profile updated successfully!', 'success');
            cancelEdit('personal');
            
            // Update tampilan
            document.getElementById('profileUsername').textContent = result.user.username;
            document.getElementById('profileEmail').textContent = result.user.email;
            document.getElementById('avatarText').textContent = result.user.username.charAt(0).toUpperCase();
            
        } else {
            const error = await response.json();
            throw new Error(error.message || 'Failed to update profile');
        }
        
    } catch (error) {
        console.error('Error updating profile:', error);
        showNotification(error.message || 'Error updating profile', 'error');
    }
}

async function changePassword(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    if (data.new_password !== data.confirm_password) {
        showNotification('New passwords do not match', 'error');
        return;
    }
    
    try {
        const userId = {{ Session::get('user_id') }};
        const response = await fetch(`/api/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                current_password: data.current_password,
                password: data.new_password
            })
        });
        
        if (response.ok) {
            showNotification('Password updated successfully!', 'success');
            event.target.reset();
        } else {
            const error = await response.json();
            throw new Error(error.message || 'Failed to update password');
        }
        
    } catch (error) {
        console.error('Error changing password:', error);
        showNotification(error.message || 'Error changing password', 'error');
    }
}

async function savePreferences(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        email_notifications: document.getElementById('emailNotifications').checked,
        download_notifications: document.getElementById('downloadNotifications').checked,
        auto_update: document.getElementById('autoUpdate').checked
    };
    
    try {
        const response = await fetch('/api/user/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showNotification('Preferences saved successfully!', 'success');
        } else {
            throw new Error('Failed to save preferences');
        }
        
    } catch (error) {
        console.error('Error saving preferences:', error);
        showNotification('Error saving preferences', 'error');
    }
}

function changeAvatar() {
    // Simple avatar change - in real app, this would upload an image
    const colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6'];
    const randomColor = colors[Math.floor(Math.random() * colors.length)];
    
    document.getElementById('profileAvatar').style.background = randomColor;
    showNotification('Avatar updated!', 'success');
}

function showDeleteConfirmation() {
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.getElementById('confirmDelete').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
}

function toggleDeleteButton() {
    const input = document.getElementById('confirmDelete');
    const button = document.getElementById('confirmDeleteBtn');
    button.disabled = input.value !== 'DELETE';
}

async function deleteAccount() {
    try {
        const userId = {{ Session::get('user_id') }};
        const response = await fetch(`/api/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            showNotification('Account deleted successfully', 'success');
            setTimeout(() => {
                window.location.href = '/logout';
            }, 2000);
        } else {
            throw new Error('Failed to delete account');
        }
        
    } catch (error) {
        console.error('Error deleting account:', error);
        showNotification('Error deleting account', 'error');
    }
}

// Utility functions
function formatCurrency(amount) {
    if (!amount) return 'Rp 0';
    return 'Rp ' + amount.toLocaleString('id-ID');
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