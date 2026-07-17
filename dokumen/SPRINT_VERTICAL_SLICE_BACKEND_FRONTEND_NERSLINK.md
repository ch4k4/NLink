# Sprint Vertical Slice Per Modul — Nerslink Healthcare Platform

**Versi:** 2.0  
**Model delivery:** backend–frontend per modul, demo-driven, vertical slice  
**Baseline:** `ssot_php.zip`, `Vision.md`, `PRD.md`, blueprint woundcare, dan requirement SIMRS Permenkes 6/2026  
**Stack:** PHP 8.1+, MySQL 8/MariaDB, Apache/XAMPP, PDO, Composer, Modular Monolith

---

## 1. Tujuan Model Sprint

Rencana ini mengganti pendekatan yang menyelesaikan seluruh backend terlebih dahulu baru frontend.

Setiap modul dikembangkan sebagai **vertical slice**:

```text
Database
→ Backend/domain/API
→ Frontend/menu/form/list/detail
→ Authorization dan audit
→ Test dan UAT
→ Demo yang dapat dibuka melalui browser
```

Dengan model ini, progres setiap sprint dapat dinilai melalui fitur yang benar-benar berjalan, bukan hanya jumlah tabel atau class yang selesai.

---

## 2. Format Penomoran

```text
M<nomor-modul>.<nomor-sprint>
```

Contoh:

```text
M07.1 = sprint pertama modul Patient
M10.4 = sprint keempat modul Woundcare
```

Setiap sprint idealnya berdurasi **1–2 minggu**, tergantung ukuran tim dan kompleksitas integrasi.

---

## 3. Aturan Wajib Setiap Sprint

Setiap sprint harus menghasilkan:

1. migration dan seed;
2. backend/domain logic;
3. route atau endpoint;
4. frontend yang bisa dibuka;
5. permission dan scope enforcement;
6. audit event;
7. automated test minimum;
8. skenario UAT browser;
9. screenshot atau rekaman demo;
10. release note singkat.

Sprint tidak dianggap selesai apabila backend sudah ada tetapi UI belum dapat digunakan, kecuali sprint bootstrap teknis.

---

## 4. Definition of Done Vertical Slice

Satu sprint selesai apabila:

- migration dan rollback lulus;
- halaman list/detail/form dapat dibuka;
- happy path berjalan dari browser sampai database;
- validation error tampil jelas;
- direct URL tetap dilindungi permission;
- data terisolasi berdasarkan tenant/hospital/unit;
- event penting masuk audit log;
- unit dan integration test utama lulus;
- UAT evidence tersedia;
- tidak ada critical/high finding terbuka.

---

# 5. Rencana Sprint Per Modul

## M00 — Bootstrap Aplikasi

### M00.1 — Runtime, Layout, dan Health Dashboard

**Backend**

- Composer dan PSR-4;
- environment loader;
- front controller;
- router;
- base controller/service/repository;
- standard exception handler;
- health service.

**Frontend**

- shell aplikasi;
- login placeholder;
- sidebar/topbar;
- error page;
- health dashboard.

**Demo**

- aplikasi dapat dibuka di XAMPP;
- `/health` dan halaman status aplikasi tampil.

### M00.2 — Database, Migration, dan Developer Console

**Backend**

- PDO connection;
- migration/rollback/seed runner;
- transaction helper;
- schema version table.

**Frontend**

- halaman internal environment status;
- halaman migration status read-only untuk development.

**Exit gate M00**

- aplikasi dibangun dari database kosong;
- migrate → rollback → migrate lulus;
- health dashboard menampilkan database connectivity.

---

## M01 — Organization dan Scope

### M01.1 — Tenant dan Hospital Management

**Backend**

- tenant;
- hospital/clinic/homecare center;
- legal entity;
- effective dating;
- CRUD service dan repository.

**Frontend**

- daftar tenant;
- detail tenant;
- form hospital/clinic;
- status aktif/nonaktif.

**Demo**

- admin membuat tenant dan hospital melalui browser.

### M01.2 — Unit, Service, dan Location

**Backend**

- organization unit;
- service;
- location;
- hierarchy validation;
- history.

**Frontend**

- organization tree;
- unit/service/location form;
- detail struktur organisasi.

### M01.3 — Active Scope dan Isolation

**Backend**

- ScopeContext;
- ScopeResolver;
- middleware;
- repository scope enforcement;
- cross-scope denial.

**Frontend**

- active-scope switcher;
- scope badge;
- forbidden page;
- filter data berdasarkan scope aktif.

**Exit gate M01**

- dua hospital pada tenant yang sama tidak saling melihat data tanpa grant;
- scope aktif terlihat pada seluruh halaman.

---

## M02 — IAM dan Authentication

### M02.1 — Login, Logout, dan Session

**Backend**

- users;
- password hashing;
- login/logout;
- secure session;
- failed-login event;
- account status.

**Frontend**

- login;
- logout;
- session-expired page;
- profil akun ringkas.

### M02.2 — User Administration

**Backend**

- create/invite user;
- activate;
- suspend;
- deactivate;
- reset password;
- revoke session.

**Frontend**

- user list;
- user detail;
- create/edit form;
- activation dan suspension action.

### M02.3 — MFA dan Session Security

**Backend**

- MFA privileged user;
- recovery code;
- device/session registry;
- session revocation.

**Frontend**

- MFA setup;
- MFA challenge;
- active session list;
- revoke session action.

**Exit gate M02**

- akun nonaktif tidak dapat login;
- privileged account wajib MFA;
- aktivitas authentication masuk security log.

---

## M03 — RBAC, Policy, dan Dynamic Menu

### M03.1 — Permission dan Role

**Backend**

- roles;
- permissions;
- role-permission mapping;
- scoped role assignment;
- validity period.

**Frontend**

- permission catalog;
- role editor;
- role assignment pada user;
- matrix role-permission.

### M03.2 — Authorization Enforcement

**Backend**

- permission checker;
- authorization middleware;
- scope-aware policy;
- denial audit.

**Frontend**

- tombol/action disembunyikan sesuai permission;
- access denied page;
- permission inspector untuk admin.

### M03.3 — Dynamic Menu

**Backend**

- module registry;
- menu registry;
- route binding;
- feature flag;
- menu projection.

**Frontend**

- menu otomatis berdasarkan user;
- menu administration;
- preview menu per role/scope.

### M03.4 — SoD, Temporary Access, dan Break-glass

**Backend**

- SoD rule;
- temporary grant;
- break-glass;
- expiry;
- retrospective review.

**Frontend**

- conflict warning;
- temporary-access request;
- break-glass reason form;
- review queue.

**Exit gate M03**

- direct URL tetap ditolak bila tidak berwenang;
- menu mengikuti role, permission, scope, dan feature flag;
- break-glass memiliki alasan, expiry, notifikasi, dan audit.

---

## M04 — Audit, Records, dan Privacy

### M04.1 — Audit Explorer

**Backend**

- immutable audit event;
- actor/resource/patient/scope;
- correlation ID;
- safe before/after metadata.

**Frontend**

- audit list;
- filter actor/action/resource/date;
- audit detail;
- correlation view.

### M04.2 — Clinical Finalization dan Amendment

**Backend**

- draft/final;
- signature metadata;
- amendment request;
- approval;
- immutable original version.

**Frontend**

- finalize action;
- amendment request form;
- version history;
- comparison view.

### M04.3 — Retention, Legal Hold, dan Disclosure

**Backend**

- retention class;
- legal hold;
- disposal request;
- disclosure register.

**Frontend**

- retention policy list;
- legal-hold detail;
- disposal approval queue;
- disclosure log.

**Exit gate M04**

- final record tidak dapat ditimpa;
- legal hold memblokir disposal;
- akses audit juga ikut diaudit.

---

## M05 — Master Data dan Data Quality

### M05.1 — Reference Data Registry

**Backend**

- reference data;
- code set;
- effective dating;
- ownership/stewardship;
- seed version.

**Frontend**

- reference-data list;
- code-set editor;
- active/inactive filter;
- effective-period form.

### M05.2 — Validation dan Duplicate Detection

**Backend**

- reusable validation rule;
- duplicate-match service;
- data-quality issue registry.

**Frontend**

- duplicate warning;
- validation summary;
- data-quality queue;
- remediation action.

### M05.3 — Backup/Restore Evidence

**Backend**

- backup metadata;
- restore verification record;
- archive job baseline.

**Frontend**

- backup status dashboard;
- restore-test evidence;
- failed-job status.

**Exit gate M05**

- lookup kritis tidak di-hardcode pada controller/view;
- duplicate dan validation issue dapat dilihat serta ditindaklanjuti.

---

## M06 — Workforce, Credentialing, dan Clinical Privilege

### M06.1 — Staff Profile dan Assignment

**Backend**

- staff profile;
- nurse/doctor profile;
- employment;
- unit/service/location assignment;
- suspension.

**Frontend**

- staff list;
- profile detail;
- assignment form;
- employment status.

### M06.2 — STR, SIP, dan Certificate

**Backend**

- professional registration;
- practice license;
- training/certificate;
- verification;
- expiry rule.

**Frontend**

- credential tab;
- upload document;
- verification queue;
- expiry dashboard.

### M06.3 — Credentialing Workflow

**Backend**

- application;
- review;
- maker-checker;
- recommendation;
- approval history.

**Frontend**

- application form;
- reviewer inbox;
- checklist;
- decision page.

### M06.4 — Clinical Privilege dan Eligibility

**Backend**

- privilege catalog;
- procedure/service mapping;
- grant/restrict/suspend/revoke;
- eligibility API.

**Frontend**

- privilege matrix;
- grant/restrict form;
- eligibility status badge;
- blocked-action explanation.

**Exit gate M06**

- role klinis tidak otomatis memberi hak tindakan;
- credential kedaluwarsa memblokir assignment atau tindakan;
- keputusan credential/privilege memiliki approver, periode, dan audit.

---

## M07 — Patient, Consent, dan Encounter

### M07.1 — Patient Registration

**Backend**

- patient;
- MRN;
- identifier;
- demographics;
- contact/address;
- emergency contact.

**Frontend**

- patient list;
- registration form;
- patient detail;
- search/filter.

### M07.2 — Duplicate dan Merge Patient

**Backend**

- duplicate detection;
- merge request;
- approval;
- identifier history.

**Frontend**

- duplicate suggestion;
- side-by-side comparison;
- merge request;
- merge history.

### M07.3 — Consent dan Restrictions

**Backend**

- general/service/photo consent;
- withdrawal;
- proxy;
- restricted-access marker.

**Frontend**

- consent form;
- consent history;
- withdrawal action;
- restricted patient warning.

### M07.4 — Appointment dan Encounter

**Backend**

- appointment;
- encounter;
- participant;
- location;
- status history;
- cancellation/closure.

**Frontend**

- appointment calendar/list;
- appointment form;
- encounter detail;
- status action.

**Exit gate M07**

- patient tidak bocor lintas scope;
- MRN unik sesuai policy;
- merge tidak menghapus riwayat;
- consent aktif diverifikasi sebelum penggunaan terkait.

---

## M08 — Secure File dan Clinical Evidence

### M08.1 — Secure Upload dan Download

**Backend**

- private file metadata;
- checksum;
- MIME/size validation;
- authorized download;
- ownership.

**Frontend**

- drag-and-drop upload;
- upload progress;
- file list;
- authorized preview/download.

### M08.2 — Evidence Linking dan Governance

**Backend**

- relation ke patient/episode/visit/document;
- versioning;
- malware-scan hook;
- retention/legal hold;
- access logging.

**Frontend**

- evidence gallery;
- version history;
- evidence metadata;
- access history.

**Exit gate M08**

- tidak ada direct public file path;
- seluruh akses file melalui authorization;
- foto klinis memiliki owner, context, checksum, dan audit.

---

## M09 — Homecare

### M09.1 — Homecare Request

**Backend**

- service request;
- address;
- service area;
- requested date;
- status;
- coordinator queue.

**Frontend**

- patient request form;
- coordinator request list;
- request detail;
- approve/reject action.

### M09.2 — Scheduling dan Assignment

**Backend**

- schedule;
- nurse eligibility;
- availability;
- workload;
- location/service-area rule;
- assignment history.

**Frontend**

- scheduling form;
- eligible nurse dropdown;
- assignment board;
- nurse workload view.

### M09.3 — Nurse Visit Workspace

**Backend**

- visit lifecycle;
- en-route/arrived/in-progress;
- EVV;
- timestamp/location evidence.

**Frontend**

- nurse visit list;
- visit detail;
- depart/arrive/clock-in;
- active visit workspace.

### M09.4 — Completion, Escalation, dan Supervisor Review

**Backend**

- documentation guard;
- clock-out;
- complete;
- escalation;
- referral baseline;
- finalization.

**Frontend**

- completion checklist;
- escalation form;
- supervisor review queue;
- visit timeline.

**Exit gate M09**

- hanya nurse eligible yang dapat ditugaskan;
- nurse hanya melihat assignment-nya;
- visit tidak dapat selesai tanpa bukti minimum;
- finalized visit hanya dapat dikoreksi melalui amendment.

---

## M10 — Woundcare Clinical Core

### M10.1 — Intake dan Screening

**Backend**

- woundcare intake;
- initial photo;
- risk screening;
- eligibility;
- rejection reason.

**Frontend**

- intake form;
- screening queue;
- risk badge;
- eligibility decision.

### M10.2 — Episode dan Initial Assessment

**Backend**

- woundcare episode;
- DM profile;
- vital signs;
- neuropathy/vascular checks;
- risk flag;
- draft/final.

**Frontend**

- episode list;
- episode dashboard;
- initial assessment form;
- risk summary.

### M10.3 — Wound Site dan Assessment

**Backend**

- wound site;
- measurement;
- tissue;
- exudate;
- infection;
- periwound;
- pain;
- serial assessment.

**Frontend**

- wound-site management;
- wound assessment form;
- photo gallery;
- measurement trend.

### M10.4 — Treatment Plan

**Backend**

- goals;
- dressing plan;
- offloading;
- infection management;
- education;
- follow-up;
- escalation criteria;
- versioning.

**Frontend**

- care-plan editor;
- active plan view;
- plan history;
- review/approval action.

### M10.5 — Procedure Documentation

**Backend**

- preparation checklist;
- cleansing/debridement/dressing;
- consumables;
- pain before/after;
- signatures;
- finalization guard.

**Frontend**

- field procedure form;
- before/after photo;
- consumable input;
- completion validation.

### M10.6 — Outcome dan Follow-up

**Backend**

- healing trend;
- adherence;
- goal achievement;
- escalation/referral;
- next visit generation.

**Frontend**

- outcome form;
- progress dashboard;
- trend view;
- next-visit action.

### M10.7 — Closure dan Clinical Timeline

**Backend**

- closure validation;
- closure summary;
- resolved/referred/closed;
- evidence package.

**Frontend**

- closure checklist;
- episode timeline;
- clinical summary;
- print/export evidence package.

**Exit gate M10**

- episode menjadi container klinis utama;
- invalid transition ditolak;
- high-risk case mengikuti review policy;
- seluruh record final immutable;
- satu episode dapat didemokan dari intake sampai closure.

---

## M11 — Workflow, Approval, dan Scheduler

### M11.1 — Workflow Registry dan Visual Status

**Backend**

- workflow definition;
- version;
- transition guard;
- state history.

**Frontend**

- workflow definition list;
- state-transition viewer;
- status history component.

### M11.2 — Task Inbox dan Approval

**Backend**

- human task;
- assignment;
- approve/reject/request-change;
- delegation;
- escalation.

**Frontend**

- task inbox;
- task detail;
- approval action;
- delegation form.

### M11.3 — Scheduler dan Recovery

**Backend**

- scheduled job;
- retry;
- idempotency;
- failed-job queue;
- reconciliation.

**Frontend**

- job dashboard;
- failed-job detail;
- retry action;
- reconciliation status.

**Exit gate M11**

- transisi kritis tidak dilakukan melalui update status langsung;
- retry tidak menggandakan transaksi;
- seluruh approval memiliki actor, reason, dan timestamp.

---

## M12 — Notification dan Communication Preference

### M12.1 — Template dan In-app Notification

**Backend**

- notification template;
- template version;
- in-app channel;
- delivery log.

**Frontend**

- notification center;
- unread badge;
- template management;
- delivery detail.

### M12.2 — Preference, Consent, dan Retry

**Backend**

- user preference;
- consent check;
- suppression;
- retry/failure handling.

**Frontend**

- notification preference;
- consent-aware channel selection;
- failed-delivery dashboard.

**Exit gate M12**

- notifikasi tidak di-hardcode dalam controller;
- preference dan consent dihormati;
- delivery status dapat ditelusuri.

---

## M13 — Inventory dan Consumables

### M13.1 — Item Master dan Warehouse

**Backend**

- item;
- category;
- unit;
- warehouse;
- stock balance;
- lot/expiry.

**Frontend**

- item list/form;
- warehouse list;
- stock dashboard;
- lot/expiry view.

### M13.2 — Stock Movement

**Backend**

- receive;
- transfer;
- issue;
- return;
- adjustment;
- stock opname.

**Frontend**

- movement form;
- movement history;
- stock-opname workspace;
- discrepancy approval.

### M13.3 — Clinical Consumption

**Backend**

- visit/procedure consumption;
- reservation;
- deduction;
- traceability;
- reversal rule.

**Frontend**

- consumable picker pada procedure;
- usage summary;
- traceability view;
- shortage warning.

**Exit gate M13**

- tidak ada stok negatif tanpa controlled override;
- consumable dapat ditelusuri sampai visit/patient;
- adjustment memiliki approval dan audit.

---

## M14 — Billing dan Payment

### M14.1 — Service Catalog dan Tariff

**Backend**

- service catalog;
- tariff scheme/version;
- effective dating;
- payer tariff.

**Frontend**

- service/tariff list;
- tariff editor;
- version history;
- activation approval.

### M14.2 — Charge dan Invoice

**Backend**

- charge capture;
- invoice draft;
- invoice item;
- discount rule;
- finalization.

**Frontend**

- billing worklist;
- invoice detail;
- adjustment form;
- finalize invoice.

### M14.3 — Payment dan Refund

**Backend**

- payment;
- allocation;
- receipt;
- refund request;
- dual approval.

**Frontend**

- payment form;
- receipt view;
- refund request;
- approval queue.

**Exit gate M14**

- tariff lama tidak ditimpa;
- refund menggunakan maker-checker;
- semua perubahan nilai finansial diaudit.

---

## M15 — Workforce Claim dan Payroll

### M15.1 — Claim Rule dan Eligibility

**Backend**

- compensation rule;
- completed/verified visit eligibility;
- claim generation;
- exception.

**Frontend**

- rule list;
- eligible-visit list;
- claim preview;
- exception detail.

### M15.2 — Approval dan Payment Status

**Backend**

- claim approval;
- rejection;
- payment batch;
- paid status;
- reconciliation.

**Frontend**

- nurse claim portal;
- approver queue;
- payment status;
- claim history.

**Exit gate M15**

- claim tidak terbentuk dari visit yang belum verified;
- perubahan nilai atau status memiliki approval dan audit.

---

## M16 — Quality, Patient Safety, dan CAPA

### M16.1 — Quality Indicator

**Backend**

- indicator definition;
- numerator/denominator;
- measurement;
- target;
- validation.

**Frontend**

- indicator catalog;
- entry/import form;
- trend dashboard;
- validation queue.

### M16.2 — Patient Safety Incident

**Backend**

- incident;
- severity/risk score;
- protected reporter;
- triage;
- investigation assignment.

**Frontend**

- incident report;
- restricted incident list;
- triage page;
- investigation workspace.

### M16.3 — RCA dan CAPA

**Backend**

- root cause;
- recommendation;
- corrective/preventive action;
- evidence;
- effectiveness review.

**Frontend**

- RCA form;
- CAPA board;
- evidence upload;
- effectiveness review.

**Exit gate M16**

- incident tidak dapat dihapus;
- akses identitas pelapor dibatasi dan diaudit;
- CAPA dapat ditelusuri sampai evidence dan closure.

---

## M17 — Search, Reporting, dan Analytics

### M17.1 — Global Search

**Backend**

- search index contract;
- scope-aware search;
- permission filtering;
- query audit.

**Frontend**

- global search bar;
- grouped result;
- recent search;
- no-access filtering.

### M17.2 — Operational Reporting

**Backend**

- report definition;
- parameter;
- scoped execution;
- output metadata.

**Frontend**

- report catalog;
- parameter form;
- result preview;
- export request.

### M17.3 — Dashboard dan KPI

**Backend**

- KPI aggregation;
- scheduled snapshot;
- freshness metadata.

**Frontend**

- operational dashboard;
- clinical dashboard;
- compliance dashboard;
- data freshness label.

**Exit gate M17**

- search dan report tidak membocorkan data lintas scope;
- export memerlukan permission dan tercatat di audit.

---

## M18 — API dan Integration

### M18.1 — API Foundation dan Developer Portal

**Backend**

- API authentication;
- versioning;
- standard response;
- idempotency;
- rate limit.

**Frontend**

- API client registry;
- credential management;
- API log view;
- documentation page.

### M18.2 — Integration Message dan Recovery

**Backend**

- endpoint;
- mapping;
- message;
- transactional outbox;
- retry;
- dead-letter;
- reconciliation.

**Frontend**

- integration dashboard;
- message detail;
- retry/reconcile action;
- mapping status.

### M18.3 — Healthcare Integration Baseline

**Backend**

- terminology mapping;
- FHIR adapter baseline;
- consent enforcement;
- external identifier mapping.

**Frontend**

- terminology mapping UI;
- external identifier tab;
- interoperability readiness dashboard.

**Exit gate M18**

- retry bersifat idempotent;
- seluruh payload dan response memiliki correlation ID;
- integrasi gagal dapat direkonsiliasi.

---

## M19 — Security, Observability, dan Operations

### M19.1 — Logging dan Observability Dashboard

**Backend**

- structured log;
- metric;
- trace/correlation;
- health probe;
- alert event.

**Frontend**

- operations dashboard;
- error rate;
- job status;
- integration health;
- database health.

### M19.2 — Security Operations

**Backend**

- security event;
- anomaly rule baseline;
- privileged activity;
- vulnerability register;
- incident workflow.

**Frontend**

- security dashboard;
- privileged activity view;
- vulnerability queue;
- incident detail.

### M19.3 — Backup, DR, dan Release Evidence

**Backend**

- backup schedule metadata;
- restore test;
- RPO/RTO evidence;
- release manifest;
- rollback record.

**Frontend**

- backup/restore dashboard;
- DR evidence;
- release-readiness checklist;
- release history.

**Exit gate M19**

- backup dapat dipulihkan;
- critical event dapat dipantau;
- release memiliki evidence, rollback plan, dan approval.

---

# 6. Urutan Eksekusi yang Direkomendasikan

## Wave 0 — Aplikasi Dapat Dibuka

```text
M00
```

Hasil demo: shell aplikasi, health dashboard, database migration.

## Wave 1 — Platform Administration

```text
M01 → M02 → M03 → M04 → M05
```

Hasil demo: tenant/hospital, user, role, dynamic menu, audit, reference data.

## Wave 2 — Clinical Eligibility dan Patient Foundation

```text
M06 → M07 → M08
```

Hasil demo: staff credentialing, privilege, patient, consent, appointment, secure evidence.

## Wave 3 — Clinical MVP

```text
M09 → M10
```

Hasil demo: homecare request sampai visit, kemudian episode woundcare intake sampai closure.

## Wave 4 — Shared Workflow dan Communication

```text
M11 → M12
```

Hasil demo: task inbox, approval, scheduler, notification center.

## Wave 5 — Commercial dan Governance

```text
M13 → M14 → M15 → M16
```

Hasil demo: inventory, billing, claim tenaga, quality dan patient safety.

## Wave 6 — Intelligence, Integration, dan Production Readiness

```text
M17 → M18 → M19
```

Hasil demo: search/reporting, integration monitoring, security dan operations dashboard.

---

# 7. Milestone Demo

## Milestone A — Platform Admin Demo

Selesai setelah M05:

- tenant dan hospital;
- user dan MFA;
- role/permission;
- dynamic menu;
- audit explorer;
- reference data.

## Milestone B — Patient and Workforce Demo

Selesai setelah M08:

- staff profile;
- credential dan privilege;
- patient registration;
- consent;
- appointment;
- secure clinical file.

## Milestone C — Homecare MVP Demo

Selesai setelah M09:

- patient mengajukan homecare;
- coordinator menjadwalkan;
- sistem menampilkan nurse eligible;
- nurse menjalankan EVV;
- supervisor meninjau completion.

## Milestone D — Woundcare E2E Demo

Selesai setelah M10:

```text
Intake
→ Screening
→ Episode
→ Assessment
→ Treatment Plan
→ Assignment
→ Homecare Visit
→ Procedure
→ Outcome
→ Closure
```

## Milestone E — Commercial Operations Demo

Selesai setelah M15:

- consumable usage;
- charge capture;
- invoice;
- payment/refund;
- nurse claim.

## Milestone F — Governance and Production Demo

Selesai setelah M19:

- quality/patient safety;
- report dan integration;
- security operations;
- backup/restore;
- release evidence.

---

# 8. Sprint Review Template

Pada akhir setiap sprint, tim wajib menunjukkan:

```text
1. Halaman/menu baru
2. Skenario bisnis yang sudah berjalan
3. Data yang tersimpan di database
4. Permission dan scope yang diuji
5. Audit event yang dihasilkan
6. Automated test result
7. UAT browser evidence
8. Known limitation
9. Dependency sprint berikutnya
```

---

# 9. Catatan Eksekusi

- Jangan membuat semua tabel satu modul sekaligus bila belum dipakai oleh UI sprint tersebut.
- Jangan menunda authorization dan audit ke sprint terakhir.
- Jangan menggunakan menu visibility sebagai pengganti backend authorization.
- Jangan menganggap halaman tampil berarti modul selesai; negative test dan scope isolation tetap wajib.
- Modul M11 dan M12 dapat dimulai paralel setelah M05 apabila kapasitas tim mencukupi.
- Inventory, billing, dan payroll tidak menghambat demonstrasi clinical MVP, tetapi wajib sebelum go-live komersial apabila masuk ruang lingkup rilis.
- Woundcare harus tetap menggunakan episode sebagai container klinis, sementara homecare visit menjadi aktivitas operasional di dalam episode.
