# NersLink Healthcare Platform

Platform manajemen perawatan luka diabetes on-demand untuk Indonesia.

## 🚀 Quick Start (XAMPP Windows)

### Prerequisites
- XAMPP untuk Windows terinstall
- PHP 8.1+ (included in XAMPP)
- MySQL/MariaDB (included in XAMPP)

### Installation Steps

1. **Copy Project ke htdocs**
   ```
   Copy folder ini ke: C:\xampp\htdocs\nerslink
   ```

2. **Start Services di XAMPP Control Panel**
   - Start Apache
   - Start MySQL

3. **Buat Database**
   - Buka phpMyAdmin: http://localhost/phpmyadmin
   - Buat database baru: `nerslink`

4. **Konfigurasi Environment**
   - File `.env` sudah tersedia dengan konfigurasi default
   - Sesuaikan jika perlu:
     ```
     DB_DATABASE=nerslink
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Jalankan Migration** (Optional - tables will auto-create on first access if using manual SQL)
   - Import manual via phpMyAdmin:
     - `database/migrations/001_create_schema_migrations_table.sql`
     - `database/migrations/002_create_audit_logs_table.sql`

6. **Akses Aplikasi**
   ```
   http://localhost/nerslink/
   ```

7. **Test Health Endpoint**
   ```
   http://localhost/nerslink/health
   http://localhost/nerslink/health/api
   ```

## 📁 Struktur Folder

```
nerslink/
├── app/                      # Application code
│   ├── Controllers/          # Request handlers
│   ├── Core/                 # Framework core classes
│   ├── Repositories/         # Data access layer
│   ├── Services/             # Business logic
│   └── Views/                # HTML templates
├── database/
│   ├── migrations/           # Schema migrations
│   └── seeds/                # Seed data
├── public/                   # Web root (document root)
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   ├── uploads/              # User uploads
│   └── index.php             # Front controller
├── routes/                   # Route definitions
│   └── web.php               # Web routes
├── tests/                    # Test files
├── .env                      # Environment config
├── .env.example              # Example env file
├── composer.json             # Composer dependencies
└── RELEASE_NOTES_*.md        # Sprint release notes
```

## 🏗️ Arsitektur

- **Pattern**: Modular Monolith
- **Routing**: Custom PSR-7 inspired router
- **Database**: PDO dengan Repository Pattern
- **Autoloading**: PSR-4
- **Configuration**: Environment-based (.env)

## 📋 Sprint Roadmap

| Modul | Sprint | Deskripsi | Status |
|-------|--------|-----------|--------|
| M00.1 | Bootstrap Runtime & Layout | ✅ Complete | Done |
| M00.2 | Migration Console | ⏸️ Next | Pending |
| M01.1 | Tenant & Hospital | ⏸️ | Pending |
| M01.2 | Unit, Service, Location | ⏸️ | Pending |
| M01.3 | Scope Isolation | ⏸️ | Pending |
| M02.1 | Login & Session | ⏸️ | Pending |
| M02.2 | User Administration | ⏸️ | Pending |
| M02.3 | MFA & Security | ⏸️ | Pending |
| M03.x | RBAC & Menu | ⏸️ | Pending |
| M04.x | Audit & Privacy | ⏸️ | Pending |
| M05.x | Master Data | ⏸️ | Pending |
| M06.x | Workforce & Credentialing | ⏸️ | Pending |
| M07.x | Patient Management | ⏸️ | Pending |
| M09.x | Homecare | ⏸️ | Pending |
| M10.x | Woundcare Clinical Core | ⏸️ | Pending |

## 🔧 Development

### Menambah Route Baru
Edit `routes/web.php`:
```php
$router->get('/my-page', ['MyController', 'index']);
```

### Membuat Controller Baru
Buat file di `app/Controllers/`:
```php
<?php
class MyController extends BaseController
{
    public function index(): string
    {
        return $this->view('my-view', [
            'pageTitle' => 'My Page'
        ]);
    }
}
```

### Membuat Migration Baru
Buat file di `database/migrations/`:
```sql
-- 003_create_my_table.sql
CREATE TABLE IF NOT EXISTS `my_table` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🧪 Testing

Manual testing scenarios tersedia di setiap file `RELEASE_NOTES_*.md`.

Automated testing framework akan ditambahkan di sprint berikutnya.

## 📝 Dokumentasi

- [Vision.md](dokumen/Vision.md) - Visi dan prinsip produk
- [PRD.md](dokumen/PRD.md) - Product Requirement Document
- [SPRINT_VERTICAL_SLICE.md](dokumen/SPRINT_VERTICAL_SLICE_BACKEND_FRONTEND_NERSLINK.md) - Sprint planning
- [Permenkes Requirements](dokumen/requirement_simrs_permenkes_6_2026.md) - Compliance requirements

## 🔐 Security Notes

- Password hashing menggunakan bcrypt (cost factor configurable)
- Session security dengan httponly cookies
- SQL injection prevention via PDO prepared statements
- XSS prevention via htmlspecialchars() pada output
- CSRF protection akan ditambahkan di sprint M02

## 🤝 Contributing

Setiap fitur baru harus mengikuti pattern vertical slice:
1. Migration
2. Backend logic
3. Routes
4. Frontend UI
5. Permission enforcement
6. Audit logging
7. Testing
8. Documentation

## 📄 License

Proprietary - NersLink Platform

---

**Version**: 1.0.0-dev (M00.1)  
**Last Updated**: 2024
