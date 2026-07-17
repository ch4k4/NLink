<div class="card">
    <div class="card-header">
        <h2 class="card-title">Welcome to NersLink Platform</h2>
    </div>
    
    <p style="margin-bottom: 20px; color: #666;">
        NersLink is a comprehensive woundcare management platform designed to make diabetes wound care 
        more accessible, clinically safe, and operationally measurable in Indonesia.
    </p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem; margin-bottom: 10px;">❤️</div>
                <h3 style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 10px;">System Health</h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                    Monitor application status and database connectivity
                </p>
                <a href="/nerslink/health" class="btn btn-primary">View Health Status</a>
            </div>
        </div>
        
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem; margin-bottom: 10px;">🏥</div>
                <h3 style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 10px;">Organizations</h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                    Manage tenants, hospitals, and organizational units
                </p>
                <span class="status-badge status-warning">Coming Soon</span>
            </div>
        </div>
        
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem; margin-bottom: 10px;">👥</div>
                <h3 style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 10px;">Users & Roles</h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                    User management and role-based access control
                </p>
                <span class="status-badge status-warning">Coming Soon</span>
            </div>
        </div>
        
        <div class="card" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 0;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 3rem; margin-bottom: 10px;">🩹</div>
                <h3 style="font-size: 1.1rem; color: #2c3e50; margin-bottom: 10px;">Woundcare</h3>
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                    Clinical wound assessment and treatment planning
                </p>
                <span class="status-badge status-warning">Coming Soon</span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 1rem; color: #2c3e50;">📋 System Information</h3>
        </div>
        <table style="width: 100%;">
            <tr>
                <td style="font-weight: 500; width: 200px;">Platform Version</td>
                <td>1.0.0-dev (M00.1 Bootstrap)</td>
            </tr>
            <tr>
                <td style="font-weight: 500;">PHP Version</td>
                <td><?= PHP_VERSION ?></td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Database</td>
                <td>MySQL / MariaDB (via PDO)</td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Architecture</td>
                <td>Modular Monolith with Vertical Slices</td>
            </tr>
            <tr>
                <td style="font-weight: 500;">Current Sprint</td>
                <td>M00.1 - Runtime, Layout, and Health Dashboard</td>
            </tr>
        </table>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 style="font-size: 1rem; color: #2c3e50;">🚀 Next Steps</h3>
        </div>
        <ol style="padding-left: 20px; line-height: 2;">
            <li>Complete M00.2 - Database migration runner and developer console</li>
            <li>Implement M01 - Organization and Scope Management</li>
            <li>Implement M02 - IAM and Authentication</li>
            <li>Implement M03 - RBAC, Policy, and Dynamic Menu</li>
            <li>Continue with business modules (Patient, Woundcare, Homecare)</li>
        </ol>
    </div>
</div>
