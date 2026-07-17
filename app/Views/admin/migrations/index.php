<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> - NersLink</title>
    <link rel="stylesheet" href="/nerslink/public/assets/css/style.css">
    <style>
        .migration-console {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .console-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 0.5rem 0;
            color: #666;
            font-size: 0.9rem;
        }
        .stat-card .value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }
        .stat-card.total .value { color: #2196F3; }
        .stat-card.executed .value { color: #4CAF50; }
        .stat-card.pending .value { color: #FF9800; }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #2196F3;
            color: white;
        }
        .btn-primary:hover {
            background: #1976D2;
        }
        .btn-danger {
            background: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background: #d32f2f;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .migrations-table {
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .migrations-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .migrations-table th,
        .migrations-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .migrations-table th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }
        .migrations-table tr:hover {
            background: #f9f9f9;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-executed {
            background: #E8F5E9;
            color: #2E7D32;
        }
        .status-pending {
            background: #FFF3E0;
            color: #EF6C00;
        }
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2196F3;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
        }
        .alert-error {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #FFCDD2;
        }
        .timestamp {
            color: #999;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/main.php'; ?>
    
    <div class="main-content">
        <div class="migration-console">
            <div class="console-header">
                <h1>🔧 Migration Console</h1>
                <button class="btn btn-primary" onclick="refreshStatus()">🔄 Refresh</button>
            </div>

            <div class="stats-grid">
                <div class="stat-card total">
                    <h3>Total Migrations</h3>
                    <div class="value" id="stat-total"><?= $data['total'] ?></div>
                </div>
                <div class="stat-card executed">
                    <h3>Executed</h3>
                    <div class="value" id="stat-executed"><?= $data['executed'] ?></div>
                </div>
                <div class="stat-card pending">
                    <h3>Pending</h3>
                    <div class="value" id="stat-pending"><?= $data['pending'] ?></div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" id="btn-run" onclick="runMigrations()" <?= $data['pending'] == 0 ? 'disabled' : '' ?>>
                    ▶️ Run All Pending
                </button>
                <button class="btn btn-danger" id="btn-rollback" onclick="rollbackMigration()" <?= $data['executed'] == 0 ? 'disabled' : '' ?>>
                    ↩️ Rollback Last
                </button>
            </div>

            <div id="alert-container"></div>

            <div class="migrations-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Migration File</th>
                            <th>Status</th>
                            <th>Executed At</th>
                        </tr>
                    </thead>
                    <tbody id="migrations-body">
                        <?php if (empty($data['migrations'])): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">
                                    No migrations found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['migrations'] as $index => $migration): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><code><?= htmlspecialchars($migration['name']) ?></code></td>
                                    <td>
                                        <span class="status-badge status-<?= $migration['status'] ?>">
                                            <?= ucfirst($migration['status']) ?>
                                        </span>
                                    </td>
                                    <td class="timestamp">
                                        <?= $migration['executed_at'] ? date('d M Y H:i:s', strtotime($migration['executed_at'])) : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/nerslink/public/assets/js/migrations.js"></script>
</body>
</html>
