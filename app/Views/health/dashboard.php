<div class="card">
    <div class="card-header">
        <h2 class="card-title">System Health Status</h2>
    </div>
    
    <div style="margin-bottom: 30px;">
        <?php if ($status['overall'] === 'healthy'): ?>
            <span class="status-badge status-success">✓ System Healthy</span>
        <?php else: ?>
            <span class="status-badge status-danger">⚠ System Unhealthy</span>
        <?php endif; ?>
        <p style="margin-top: 10px; color: #666;">
            Last checked: <?= date('Y-m-d H:i:s', strtotime($status['timestamp'])) ?>
        </p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <!-- Database Status -->
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <h3 style="font-size: 1rem; color: #2c3e50;">🗄️ Database</h3>
            </div>
            <div>
                <?php 
                $dbStatus = $status['checks']['database'];
                if ($dbStatus['status'] === 'connected'): 
                ?>
                    <span class="status-badge status-success">Connected</span>
                <?php elseif ($dbStatus['status'] === 'error'): ?>
                    <span class="status-badge status-warning">Error</span>
                <?php else: ?>
                    <span class="status-badge status-danger">Disconnected</span>
                <?php endif; ?>
                
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                    <?= htmlspecialchars($dbStatus['message']) ?>
                </p>
                
                <?php if (isset($dbStatus['driver'])): ?>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 5px;">
                        Driver: <?= htmlspecialchars($dbStatus['driver']) ?> | 
                        Version: <?= htmlspecialchars($dbStatus['version']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Filesystem Status -->
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <h3 style="font-size: 1rem; color: #2c3e50;">💾 Filesystem</h3>
            </div>
            <div>
                <?php 
                $fsStatus = $status['checks']['filesystem'];
                if ($fsStatus['status'] === 'writable'): 
                ?>
                    <span class="status-badge status-success">Writable</span>
                <?php else: ?>
                    <span class="status-badge status-warning">Read-only</span>
                <?php endif; ?>
                
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                    <?= htmlspecialchars($fsStatus['message']) ?>
                </p>
                
                <?php if (isset($fsStatus['upload_path'])): ?>
                    <p style="font-size: 0.85rem; color: #888; margin-top: 5px;">
                        Path: <?= htmlspecialchars($fsStatus['upload_path']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Environment Status -->
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <h3 style="font-size: 1rem; color: #2c3e50;">⚙️ Environment</h3>
            </div>
            <div>
                <?php 
                $envStatus = $status['checks']['environment'];
                if ($envStatus['status'] === 'ok'): 
                ?>
                    <span class="status-badge status-success">OK</span>
                <?php else: ?>
                    <span class="status-badge status-warning">Warning</span>
                <?php endif; ?>
                
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                    <?= htmlspecialchars($envStatus['message']) ?>
                </p>
                
                <?php if (isset($envStatus['details']['php_version'])): ?>
                    <div style="margin-top: 10px; font-size: 0.85rem;">
                        <strong>PHP Version:</strong><br>
                        Current: <?= htmlspecialchars($envStatus['details']['php_version']['current']) ?> 
                        (Required: >= <?= htmlspecialchars($envStatus['details']['php_version']['required']) ?>)
                    </div>
                <?php endif; ?>
                
                <?php if (isset($envStatus['details']['extensions'])): ?>
                    <div style="margin-top: 10px; font-size: 0.85rem;">
                        <strong>Extensions:</strong><br>
                        <?php foreach ($envStatus['details']['extensions'] as $ext => $loaded): ?>
                            <span style="<?= $loaded ? 'color: #27ae60;' : 'color: #e74c3c;' ?>">
                                <?= $loaded ? '✓' : '✗' ?> <?= htmlspecialchars($ext) ?>
                            </span><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 style="font-size: 1rem; color: #2c3e50;">ℹ️ Application Information</h3>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="font-weight: 500; width: 200px;">Application Name</td>
                <td><?= htmlspecialchars(Config::getInstance()->get('app.name')) ?></td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Environment</td>
                <td>
                    <span class="status-badge status-info">
                        <?= htmlspecialchars(Config::getInstance()->get('app.env')) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Version</td>
                <td><?= htmlspecialchars($status['version']) ?></td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Debug Mode</td>
                <td>
                    <?php if (Config::getInstance()->get('app.debug')): ?>
                        <span class="status-badge status-warning">Enabled</span>
                    <?php else: ?>
                        <span class="status-badge status-success">Disabled</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Quick Actions</h2>
    </div>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="/nerslink/health/api" class="btn btn-primary" target="_blank">View JSON Health API</a>
        <a href="/nerslink/health/database" class="btn btn-secondary" target="_blank">Test Database Connection</a>
        <a href="/nerslink/" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
