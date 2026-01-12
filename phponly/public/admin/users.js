// Users Management JavaScript
let currentPage = 1;
let totalPages = 1;
let searchQuery = '';
let roleFilter = '';
let statusFilter = '';

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    
    // Add event listeners for filters
    document.getElementById('search-input').addEventListener('keyup', debounce(searchUsers, 500));
    document.getElementById('role-filter').addEventListener('change', searchUsers);
    document.getElementById('status-filter').addEventListener('change', searchUsers);
});

async function loadUsers(page = 1) {
    try {
        currentPage = page;
        
        const params = new URLSearchParams({
            page: page,
            search: searchQuery,
            role: roleFilter,
            status: statusFilter
        });
        
        const response = await fetch(`/api/admin/users?${params}`);
        const data = await response.json();
        
        if (data.success) {
            renderUsersTable(data.data.data);
            updatePagination(data.data.pagination);
            updatePaginationInfo(data.data.pagination);
        } else {
            showError('Error loading users');
        }
    } catch (error) {
        console.error('Error loading users:', error);
        showError('Error loading users');
    }
}

function renderUsersTable(users) {
    const tbody = document.getElementById('users-table-body');
    
    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center">
                    <div class="no-data">No users found</div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = users.map(user => `
        <tr>
            <td>${user.id}</td>
            <td>
                <div class="user-info">
                    <div class="user-name">${user.name}</div>
                    ${user.profile_image ? `<img src="${user.profile_image}" class="user-avatar" alt="${user.name}">` : ''}
                </div>
            </td>
            <td>${user.email}</td>
            <td>${user.username}</td>
            <td>
                <span class="badge badge-${user.role}">${user.role}</span>
            </td>
            <td>
                <span class="status-badge ${user.is_verified ? 'verified' : 'unverified'}">
                    <i class="fas fa-${user.is_verified ? 'check-circle' : 'times-circle'}"></i>
                    ${user.is_verified ? 'Verified' : 'Not Verified'}
                </span>
            </td>
            <td>
                <span class="status-badge ${user.is_blocked ? 'blocked' : 'active'}">
                    <i class="fas fa-${user.is_blocked ? 'ban' : 'check-circle'}"></i>
                    ${user.is_blocked ? 'Blocked' : 'Active'}
                </span>
            </td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-primary" onclick="editUser(${user.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="toggleUserStatus(${user.id}, ${user.is_blocked})" title="${user.is_blocked ? 'Unblock' : 'Block'}">
                        <i class="fas fa-${user.is_blocked ? 'unlock' : 'ban'}"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updatePagination(pagination) {
    totalPages = pagination.last_page;
    
    const controls = document.getElementById('pagination-controls');
    
    if (totalPages <= 1) {
        controls.innerHTML = '';
        return;
    }
    
    let paginationHTML = '';
    
    // Previous button
    paginationHTML += `
        <button class="btn btn-secondary" onclick="loadUsers(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i> Previous
        </button>
    `;
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationHTML += `<span class="page-number active">${i}</span>`;
        } else {
            paginationHTML += `<button class="page-number" onclick="loadUsers(${i})">${i}</button>`;
        }
    }
    
    // Next button
    paginationHTML += `
        <button class="btn btn-secondary" onclick="loadUsers(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
            Next <i class="fas fa-chevron-right"></i>
        </button>
    `;
    
    controls.innerHTML = paginationHTML;
}

function updatePaginationInfo(pagination) {
    const info = document.getElementById('pagination-info');
    info.textContent = `Showing ${pagination.from}-${pagination.to} of ${pagination.total} users`;
}

function searchUsers() {
    searchQuery = document.getElementById('search-input').value;
    roleFilter = document.getElementById('role-filter').value;
    statusFilter = document.getElementById('status-filter').value;
    loadUsers(1);
}

function addUser() {
    document.getElementById('modalTitle').textContent = 'Add User';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function editUser(userId) {
    document.getElementById('modalTitle').textContent = 'Edit User';
    
    // Load user data
    fetch(`/api/admin/users/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                document.getElementById('userId').value = user.id;
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;
                document.getElementById('username').value = user.username;
                document.getElementById('role').value = user.role;
                document.getElementById('is_verified').value = user.is_verified ? '1' : '0';
                document.getElementById('is_blocked').value = user.is_blocked ? '1' : '0';
                
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error loading user:', error);
            showError('Error loading user data');
        });
}

async function saveUser() {
    const userId = document.getElementById('userId').value;
    const userData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        username: document.getElementById('username').value,
        role: document.getElementById('role').value,
        is_verified: document.getElementById('is_verified').value === '1',
        is_blocked: document.getElementById('is_blocked').value === '1'
    };
    
    try {
        const url = userId ? `/api/admin/users/${userId}` : '/api/admin/users';
        const method = userId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            modal.hide();
            loadUsers(currentPage);
            showSuccess(userId ? 'User updated successfully' : 'User created successfully');
        } else {
            showError(result.message || 'Error saving user');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showError('Error saving user');
    }
}

async function toggleUserStatus(userId, isBlocked) {
    const action = isBlocked ? 'unblock' : 'block';
    const confirmMessage = `Are you sure you want to ${action} this user?`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    try {
        const response = await fetch(`/api/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ is_blocked: !isBlocked })
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadUsers(currentPage);
            showSuccess(`User ${action}ed successfully`);
        } else {
            showError(result.message || `Error ${action}ing user`);
        }
    } catch (error) {
        console.error('Error toggling user status:', error);
        showError(`Error ${action}ing user`);
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/admin/users/${userId}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadUsers(currentPage);
            showSuccess('User deleted successfully');
        } else {
            showError(result.message || 'Error deleting user');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        showError('Error deleting user');
    }
}

function exportUsers() {
    const params = new URLSearchParams({
        search: searchQuery,
        role: roleFilter,
        status: statusFilter,
        export: 'csv'
    });
    
    window.open(`/api/admin/users/export?${params}`, '_blank');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function showError(message) {
    // Create or update error notification
    showNotification(message, 'danger');
}

function showSuccess(message) {
    // Create or update success notification
    showNotification(message, 'success');
}

function showNotification(message, type) {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Utility function for debouncing
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
