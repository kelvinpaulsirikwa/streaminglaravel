// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentActivity();
});

async function loadDashboardStats() {
    try {
        // Load total users
        const usersResponse = await fetch('/api/admin/stats/users');
        const usersData = await usersResponse.json();
        document.getElementById('total-users').textContent = usersData.data.total || 0;

        // Load total superstars
        const superstarsResponse = await fetch('/api/admin/stats/superstars');
        const superstarsData = await superstarsResponse.json();
        document.getElementById('total-superstars').textContent = superstarsData.data.total || 0;

        // Load total posts
        const postsResponse = await fetch('/api/admin/stats/posts');
        const postsData = await postsResponse.json();
        document.getElementById('total-posts').textContent = postsData.data.total || 0;

        // Load total payments
        const paymentsResponse = await fetch('/api/admin/stats/payments');
        const paymentsData = await paymentsResponse.json();
        document.getElementById('total-payments').textContent = paymentsData.data.total || 0;
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        // Set default values
        document.getElementById('total-users').textContent = '0';
        document.getElementById('total-superstars').textContent = '0';
        document.getElementById('total-posts').textContent = '0';
        document.getElementById('total-payments').textContent = '0';
    }
}

async function loadRecentActivity() {
    try {
        // Load recent users
        const recentUsersResponse = await fetch('/api/admin/recent/users');
        const recentUsersData = await recentUsersResponse.json();
        renderRecentUsers(recentUsersData.data || []);

        // Load recent superstars
        const recentSuperstarsResponse = await fetch('/api/admin/recent/superstars');
        const recentSuperstarsData = await recentSuperstarsResponse.json();
        renderRecentSuperstars(recentSuperstarsData.data || []);

        // Load recent posts
        const recentPostsResponse = await fetch('/api/admin/recent/posts');
        const recentPostsData = await recentPostsResponse.json();
        renderRecentPosts(recentPostsData.data || []);

        // Load recent payments
        const recentPaymentsResponse = await fetch('/api/admin/recent/payments');
        const recentPaymentsData = await recentPaymentsResponse.json();
        renderRecentPayments(recentPaymentsData.data || []);
    } catch (error) {
        console.error('Error loading recent activity:', error);
        // Hide loading indicators
        document.querySelectorAll('.loading').forEach(el => el.textContent = 'No data available');
    }
}

function renderRecentUsers(users) {
    const container = document.getElementById('recent-users');
    if (users.length === 0) {
        container.innerHTML = '<p class="no-data">No recent users</p>';
        return;
    }

    container.innerHTML = users.map(user => `
        <div class="activity-item">
            <div class="activity-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="activity-details">
                <div class="activity-name">${user.name}</div>
                <div class="activity-meta">${user.email} • ${formatDate(user.created_at)}</div>
            </div>
        </div>
    `).join('');
}

function renderRecentSuperstars(superstars) {
    const container = document.getElementById('recent-superstars');
    if (superstars.length === 0) {
        container.innerHTML = '<p class="no-data">No recent superstars</p>';
        return;
    }

    container.innerHTML = superstars.map(superstar => `
        <div class="activity-item">
            <div class="activity-avatar">
                <i class="fas fa-star"></i>
            </div>
            <div class="activity-details">
                <div class="activity-name">${superstar.display_name}</div>
                <div class="activity-meta">${superstar.rating} ⭐ • ${superstar.total_followers} followers • ${formatDate(superstar.created_at)}</div>
            </div>
        </div>
    `).join('');
}

function renderRecentPosts(posts) {
    const container = document.getElementById('recent-posts');
    if (posts.length === 0) {
        container.innerHTML = '<p class="no-data">No recent posts</p>';
        return;
    }

    container.innerHTML = posts.map(post => `
        <div class="activity-item">
            <div class="activity-avatar">
                <i class="fas fa-${post.media_type === 'image' ? 'image' : 'video'}"></i>
            </div>
            <div class="activity-details">
                <div class="activity-name">${post.media_type.charAt(0).toUpperCase() + post.media_type.slice(1)} Post</div>
                <div class="activity-meta">${post.is_pg ? 'PG' : '18+'} • ${formatDate(post.created_at)}</div>
            </div>
        </div>
    `).join('');
}

function renderRecentPayments(payments) {
    const container = document.getElementById('recent-payments');
    if (payments.length === 0) {
        container.innerHTML = '<p class="no-data">No recent payments</p>';
        return;
    }

    container.innerHTML = payments.map(payment => `
        <div class="activity-item">
            <div class="activity-avatar">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="activity-details">
                <div class="activity-name">$${payment.total_amount}</div>
                <div class="activity-meta">${payment.payment_method} • ${payment.payment_status} • ${formatDate(payment.created_at)}</div>
            </div>
        </div>
    `).join('');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        return 'Today';
    } else if (diffDays === 1) {
        return 'Yesterday';
    } else if (diffDays < 7) {
        return `${diffDays} days ago`;
    } else {
        return date.toLocaleDateString();
    }
}

// Auto-refresh dashboard every 30 seconds
setInterval(() => {
    loadDashboardStats();
}, 30000);
