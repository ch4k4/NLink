/**
 * Migration Console JavaScript
 * NersLink M00.2 - Developer Console & Migration UI
 */

const BASE_URL = '/nerslink/public';

// Show alert message
function showAlert(message, type = 'success') {
    const container = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    container.innerHTML = '';
    container.appendChild(alertDiv);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Refresh migration status
async function refreshStatus() {
    try {
        const response = await fetch(`${BASE_URL}/admin/migrations/refresh`);
        const data = await response.json();
        
        if (data.success) {
            updateStats(data.total, data.executed, data.pending);
            updateTable(data.migrations);
            updateButtons(data.pending, data.executed);
        } else {
            showAlert('Failed to refresh: ' + data.message, 'error');
        }
    } catch (error) {
        showAlert('Connection error: ' + error.message, 'error');
    }
}

// Update stats cards
function updateStats(total, executed, pending) {
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-executed').textContent = executed;
    document.getElementById('stat-pending').textContent = pending;
}

// Update table body
function updateTable(migrations) {
    const tbody = document.getElementById('migrations-body');
    
    if (migrations.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 2rem;">
                    No migrations found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = migrations.map((migration, index) => `
        <tr>
            <td>${index + 1}</td>
            <td><code>${escapeHtml(migration.name)}</code></td>
            <td>
                <span class="status-badge status-${migration.status}">
                    ${capitalizeFirst(migration.status)}
                </span>
            </td>
            <td class="timestamp">
                ${migration.executed_at ? formatDate(migration.executed_at) : '-'}
            </td>
        </tr>
    `).join('');
}

// Update button states
function updateButtons(pending, executed) {
    const btnRun = document.getElementById('btn-run');
    const btnRollback = document.getElementById('btn-rollback');
    
    btnRun.disabled = pending === 0;
    btnRollback.disabled = executed === 0;
}

// Run all pending migrations
async function runMigrations() {
    if (!confirm('Are you sure you want to run all pending migrations?')) {
        return;
    }
    
    const btn = document.getElementById('btn-run');
    btn.disabled = true;
    btn.textContent = '⏳ Running...';
    
    try {
        const response = await fetch(`${BASE_URL}/admin/migrations/run`, {
            method: 'POST'
        });
        const data = await response.json();
        
        if (data.success) {
            showAlert('Migration completed successfully!', 'success');
            refreshStatus();
        } else {
            showAlert('Migration failed: ' + data.message, 'error');
            btn.disabled = false;
            btn.textContent = '▶️ Run All Pending';
        }
    } catch (error) {
        showAlert('Connection error: ' + error.message, 'error');
        btn.disabled = false;
        btn.textContent = '▶️ Run All Pending';
    }
}

// Rollback last migration
async function rollbackMigration() {
    if (!confirm('Are you sure you want to rollback the last migration? This cannot be undone.')) {
        return;
    }
    
    const btn = document.getElementById('btn-rollback');
    btn.disabled = true;
    btn.textContent = '⏳ Rolling back...';
    
    try {
        const response = await fetch(`${BASE_URL}/admin/migrations/rollback`, {
            method: 'POST'
        });
        const data = await response.json();
        
        if (data.success) {
            showAlert('Rollback completed successfully!', 'success');
            refreshStatus();
        } else {
            showAlert('Rollback failed: ' + data.message, 'error');
            btn.disabled = false;
            btn.textContent = '↩️ Rollback Last';
        }
    } catch (error) {
        showAlert('Connection error: ' + error.message, 'error');
        btn.disabled = false;
        btn.textContent = '↩️ Rollback Last';
    }
}

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

// Auto-refresh every 30 seconds
setInterval(refreshStatus, 30000);
