# M00.2 - Developer Console & Migration UI

## Sprint Information
- **Sprint Code**: M00.2
- **Title**: Developer Console & Migration UI
- **Duration**: 3 days
- **Status**: ✅ COMPLETED

## Deliverables Checklist

| # | Deliverable | Status | Notes |
|---|-------------|--------|-------|
| 1 | Migration Manager Class | ✅ | `app/Database/MigrationManager.php` |
| 2 | Migration Controller | ✅ | `app/Controllers/Admin/MigrationController.php` |
| 3 | Routes (GET/POST) | ✅ | 4 routes added to `routes/web.php` |
| 4 | Frontend UI | ✅ | Dashboard dengan stats, table, action buttons |
| 5 | JavaScript Interactions | ✅ | AJAX refresh, run, rollback functionality |
| 6 | Audit Logging | ✅ | Integrated with audit_logs table |
| 7 | Navigation Update | ✅ | Sidebar link added |
| 8 | Documentation | ✅ | This file |

## Files Created/Modified

### New Files (5)
1. `app/Database/MigrationManager.php` - Core migration management logic
2. `app/Controllers/Admin/MigrationController.php` - Admin controller for migrations
3. `app/Views/admin/migrations/index.php` - Migration dashboard view
4. `public/assets/js/migrations.js` - Client-side interactions
5. `RELEASE_NOTES_M00.2.md` - This release notes

### Modified Files (2)
1. `routes/web.php` - Added 4 migration routes
2. `app/Views/layouts/main.php` - Added navigation link

## Features Implemented

### 1. Migration Dashboard UI
- **Stats Cards**: Total, Executed, Pending counts
- **Action Buttons**: Run All Pending, Rollback Last
- **Migration Table**: File name, status badge, execution timestamp
- **Auto-refresh**: Every 30 seconds
- **Manual Refresh**: Button trigger

### 2. Backend Functions
- `getStatus()` - Get all migrations with status
- `run()` - Execute all pending migrations
- `rollback()` - Rollback last executed migration
- `ensureMigrationsTableExists()` - Auto-create schema_migrations table

### 3. API Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/migrations` | Dashboard page |
| POST | `/admin/migrations/run` | Execute pending migrations |
| POST | `/admin/migrations/rollback` | Rollback last migration |
| GET | `/admin/migrations/refresh` | AJAX status refresh |

### 4. User Experience
- Confirmation dialogs before run/rollback
- Loading states on buttons
- Success/error alerts with auto-dismiss
- Disabled button states based on availability
- Responsive table layout

## Testing Scenarios (UAT)

### Scenario 1: View Migration Status
1. Navigate to `/nerslink/admin/migrations`
2. Verify stats cards show correct counts
3. Verify table lists all SQL files in `/database/migrations/`
4. Verify status badges (executed=pending) are correct

**Expected**: Dashboard loads with accurate migration status

### Scenario 2: Run Pending Migrations
1. Ensure there are pending migrations
2. Click "▶️ Run All Pending" button
3. Confirm the action in dialog
4. Wait for completion
5. Verify success alert appears
6. Verify stats and table update automatically

**Expected**: All pending migrations execute successfully, status updates to "executed"

### Scenario 3: Rollback Last Migration
1. Ensure there are executed migrations
2. Click "↩️ Rollback Last" button
3. Confirm the action in dialog
4. Wait for completion
5. Verify success alert appears
6. Verify last migration status changes to "pending"

**Expected**: Last migration record removed from schema_migrations table

### Scenario 4: Error Handling
1. Test with database connection lost
2. Test with invalid SQL in migration file
3. Test rollback when no migrations exist

**Expected**: Error messages displayed clearly, no application crash

### Scenario 5: Auto-refresh
1. Leave page open for 35 seconds
2. Verify stats update without manual refresh

**Expected**: Data refreshes every 30 seconds automatically

## Database Schema

### schema_migrations (already exists from M00.1)
```sql
CREATE TABLE schema_migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Audit Events

All migration actions are logged to `audit_logs`:
- Action: `MIGRATE` - When a migration is executed
- Action: `ROLLBACK` - When a migration is rolled back
- Entity Type: `SYSTEM_MIGRATION`
- Description: Contains migration filename

## Known Limitations

1. **Rollback Simplicity**: Current rollback only removes record from `schema_migrations`. It does NOT reverse schema changes. Future versions should support `_down.sql` files.

2. **No Dry Run**: No preview of what will be executed before running.

3. **Sequential Execution**: Migrations run sequentially; no parallel execution.

4. **No Migration Dependencies**: No explicit dependency management between migrations.

## Next Steps (M00.3 or M01.1)

- [ ] Add dry-run preview feature
- [ ] Implement proper rollback with `_down.sql` files
- [ ] Add migration generator CLI command
- [ ] Add seed data management UI
- [ ] Implement M01 (Organization Management)

## Installation Instructions

1. Ensure M00.1 is already deployed
2. Copy new files to respective directories
3. Clear browser cache if needed
4. Navigate to `/nerslink/admin/migrations`
5. Click "Run All Pending" to ensure all migrations are executed

## Demo Evidence

To demonstrate this sprint:
1. Open browser: `http://localhost/nerslink/admin/migrations`
2. Show initial state with pending migrations
3. Click "Run All Pending" and show execution
4. Show updated stats and table
5. Click "Rollback Last" and show reversal
6. Show auto-refresh working
7. Check `audit_logs` table for recorded events

---

**Release Date**: 2026
**Released By**: Development Team
**Approved By**: Product Owner
