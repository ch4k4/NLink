# Blueprint Requirement Platform sesuai Permenkes Nomor 6 Tahun 2026

## Status Dokumen

- **Jenis:** Blueprint requirement
- **Ruang lingkup:** Database, RBAC, workflow, modul, keamanan, audit, dan kepatuhan
- **Acuan utama:** Peraturan Menteri Kesehatan Nomor 6 Tahun 2026 tentang Rumah Sakit
- **Status regulasi:** Berlaku
- **Tanggal penetapan:** 4 Juni 2026
- **Tanggal pengundangan:** 12 Juni 2026

> **Catatan:** Permenkes 6/2026 mengatur penyelenggaraan rumah sakit, bukan spesifikasi teknis perangkat lunak. Requirement dalam dokumen ini merupakan terjemahan arsitektural agar platform dapat mendukung pelaksanaan, pembuktian, dan audit kepatuhan. Kepatuhan akhir tetap bergantung pada konfigurasi, SOP, implementasi operasional, regulasi terkait, dan hasil audit.

---

## 1. Klasifikasi Requirement

### 1.1 Wajib regulatif

Fungsi yang diperlukan untuk menjalankan dan membuktikan kewajiban penyelenggaraan rumah sakit.

### 1.2 Wajib sistem

Kontrol aplikasi yang diperlukan agar kewajiban regulatif dapat dilaksanakan secara konsisten, aman, dan dapat diaudit.

### 1.3 Best practice

Kontrol yang tidak selalu disebut sebagai nama fitur di regulasi, tetapi diperlukan untuk membangun platform rumah sakit yang aman dan dapat dipertanggungjawabkan.

---

# 2. Arsitektur Organisasi Rumah Sakit

## 2.1 Struktur minimum

```text
Pemilik/Badan Hukum
└── Rumah Sakit
    ├── Unit/Instalasi
    │   ├── Rawat Jalan
    │   ├── Rawat Inap
    │   ├── IGD
    │   ├── Farmasi
    │   ├── Laboratorium
    │   ├── Radiologi
    │   ├── Rekam Medis
    │   └── Unit lainnya
    ├── Pimpinan Rumah Sakit
    ├── Dewan Pengawas
    ├── Komite/Tim/Penanggung Jawab
    └── Tenaga Medis dan Tenaga Kesehatan
```

## 2.2 Tabel inti organisasi

```text
organizations
hospitals
hospital_licenses
hospital_classifications
hospital_service_capabilities
organization_units
organization_positions
organization_structures
organization_structure_versions
legal_entities
hospital_owners
hospital_leaderships
supervisory_boards
supervisory_board_members
committees
committee_members
teams
team_members
responsible_officers
delegations_of_authority
```

## 2.3 Kolom minimum tabel `hospitals`

```text
id
organization_id
legal_entity_id
hospital_code
hospital_name
registration_number
ownership_type
hospital_type
hospital_classification_id
operational_status
address
province_code
regency_code
latitude
longitude
phone
email
director_name
effective_from
effective_until
created_at
updated_at
deleted_at
```

## 2.4 Business rule

- Semua transaksi wajib memiliki `hospital_id`.
- Semua transaksi unit wajib memiliki `organization_unit_id`.
- Data satu rumah sakit tidak boleh dapat diakses rumah sakit lain tanpa otorisasi eksplisit.
- Struktur organisasi harus memiliki versi dan periode berlaku.
- Perubahan pimpinan, unit, atau kewenangan tidak boleh menghapus riwayat sebelumnya.
- Delegasi kewenangan harus memiliki pemberi delegasi, penerima, ruang lingkup, alasan, dan periode berlaku.

---

# 3. Perizinan dan Klasifikasi Rumah Sakit

## 3.1 Modul wajib

### Hospital Licensing

Fitur minimum:

- registrasi izin;
- jenis izin;
- nomor izin;
- instansi penerbit;
- tanggal penerbitan;
- tanggal berlaku;
- status izin;
- dokumen pendukung;
- histori perubahan;
- reminder kedaluwarsa;
- verifikasi legal/compliance;
- approval;
- bukti verifikasi.

### Service Capability Registry

Mencatat kemampuan pelayanan rumah sakit:

- pelayanan medis umum;
- spesialis;
- subspesialis;
- gawat darurat;
- rawat jalan;
- rawat inap;
- pelayanan keperawatan;
- pelayanan penunjang;
- pelayanan lain sesuai izin.

## 3.2 Tabel

```text
license_types
hospital_licenses
hospital_license_documents
hospital_license_requirements
hospital_license_verifications
hospital_license_histories

service_capability_catalog
hospital_service_capabilities
service_capability_requirements
service_capability_evidences
service_capability_assessments

hospital_classifications
hospital_classification_histories
classification_assessments
classification_assessment_items
```

## 3.3 Business rule

- Layanan tidak boleh diaktifkan apabila kemampuan pelayanan belum disetujui.
- Layanan harus dikaitkan dengan SDM, ruangan, sarana, dan peralatan yang memenuhi syarat.
- Sistem harus memberikan reminder masa berlaku izin dan sertifikat.
- Perubahan klasifikasi harus menyimpan alasan, approver, dan dokumen keputusan.
- Tarif dan pemesanan layanan hanya boleh menggunakan layanan aktif.

---

# 4. SDM, Kredensial, dan Kewenangan Klinis

## 4.1 Tabel minimum

```text
users
staff
staff_profiles
medical_staff_profiles
nurse_profiles
health_worker_profiles
employment_records
professional_registrations
practice_licenses
education_records
training_records
competency_certificates
clinical_credentials
credentialing_applications
credentialing_reviews
clinical_privileges
clinical_privilege_items
privilege_approvals
privilege_restrictions
recredentialing_schedules
staff_unit_assignments
staff_service_assignments
staff_suspensions
```

## 4.2 Data minimum tenaga kesehatan

- identitas;
- profesi;
- status kepegawaian;
- unit kerja;
- nomor registrasi profesi;
- izin praktik;
- masa berlaku;
- pendidikan;
- kompetensi;
- sertifikasi;
- kewenangan klinis;
- pembatasan kewenangan;
- riwayat kredensial;
- riwayat disiplin;
- status aktif.

## 4.3 Business rule

Pengguna tidak boleh melakukan tindakan klinis hanya berdasarkan role.

Validasi tindakan klinis minimal memeriksa:

1. role pengguna;
2. unit penugasan;
3. status kepegawaian;
4. status registrasi dan izin praktik;
5. kewenangan klinis;
6. batas lokasi praktik;
7. periode berlaku;
8. status pembatasan atau suspensi.

Kewenangan klinis yang kedaluwarsa harus otomatis memblokir tindakan terkait. Override wajib mencatat alasan, approver, durasi, dan audit trail.

---

# 5. RBAC dan Kontrol Akses

## 5.1 Model minimum

Jangan menggunakan model sederhana:

```text
users.role_id
```

Gunakan:

```text
users
roles
permissions
role_permissions
user_role_assignments
user_permission_overrides
access_scopes
role_scope_rules
permission_conditions
delegated_access
break_glass_access
access_review_campaigns
access_review_items
access_decisions
```

## 5.2 Scope penugasan role

```text
tenant_id
hospital_id
organization_unit_id
service_id
valid_from
valid_until
assignment_status
```

## 5.3 Role minimum

### Governance

```text
hospital_owner
supervisory_board_member
hospital_director
medical_director
nursing_director
finance_director
operations_director
compliance_officer
legal_officer
internal_auditor
```

### Clinical Governance

```text
credentialing_chair
credentialing_reviewer
quality_manager
patient_safety_officer
risk_manager
ethics_officer
infection_control_officer
clinical_auditor
```

### Clinical Service

```text
doctor
specialist_doctor
dentist
nurse
midwife
pharmacist
laboratory_officer
radiology_officer
nutritionist
physiotherapist
other_health_worker
```

### Operational

```text
registration_officer
medical_record_officer
cashier
billing_officer
claim_officer
inventory_officer
procurement_officer
facility_officer
reporting_officer
```

### Platform

```text
tenant_admin
hospital_admin
security_admin
rbac_admin
system_operator
integration_operator
support_agent
```

## 5.4 Kelompok permission minimum

```text
IAM
ORGANIZATION
LICENSING
STAFF
CREDENTIALING
PATIENT
REGISTRATION
APPOINTMENT
EMERGENCY
OUTPATIENT
INPATIENT
EMR
NURSING
PHARMACY
LABORATORY
RADIOLOGY
PROCEDURE
OPERATING_ROOM
BLOOD_BANK
NUTRITION
MEDICAL_RECORD
BILLING
CLAIM
QUALITY
PATIENT_SAFETY
RISK
COMPLAINT
LEGAL
ETHICS
AUDIT
REPORTING
INTEGRATION
FACILITY
ASSET
INVENTORY
PROCUREMENT
SYSTEM
```

## 5.5 Permission kritis

```text
patient.read
patient.create
patient.update_identity
patient.merge
patient.restrict_access

emr.read
emr.create
emr.update_own
emr.amend
emr.sign
emr.cosign
emr.lock
emr.unlock
emr.break_glass

clinical_order.create
clinical_order.verify
clinical_order.cancel

medication.prescribe
medication.verify
medication.dispense
medication.administer

credential.review
credential.approve
privilege.grant
privilege.restrict
privilege.revoke

incident.create
incident.review
incident.investigate
incident.close
incident.view_identifiable

audit_log.read
audit_log.export

rbac.role.assign
rbac.permission.assign
rbac.override.grant
```

## 5.6 Segregation of Duties

Sistem harus mencegah kombinasi akses berisiko, antara lain:

- pembuat tagihan sekaligus penghapus pembayaran;
- pemohon kredensial sekaligus approver final;
- pembuat incident report sekaligus penghapus laporan;
- administrator sistem sekaligus auditor tunggal;
- prescriber sekaligus verifier farmasi pada transaksi tertentu;
- pembuat vendor sekaligus approver pembayaran;
- pembuat refund sekaligus approver refund;
- pengelola RBAC sekaligus approver aksesnya sendiri.

---

# 6. Workflow Pelayanan Pasien

## 6.1 Master Patient Index

```text
patients
patient_identifiers
patient_contacts
patient_addresses
patient_emergency_contacts
patient_insurances
patient_consent_preferences
patient_restrictions
patient_merge_histories
```

## 6.2 Kontrol minimum

- nomor rekam medis unik;
- deteksi pasien duplikat;
- merge pasien melalui approval;
- histori identitas tidak boleh hilang;
- dukungan pembatasan akses;
- pencatatan seluruh perubahan identitas.

## 6.3 Workflow umum

```text
Registrasi
→ Verifikasi identitas
→ Verifikasi penjamin
→ Penentuan layanan
→ Triage/asesmen awal
→ Encounter dibuat
→ Pemeriksaan klinis
→ Diagnosis
→ Rencana terapi
→ Order penunjang
→ Tindakan
→ Resep
→ Edukasi dan persetujuan
→ Billing/klaim
→ Discharge atau rujukan
→ Rekam medis dikunci
→ Pelaporan
```

## 6.4 Status encounter

```text
planned
arrived
registered
triaged
in_progress
awaiting_result
awaiting_medication
awaiting_payment
discharged
referred
cancelled
closed
```

## 6.5 Tabel encounter

```text
encounters
encounter_participants
encounter_locations
encounter_status_histories
clinical_assessments
diagnoses
clinical_problems
care_plans
clinical_notes
clinical_orders
clinical_results
procedures
medication_orders
medication_administrations
referrals
discharge_summaries
follow_up_plans
```

---

# 7. Rekam Medis Elektronik

## 7.1 Fitur minimum

- identitas pasien;
- episode dan encounter;
- asesmen;
- diagnosis;
- tindakan;
- resep;
- hasil laboratorium;
- hasil radiologi;
- catatan keperawatan;
- ringkasan pulang;
- rujukan;
- informed consent;
- autentikasi tenaga kesehatan;
- tanda tangan elektronik;
- timestamp;
- amendment;
- locking;
- audit trail;
- retensi;
- ekspor dokumen.

## 7.2 Database

```text
medical_records
medical_record_documents
medical_record_entries
clinical_document_types
clinical_document_versions
clinical_signatures
clinical_cosignatures
record_amendments
record_lock_events
record_access_logs
record_disclosures
record_retention_rules
record_archives
```

## 7.3 Aturan perubahan

Catatan yang telah ditandatangani tidak boleh diubah langsung.

Gunakan model:

```text
original_entry
amendment_entry
reason_for_amendment
amended_by
amended_at
approved_by
```

Versi asli wajib tetap tersimpan dan dapat diaudit.

---

# 8. Mutu dan Keselamatan Pasien

## 8.1 Quality Management

Fitur minimum:

- indikator mutu;
- target;
- numerator;
- denominator;
- sumber data;
- periode pengukuran;
- validasi;
- tren;
- analisis;
- tindak lanjut.

## 8.2 Patient Safety

Fitur minimum:

- pelaporan insiden;
- near miss;
- kejadian tidak diharapkan;
- kejadian sentinel;
- risk grading;
- investigasi;
- RCA;
- rekomendasi;
- CAPA;
- evaluasi efektivitas.

## 8.3 Clinical Audit

- topik audit;
- kriteria;
- sampel;
- hasil;
- gap;
- tindakan korektif;
- re-audit.

## 8.4 Database

```text
quality_indicators
quality_indicator_definitions
quality_measurements
quality_targets
quality_validation_records

patient_safety_incidents
incident_types
incident_severity_levels
incident_risk_scores
incident_investigations
root_cause_analyses
incident_recommendations

corrective_actions
preventive_actions
capa_evidences
capa_effectiveness_reviews

clinical_audits
clinical_audit_criteria
clinical_audit_samples
clinical_audit_findings
clinical_audit_actions
```

## 8.5 Workflow insiden

```text
Draft
→ Submitted
→ Initial screening
→ Risk grading
→ Investigator assigned
→ Investigation
→ RCA/analysis
→ Recommendation
→ CAPA assigned
→ Implementation
→ Effectiveness review
→ Closed
```

## 8.6 Kontrol sensitif

- identitas pelapor dapat dilindungi;
- laporan insiden tidak boleh dilihat semua staf;
- insiden tidak boleh dihapus;
- koreksi menggunakan addendum;
- akses laporan harus diaudit;
- dokumen investigasi dapat dipisahkan dari rekam medis pasien.

---

# 9. Komite, Tim, dan Tata Kelola Klinis

## 9.1 Desain generik

Jangan meng-hardcode hanya komite tertentu.

```text
governance_bodies
governance_body_types
governance_body_members
governance_body_functions
governance_body_authorities
governance_body_meetings
governance_body_decisions
governance_body_recommendations
governance_body_work_programs
governance_body_reports
```

## 9.2 Fungsi minimum

- kredensial;
- kewenangan klinis;
- etik dan disiplin;
- mutu;
- keselamatan pasien;
- manajemen risiko;
- audit klinis;
- evaluasi profesi;
- pencegahan infeksi;
- farmasi dan terapi;
- pengawasan pelayanan.

---

# 10. Hospital Bylaws, Kebijakan, dan SOP

## 10.1 Tabel

```text
policies
policy_types
policy_versions
policy_approvals
policy_distributions
policy_acknowledgements
standard_operating_procedures
work_instructions
clinical_guidelines
clinical_pathways
forms
```

## 10.2 Status dokumen

```text
draft
under_review
approved
effective
superseded
revoked
archived
```

## 10.3 Fitur minimum

- nomor dokumen;
- pemilik dokumen;
- versi;
- tanggal efektif;
- tanggal review;
- approval bertingkat;
- distribusi;
- acknowledgement;
- histori revisi;
- keterkaitan dengan regulasi;
- keterkaitan dengan audit;
- keterkaitan dengan CAPA.

---

# 11. Dewan Pengawas dan Pimpinan

## 11.1 Fitur minimum

- profil dewan pengawas;
- masa jabatan;
- SK pengangkatan;
- deklarasi konflik kepentingan;
- rapat;
- agenda;
- notulen;
- keputusan;
- rekomendasi;
- evaluasi pimpinan;
- tindak lanjut;
- laporan berkala.

## 11.2 Database

```text
board_terms
board_appointments
board_meetings
board_meeting_attendees
board_agendas
board_minutes
board_decisions
board_recommendations
leadership_evaluations
conflict_of_interest_declarations
```

---

# 12. Hak Pasien, Consent, Pengaduan, dan Disclosure

## 12.1 Modul minimum

- hak dan kewajiban pasien;
- general consent;
- informed consent;
- penolakan tindakan;
- withdrawal consent;
- pengaduan;
- permintaan informasi;
- permintaan salinan rekam medis;
- disclosure;
- survei pengalaman pasien;
- penyelesaian sengketa internal.

## 12.2 Database

```text
consent_types
patient_consents
consent_signatures
consent_withdrawals
treatment_refusals

complaints
complaint_categories
complaint_investigations
complaint_responses
complaint_resolutions
complaint_escalations

patient_information_requests
medical_record_requests
record_disclosures
```

## 12.3 Workflow pengaduan

```text
Received
→ Registered
→ Classified
→ Assigned
→ Investigated
→ Response prepared
→ Approved
→ Responded
→ Follow-up
→ Closed
```

---

# 13. Tarif, Billing, dan Klaim

## 13.1 Database

```text
service_catalog
tariff_schemes
tariff_versions
tariff_items
tariff_components
payer_contracts
payer_tariffs
patient_bills
bill_items
payments
payment_allocations
refunds
claims
claim_items
claim_submissions
claim_responses
claim_adjustments
```

## 13.2 Business rule

- tarif harus memiliki versi dan tanggal efektif;
- tarif lama tidak boleh ditimpa;
- diskon memerlukan kewenangan;
- pembatalan transaksi membutuhkan alasan;
- refund membutuhkan dual approval;
- perubahan nominal wajib diaudit;
- tarif penjamin harus legal dan terdokumentasi.

---

# 14. Pelaporan dan Integrasi

## 14.1 Reporting Engine

Platform harus mendukung:

- laporan kegiatan rumah sakit;
- laporan pelayanan;
- laporan tempat tidur;
- laporan SDM;
- laporan mutu;
- laporan keselamatan;
- laporan insiden;
- laporan penyakit;
- laporan keuangan;
- laporan perizinan;
- laporan pembinaan dan pengawasan.

## 14.2 Database laporan

```text
report_definitions
report_parameters
report_runs
report_outputs
report_submissions
report_submission_receipts
report_validation_errors
report_corrections
```

## 14.3 Integration Engine

```text
integration_endpoints
integration_credentials
integration_mappings
integration_messages
integration_message_logs
integration_failures
integration_retries
integration_reconciliations
```

## 14.4 Metadata integrasi minimum

- payload keluar;
- payload masuk;
- waktu kirim;
- response;
- status;
- retry;
- error;
- correlation ID;
- service account;
- sumber dan tujuan sistem.

---

# 15. Audit Trail

## 15.1 Event wajib

- login berhasil dan gagal;
- perubahan password;
- perubahan role;
- assignment permission;
- akses rekam medis;
- ekspor dan cetak data;
- perubahan identitas pasien;
- merge pasien;
- pembuatan dan perubahan catatan klinis;
- tanda tangan;
- amendment;
- order;
- hasil;
- resep;
- pemberian obat;
- perubahan tarif;
- refund;
- perubahan izin;
- perubahan kredensial;
- perubahan privilege;
- incident report;
- pembatalan transaksi;
- break-glass access.

## 15.2 Tabel

```text
audit_events
audit_event_details
security_events
access_logs
data_export_logs
break_glass_events
```

## 15.3 Kolom minimum

```text
event_id
tenant_id
hospital_id
actor_user_id
actor_role_id
action
resource_type
resource_id
patient_id
old_value_hash
new_value_hash
ip_address
user_agent
session_id
correlation_id
reason
event_at
```

## 15.4 Aturan

- audit log tidak boleh diedit melalui aplikasi;
- audit log tidak boleh dihapus administrator biasa;
- akses audit log juga harus diaudit;
- waktu server harus tersinkron;
- log sensitif harus dienkripsi;
- ekspor audit membutuhkan permission khusus.

---

# 16. Pembinaan, Pengawasan, dan Sanksi

## 16.1 Compliance Case Management

```text
regulatory_requirements
compliance_assessments
compliance_assessment_items
compliance_findings
regulatory_notices
administrative_sanctions
sanction_orders
remediation_plans
remediation_actions
remediation_evidences
compliance_verifications
```

## 16.2 Workflow self-assessment

```text
Requirement identified
→ Self-assessment
→ Gap found
→ Finding registered
→ Risk scored
→ Corrective plan
→ Responsible person assigned
→ Evidence uploaded
→ Internal verification
→ Management approval
→ Closed
```

## 16.3 Workflow teguran regulator

```text
Notice received
→ Legal verification
→ Executive escalation
→ Corrective order registered
→ Deadline assigned
→ Action plan approved
→ Evidence submitted
→ Verification
→ Regulatory response
→ Closed/escalated
```

---

# 17. Keamanan Platform

## 17.1 Kontrol minimum

- MFA untuk pengguna berprivilege tinggi;
- session timeout;
- password policy;
- device dan session management;
- encryption in transit;
- encryption at rest;
- secrets management;
- backup;
- restore test;
- disaster recovery;
- vulnerability management;
- secure coding;
- API authentication;
- rate limiting;
- IP restriction;
- masking data;
- row-level access control;
- field-level restriction;
- monitoring keamanan;
- incident response.

## 17.2 Break-glass workflow

```text
User requests emergency access
→ Reason mandatory
→ Temporary expanded access
→ Session prominently marked
→ All access logged
→ Supervisor notified
→ Retrospective review
→ Access automatically expires
```

---

# 18. Retensi, Arsip, dan Legal Hold

## 18.1 Database

```text
retention_policies
retention_policy_rules
legal_holds
legal_hold_records
archive_jobs
disposal_requests
disposal_approvals
disposal_certificates
```

## 18.2 Fitur minimum

- aturan retensi berdasarkan jenis data;
- arsip otomatis;
- legal hold;
- blokir penghapusan;
- approval pemusnahan;
- berita acara;
- histori pemusnahan;
- pengecualian apabila terdapat perkara atau audit.

> Periode retensi spesifik harus diverifikasi terhadap regulasi rekam medis dan kebijakan rumah sakit yang berlaku.

---

# 19. Modul Minimum Platform

## 19.1 Tier 1 — sebelum operasional klinis

1. Organization & Hospital Master
2. IAM dan RBAC
3. Staff & Credentialing
4. Patient Master Index
5. Registration & Encounter
6. Electronic Medical Record
7. Clinical Order Management
8. Pharmacy
9. Laboratory/Radiology Integration
10. Billing
11. Audit Trail
12. Document & Consent Management
13. Reporting
14. Integration Engine
15. Security Administration

## 19.2 Tier 2 — tata kelola penuh

1. Quality Management
2. Patient Safety
3. Risk Management
4. Clinical Audit
5. Complaint Management
6. Governance Body Management
7. Policy and SOP Management
8. Licensing & Compliance
9. Supervisory Board
10. CAPA
11. Legal Case Management
12. Facility & Equipment Compliance

## 19.3 Tier 3 — sesuai jenis pelayanan

1. Rawat inap
2. IGD
3. Bed Management
4. Operating Room
5. ICU
6. Blood Bank
7. Nutrition
8. Rehabilitation
9. Homecare
10. Woundcare
11. Telemedicine
12. Teaching Hospital
13. Mobile atau Ship Hospital

---

# 20. Prioritas Implementasi Nerslink/CRM Medis

Platform yang saat ini berfokus pada klinik, homecare, dan woundcare belum dapat langsung diklaim sebagai SIMRS yang memenuhi seluruh kebutuhan rumah sakit.

Gap utama:

- rawat inap;
- IGD;
- tata kelola rumah sakit;
- mutu;
- keselamatan pasien;
- pelaporan rumah sakit;
- credentialing;
- clinical privilege;
- compliance case management;
- pengawasan dan sanksi;
- fasilitas dan peralatan;
- integrasi layanan penunjang.

## 20.1 Fase 1 — Compliance Foundation

- tenant dan hospital scope;
- organization unit;
- RBAC granular;
- immutable audit trail;
- staff credentialing;
- document management;
- policy dan SOP;
- licensing registry;
- data segregation.

## 20.2 Fase 2 — Clinical Core

- patient master;
- encounter;
- EMR;
- nursing care;
- clinical order;
- medication;
- consent;
- referral;
- discharge.

## 20.3 Fase 3 — Governance

- quality;
- patient safety;
- incident reporting;
- CAPA;
- clinical audit;
- credentialing;
- privilege management;
- committee/team engine.

## 20.4 Fase 4 — Hospital Operation

- rawat jalan;
- rawat inap;
- IGD;
- bed management;
- pharmacy;
- laboratory;
- radiology;
- billing;
- claim.

## 20.5 Fase 5 — Regulatory Integration

- pelaporan nasional;
- SATUSEHAT;
- compliance dashboard;
- regulator evidence package;
- inspection workflow;
- sanction remediation.

---

# 21. Acceptance Criteria Tingkat Platform

Platform dianggap siap mendukung kepatuhan apabila:

- seluruh transaksi memiliki `tenant_id` dan `hospital_id`;
- tidak ada kebocoran data antar rumah sakit;
- setiap tenaga klinis memiliki kredensial dan privilege terverifikasi;
- catatan klinis memiliki author, timestamp, status, dan signature;
- perubahan catatan menggunakan amendment;
- akses rekam medis tercatat;
- role dan permission ditinjau berkala;
- izin dan sertifikat memiliki reminder;
- insiden keselamatan dapat dilaporkan dan ditindaklanjuti;
- CAPA dapat ditelusuri sampai bukti;
- organisasi, komite, tim, dan delegasi terdokumentasi;
- laporan regulator memiliki jejak pengiriman;
- backup dapat dipulihkan;
- break-glass dapat diaudit;
- objek kritis tidak menggunakan hard delete;
- bukti kepatuhan dapat diekspor untuk audit.

---

# 22. Kesimpulan

Empat fondasi paling kritis:

1. **Hospital-scoped RBAC**
2. **Credentialing dan Clinical Privilege**
3. **Immutable Audit Trail**
4. **Mutu, Keselamatan Pasien, dan Compliance Case Management**

Detail tabel dan workflow dalam dokumen ini merupakan desain arsitektural, bukan struktur database yang ditentukan secara eksplisit oleh Permenkes.

Platform tidak boleh dinyatakan **100% compliant** hanya karena memiliki daftar fitur. Kepatuhan membutuhkan:

- implementasi teknis;
- SOP;
- kebijakan internal;
- konfigurasi rumah sakit;
- pengoperasian yang konsisten;
- regulasi terkait;
- pengujian;
- bukti audit;
- penilaian hukum dan operasional.

---

# Referensi

1. JDIH Kementerian Kesehatan Republik Indonesia, **Peraturan Menteri Kesehatan Nomor 6 Tahun 2026 tentang Rumah Sakit**.
2. Regulasi rekam medis, perlindungan data pribadi, transaksi elektronik, keselamatan pasien, akreditasi, SATUSEHAT, dan regulasi teknis lain yang masih berlaku harus digunakan sebagai acuan tambahan.
