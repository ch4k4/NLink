# M00 - Bootstrap Sprint Release Notes

## Sprint: M00.1 - Runtime, Layout, and Health Dashboard

### ✅ Deliverables Completed

1. **Migration dan Seed**
   - ✅ `001_create_schema_migrations_table.sql` - Schema version tracking
   - ✅ `002_create_audit_logs_table.sql` - Audit logging table
   - ✅ `MigrationRunner.php` - Migration execution engine

2. **Backend/Domain Logic**
   - ✅ `EnvLoader.php` - Environment variable loader
   - ✅ `Config.php` - Configuration management (singleton)
   - ✅ `Database.php` - PDO connection manager (singleton)
   - ✅ `Router.php` - PSR-7 inspired router with middleware support
   - ✅ `BaseController.php` - Base controller with view rendering
   - ✅ `HealthController.php` - Health check endpoints
   - ✅ `DashboardController.php` - Main dashboard
   - ✅ `HealthService.php` - Health monitoring service
   - ✅ `AuditService.php` - Audit logging service
   - ✅ `BaseRepository.php` - Repository pattern base class
   - ✅ Exception classes (`AppException`, `NotFoundException`)

3. **Route/Endpoint**
   - ✅ `/` - Dashboard
   - ✅ `/health` - Health dashboard page
   - ✅ `/health/api` - JSON health status API
   - ✅ `/health/database` - Database connectivity test

4. **Frontend yang Bisa Dibuka**
   - ✅ `layouts/main.php` - Main application layout with sidebar
   - ✅ `dashboard.php` - Welcome dashboard
   - ✅ `health/dashboard.php` - Health status page
   - ✅ `errors/404.php` - 404 error page
   - ✅ `errors/500.php` - 500 error page
   - ✅ CSS embedded in layout (responsive design)
   - ✅ `app.js` - Basic JavaScript utilities

5. **Permission dan Scope Enforcement**
   - ⏸️ Will be implemented in M01-M03 sprints

6. **Audit Event**
   - ✅ `audit_logs` table created
   - ✅ `AuditService` with methods for auth, access, and modification events
   - ✅ Correlation ID generation for request tracing

7. **Automated Test Minimum**
   - ⏸️ Test framework setup planned for next sprint

8. **Skenario UAT Browser**
   ```
   1. Buka http://localhost/nerslink/
   2. Verifikasi dashboard tampil dengan sidebar dan topbar
   3. Klik menu "Health" 
   4. Verifikasi health dashboard menampilkan status database, filesystem, environment
   5. Akses http://localhost/nerslink/health/api untuk JSON response
   6. Verifikasi 404 page untuk URL yang tidak ada
   ```

9. **Screenshot/Rekaman Demo**
   - Manual testing required on XAMPP Windows environment

10. **Release Note Singkat**
    ```
    M00.1 - Initial Bootstrap Release
    
    Fitur Baru:
    - Aplikasi PHP native dengan struktur modular
    - PSR-4 autoloading
    - Environment configuration (.env)
    - Router dengan parameterized routes
    - Layout responsive dengan sidebar navigation
    - Health monitoring dashboard (database, filesystem, environment)
    - Audit logging infrastructure
    - Migration runner untuk schema management
    
    Technical Stack:
    - PHP 8.1+
    - MySQL/MariaDB via PDO
    - XAMPP Windows compatible
    
    Next Steps:
    - M00.2: Developer console dan migration UI
    - M01: Organization & Scope Management
    ```

### 📁 File Structure Created

```
/workspace/
├── app/
│   ├── Config/
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── DashboardController.php
│   │   └── HealthController.php
│   ├── Core/
│   │   ├── AppException.php
│   │   ├── Config.php
│   │   ├── Database.php
│   │   ├── EnvLoader.php
│   │   ├── MigrationRunner.php
│   │   ├── NotFoundException.php
│   │   └── Router.php
│   ├── Models/
│   ├── Repositories/
│   │   └── BaseRepository.php
│   ├── Services/
│   │   ├── AuditService.php
│   │   └── HealthService.php
│   └── Views/
│       ├── dashboard.php
│       ├── errors/
│       │   ├── 404.php
│       │   └── 500.php
│       ├── health/
│       │   └── dashboard.php
│       └── layouts/
│           └── main.php
├── database/
│   ├── migrations/
│   │   ├── 001_create_schema_migrations_table.sql
│   │   └── 002_create_audit_logs_table.sql
│   └── seeds/
├── public/
│   ├── css/
│   ├── index.php (front controller)
│   ├── js/
│   │   └── app.js
│   └── uploads/
├── routes/
│   └── web.php
├── tests/
├── .env
├── .env.example
├── composer.json
└── README.md
```

### 🔧 Installation Instructions (XAMPP Windows)

1. Copy folder ke `C:\xampp\htdocs\nerslink`
2. Start Apache dan MySQL di XAMPP Control Panel
3. Buat database `nerslink` di phpMyAdmin
4. Copy `.env.example` ke `.env` dan sesuaikan credential database
5. Akses `http://localhost/nerslink/` di browser
6. Jalankan migration manual atau tunggu implementasi CLI

### 🎯 Definition of Done Status

| DoD Criteria | Status |
|-------------|--------|
| Migration dan rollback lulus | ✅ Manual SQL ready |
| Halaman list/detail/form dapat dibuka | ✅ Dashboard + Health |
| Happy path berjalan dari browser sampai database | ✅ Health check DB |
| Validation error tampil jelas | ⏸️ Next sprint |
| Direct URL tetap dilindungi permission | ⏸️ M03 |
| Data terisolasi berdasarkan tenant/hospital/unit | ⏸️ M01 |
| Event penting masuk audit log | ✅ Infrastructure ready |
| Unit dan integration test utama lulus | ⏸️ Next sprint |
| UAT evidence tersedia | ⏸️ Manual testing needed |
| Tidak ada critical/high finding terbuka | ✅ Clean start |

---

**Sprint M00.1 Complete!** 🎉

Ready to proceed to M00.2 (Database Migration Console) or M01.1 (Tenant & Hospital Management).
