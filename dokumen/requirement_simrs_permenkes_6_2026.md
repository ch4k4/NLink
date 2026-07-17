# Requirement SIMRS Berdasarkan Permenkes Nomor 6 Tahun 2026

> **Dokumen kerja arsitektur dan compliance mapping SIMRS**  
> Regulasi acuan: Peraturan Menteri Kesehatan Nomor 6 Tahun 2026 tentang Rumah Sakit  
> Status regulasi: Berlaku  
> Tanggal penetapan: 4 Juni 2026  
> Tanggal pengundangan: 12 Juni 2026

## 1. Tujuan Dokumen

Dokumen ini menerjemahkan ketentuan Permenkes Nomor 6 Tahun 2026 tentang Rumah Sakit menjadi kebutuhan sistem informasi manajemen rumah sakit (SIMRS), khususnya:

- kebutuhan modul;
- kebutuhan database;
- kebutuhan RBAC dan otorisasi;
- kebutuhan workflow;
- kebutuhan integrasi;
- kebutuhan keamanan dan audit;
- prioritas implementasi;
- risiko desain arsitektur.

Permenkes tidak menetapkan struktur tabel, endpoint API, desain antarmuka, atau arsitektur teknis SIMRS secara rinci. Karena itu, requirement dalam dokumen ini merupakan hasil pemetaan ketentuan hukum dan tata kelola rumah sakit menjadi kontrol aplikasi.

---

# 2. Kesimpulan Utama

SIMRS yang hanya berisi modul pendaftaran, rawat jalan, rawat inap, farmasi, kasir, dan billing belum cukup.

SIMRS perlu berkembang menjadi platform tata kelola rumah sakit yang mencakup:

1. pelayanan klinis;
2. rekam medis elektronik;
3. kredensial dan kewenangan klinis;
4. mutu dan keselamatan pasien;
5. insiden dan manajemen risiko;
6. organisasi, komite, dan pengawasan;
7. sarana, prasarana, dan alat kesehatan;
8. pelaporan dan integrasi nasional;
9. keuangan dan unit cost;
10. pengaduan, etik, hukum, dan kepatuhan;
11. audit trail dan kerahasiaan;
12. pendidikan dan penelitian apabila relevan.

---

# 3. Modul Inti SIMRS

## 3.1 Enterprise Master Data dan Struktur Rumah Sakit

### Fungsi Minimum

- profil badan hukum dan pemilik rumah sakit;
- profil rumah sakit;
- lokasi dan cabang pelayanan;
- jenis rumah sakit;
- klasifikasi dan kemampuan pelayanan;
- kelompok pelayanan;
- direktorat, departemen, divisi, instalasi, unit, komite, dan satuan;
- unit pelayanan;
- unit biaya;
- kamar, tempat tidur, kelas, ICU, dan kapasitas pelayanan;
- master tenaga medis, tenaga kesehatan, dan tenaga penunjang;
- master sarana, prasarana, dan alat kesehatan;
- struktur organisasi berbasis periode berlaku;
- relasi pejabat, pimpinan, kepala unit, dan penanggung jawab.

### Entitas Database Minimum

```text
organizations
hospital_legal_entities
hospital_profiles
hospital_locations
hospital_service_groups
hospital_service_capabilities
hospital_classifications
organization_units
organization_unit_types
organization_unit_relations
facilities
rooms
beds
medical_equipment
service_units
cost_centers
```

---

## 3.2 Registrasi, ADT, dan Manajemen Pasien

### Fungsi Minimum

- master pasien tunggal;
- nomor rekam medis;
- identitas dan demografi;
- penjamin dan pembiayaan;
- registrasi rawat jalan;
- registrasi IGD;
- admisi rawat inap;
- transfer antarunit;
- mutasi tempat tidur;
- cuti perawatan;
- discharge;
- kematian;
- pembatalan dan koreksi administratif;
- identifikasi pasien rentan;
- kebutuhan disabilitas;
- kontak keluarga dan penanggung jawab;
- status pasien tidak mampu;
- antrean;
- appointment.

### Entitas Database Minimum

```text
patients
patient_identifiers
patient_contacts
patient_addresses
patient_guarantors
patient_vulnerabilities
appointments
queues
registrations
encounters
admissions
bed_assignments
patient_transfers
discharges
death_records
```

---

## 3.3 Rekam Medis Elektronik

### Fungsi Minimum

- episode dan encounter klinis;
- SOAP dan CPPT;
- diagnosis;
- prosedur;
- alergi;
- masalah klinis;
- tanda vital;
- asesmen awal dan lanjutan;
- instruksi medis;
- catatan keperawatan;
- rencana asuhan;
- order;
- hasil pemeriksaan;
- resume medis;
- edukasi pasien;
- informed consent;
- discharge planning;
- rujukan;
- dokumen klinis;
- tanda tangan elektronik;
- koreksi dan amendment;
- penguncian dokumen;
- riwayat versi;
- akses darurat atau break-glass;
- audit akses rekam medis.

### Entitas Database Minimum

```text
clinical_encounters
clinical_notes
clinical_note_versions
diagnoses
procedures
patient_problems
allergies
vital_signs
care_plans
clinical_orders
observations
clinical_documents
medical_summaries
consents
referrals
document_signatures
record_amendments
record_access_logs
```

### Kontrol Wajib

- dokumen klinis tidak dihapus langsung;
- koreksi menghasilkan versi baru;
- identitas pembuat dan penandatangan tersimpan;
- setiap akses dapat ditelusuri;
- akses berdasarkan hubungan perawatan dan kewenangan klinis;
- tersedia retensi dan legal hold.

---

## 3.4 Order Management dan Pelayanan Penunjang

### Fungsi Minimum

- computerized physician order entry;
- order laboratorium;
- order radiologi;
- order farmasi;
- order tindakan;
- order rehabilitasi;
- order gizi;
- order transfusi;
- status order dari permintaan sampai hasil;
- specimen tracking;
- validasi hasil;
- hasil kritis;
- acknowledgment hasil kritis;
- integrasi LIS, RIS, PACS, dan alat;
- pembatalan dengan alasan;
- audit perubahan order.

### Entitas Database Minimum

```text
service_orders
order_items
order_status_histories
specimens
laboratory_results
radiology_results
critical_results
critical_result_acknowledgements
procedure_results
```

---

## 3.5 Farmasi dan Penggunaan Obat Rasional

### Fungsi Minimum

- formularium rumah sakit;
- katalog obat;
- prescribing;
- verifikasi farmasi;
- dispensing;
- medication administration record;
- rekonsiliasi obat;
- pemeriksaan alergi dan interaksi;
- high-alert medication;
- LASA;
- stok dan batch;
- kedaluwarsa;
- recall;
- narkotika dan psikotropika;
- penggunaan antibiotik;
- evaluasi penggunaan obat;
- medication error;
- antimicrobial stewardship.

### Entitas Database Minimum

```text
medications
hospital_formularies
formulary_items
prescriptions
prescription_items
pharmacy_verifications
dispensations
medication_administrations
medication_reconciliations
drug_batches
drug_inventory_transactions
antimicrobial_reviews
medication_incidents
```

---

## 3.6 Billing, Tarif, Pembiayaan, dan Unit Cost

### Fungsi Minimum

- master tarif berbasis periode berlaku;
- paket pelayanan;
- charge capture;
- tarif berdasarkan kelas;
- kontrak penjamin;
- estimasi biaya;
- deposit;
- invoice;
- pembayaran;
- piutang;
- diskon dan pembebasan biaya;
- subsidi pasien tidak mampu;
- klaim penjamin;
- costing;
- unit cost;
- revenue center;
- cost center;
- rekonsiliasi pelayanan dengan billing;
- pembatalan transaksi;
- refund;
- approval transaksi sensitif.

### Entitas Database Minimum

```text
service_tariffs
tariff_versions
service_packages
charge_items
invoices
invoice_items
payments
refunds
guarantor_contracts
claims
claim_items
cost_centers
cost_allocations
unit_cost_calculations
financial_approvals
```

---

# 4. Modul Tata Kelola Fundamental

## 4.1 Credentialing dan Clinical Privilege

### Fungsi Minimum

- profil tenaga medis dan tenaga kesehatan;
- STR;
- SIP;
- pendidikan;
- pelatihan;
- sertifikasi;
- masa berlaku dokumen;
- pengajuan kredensial;
- verifikasi dokumen;
- penilaian kompetensi;
- rekomendasi komite;
- clinical privilege;
- surat penugasan klinis;
- periode privilege;
- pembatasan privilege;
- suspend dan revoke;
- recredentialing;
- notifikasi kedaluwarsa;
- mapping privilege ke diagnosis, prosedur, unit, alat, dan level supervisi.

### Entitas Database Minimum

```text
workforce_profiles
professional_licenses
professional_certifications
credentialing_cases
credentialing_documents
credentialing_assessments
clinical_privileges
privilege_procedures
clinical_assignments
recredentialing_cycles
privilege_restrictions
```

### Aturan Otorisasi Klinis

SIMRS harus mencegah tenaga melakukan atau menandatangani tindakan apabila:

- privilege tidak tersedia;
- privilege sudah kedaluwarsa;
- SIP tidak aktif;
- tenaga sedang disuspensi;
- tindakan berada di luar unit penugasan;
- tindakan memerlukan supervisi tetapi supervisor tidak tercatat.

Hak klinis dihitung dari:

```text
role
+ unit assignment
+ professional license
+ clinical privilege
+ employment status
+ service location
+ time validity
+ patient relationship
```

---

## 4.2 Mutu dan Keselamatan Pasien

### Fungsi Minimum

- katalog indikator mutu;
- definisi numerator dan denominator;
- sumber data indikator;
- target dan ambang batas;
- pengumpulan data otomatis atau manual;
- validasi data;
- dashboard tren;
- pelaporan periodik;
- insiden keselamatan pasien;
- grading risiko;
- investigasi;
- root cause analysis;
- FMEA;
- corrective and preventive action;
- monitoring tindak lanjut;
- pengalaman pasien;
- survei kepuasan;
- keluhan pelayanan;
- program peningkatan mutu;
- audit mutu;
- bukti akreditasi.

### Entitas Database Minimum

```text
quality_indicators
quality_indicator_definitions
quality_measurements
quality_targets
patient_safety_incidents
incident_classifications
incident_investigations
root_cause_analyses
risk_registers
risk_assessments
corrective_actions
preventive_actions
patient_experience_surveys
quality_programs
```

---

## 4.3 Manajemen Risiko Rumah Sakit

### Fungsi Minimum

- enterprise risk register;
- clinical risk;
- operational risk;
- financial risk;
- legal and compliance risk;
- information security risk;
- risk owner;
- likelihood;
- impact;
- inherent risk;
- kontrol;
- residual risk;
- treatment plan;
- deadline;
- escalation;
- evidence;
- monitoring efektivitas kontrol;
- dashboard risiko pimpinan;
- dashboard risiko dewan pengawas.

---

## 4.4 Komite, Satuan, dan Tata Kelola Keputusan

SIMRS sebaiknya menggunakan configurable governance engine, bukan aplikasi terpisah untuk setiap komite.

### Fungsi Minimum

- registrasi komite;
- dasar pembentukan;
- masa jabatan;
- anggota;
- konflik kepentingan;
- agenda;
- rapat;
- quorum;
- notulen;
- voting;
- keputusan;
- rekomendasi;
- penugasan tindak lanjut;
- deadline;
- bukti penyelesaian;
- arsip keputusan;
- akses terbatas per komite.

### Entitas Database Minimum

```text
committees
committee_terms
committee_members
committee_meetings
meeting_agendas
meeting_minutes
committee_decisions
committee_recommendations
recommendation_actions
conflict_of_interest_declarations
```

---

## 4.5 Audit Klinis dan Profesional

### Fungsi Minimum

- audit klinis;
- peer review;
- case review;
- mortality review;
- morbidity review;
- kepatuhan clinical pathway;
- evaluasi dokumentasi;
- supervisi;
- evaluasi kinerja klinis;
- rekomendasi perbaikan;
- hubungan hasil audit dengan recredentialing;
- kerahasiaan peer review.

### Entitas Database Minimum

```text
clinical_audits
audit_criteria
audit_samples
audit_findings
peer_reviews
case_reviews
professional_evaluations
supervision_records
professional_improvement_plans
```

---

## 4.6 PPI dan Resistensi Antimikroba

### Fungsi Minimum

- surveilans infeksi;
- healthcare-associated infection;
- bundle compliance;
- isolasi pasien;
- outbreak detection;
- penggunaan antibiotik;
- pola resistensi;
- antibiogram;
- audit antibiotik;
- hand hygiene;
- paparan petugas;
- vaksinasi tenaga;
- investigasi outbreak;
- pelaporan komite PPI.

### Entitas Database Minimum

```text
infection_surveillance_cases
healthcare_associated_infections
isolation_orders
infection_control_bundles
hand_hygiene_observations
antibiotic_usage
antimicrobial_resistance_results
outbreak_cases
outbreak_investigations
```

---

## 4.7 Pengawasan Internal

### Fungsi Minimum

- audit universe;
- annual audit plan;
- audit engagement;
- working paper;
- temuan;
- klasifikasi risiko;
- rekomendasi;
- management response;
- action plan;
- evidence;
- due date;
- overdue escalation;
- closure;
- dashboard tindak lanjut;
- kontrol independensi auditor.

### Entitas Database Minimum

```text
audit_universes
audit_plans
audit_engagements
audit_workpapers
audit_findings
audit_recommendations
management_responses
audit_action_plans
audit_evidence
```

---

## 4.8 Pengaduan, Etik, Hukum, dan Kepatuhan

### Fungsi Minimum

- kanal pengaduan;
- identitas pengadu terenkripsi;
- anonimisasi untuk petugas tertentu;
- klasifikasi pengaduan;
- pihak yang diadukan;
- kronologi;
- waktu kejadian;
- attachment bukti;
- triage;
- conflict check;
- penunjukan investigator;
- tim panel ad hoc;
- investigasi;
- legal hold;
- keputusan;
- tindak lanjut;
- pelaporan regulator;
- perlindungan whistleblower;
- audit akses.

### Entitas Database Minimum

```text
complaints
complainant_identities
complaint_parties
complaint_events
complaint_evidence
complaint_triages
investigation_panels
investigation_members
investigation_findings
complaint_decisions
legal_holds
```

---

# 5. Modul Operasional Pendukung

## 5.1 SDM, Kompetensi, dan Penjadwalan

- employment record;
- organisasi dan unit penempatan;
- kompetensi;
- uraian jabatan;
- roster;
- shift;
- on-call;
- beban kerja;
- pelatihan;
- continuous professional development;
- penilaian kinerja;
- disiplin;
- masa berlaku kontrak dan izin;
- health surveillance petugas;
- integrasi dengan clinical privilege.

---

## 5.2 Sarana, Prasarana, dan Alat Kesehatan

- asset register;
- lokasi aset;
- klasifikasi alat;
- status laik pakai;
- kalibrasi;
- preventive maintenance;
- corrective maintenance;
- downtime;
- recall;
- inspeksi;
- sertifikat;
- masa berlaku;
- kebutuhan pelayanan versus ketersediaan alat;
- fasilitas kritis;
- utilitas rumah sakit;
- listrik;
- gas medik;
- air;
- sistem keselamatan;
- tiket kerusakan.

---

## 5.3 Logistik dan Persediaan

- master item;
- obat;
- alat kesehatan;
- bahan medis habis pakai;
- gudang;
- min-max stock;
- batch;
- expiry;
- FEFO;
- purchase request;
- procurement;
- purchase order;
- receiving;
- quality inspection;
- transfer;
- issue;
- return;
- stock opname;
- recall;
- traceability item sampai pasien;
- vendor performance.

---

## 5.4 Rujukan dan Continuity of Care

- rujukan masuk;
- rujukan keluar;
- rujukan internal;
- ringkasan klinis;
- alasan rujukan;
- fasilitas tujuan;
- komunikasi antarfasilitas;
- acceptance;
- transportasi;
- hasil rujukan balik;
- follow-up;
- integrasi sistem rujukan nasional apabila tersedia.

---

## 5.5 Kedaruratan, Bencana, dan Business Continuity

- emergency mode;
- mass casualty registration;
- temporary patient identity;
- disaster command structure;
- triage bencana;
- bed surge;
- resource allocation;
- emergency inventory;
- downtime procedures;
- offline documentation;
- recovery dan reconciliation;
- incident command log;
- emergency contact tree;
- drill dan evaluasi.

---

## 5.6 Rumah Sakit Pendidikan

Modul ini bersifat kondisional.

### Fungsi Minimum

- afiliasi perguruan tinggi;
- peserta didik;
- pendidik dan penyelia;
- rotasi klinik;
- daya tampung;
- rasio pembimbing;
- logbook digital;
- penilaian peserta;
- jam dan beban kerja;
- supervisi;
- akses rekam medis berdasarkan level;
- pencatatan kegiatan pendidikan;
- integrasi Sistem Informasi Kesehatan Nasional.

---

## 5.7 Penelitian Klinis

Modul ini bersifat kondisional.

### Fungsi Minimum

- registrasi penelitian;
- protokol;
- principal investigator;
- anggota penelitian;
- persetujuan etik;
- informed consent penelitian;
- conflict of interest;
- subjek penelitian;
- adverse event;
- investigational product;
- monitoring;
- hasil penelitian;
- akses data terbatas;
- pseudonymization;
- integrasi sistem nasional.

---

# 6. Requirement RBAC SIMRS

## 6.1 Model Otorisasi

SIMRS tidak cukup menggunakan RBAC sederhana.

Model yang disarankan:

```text
RBAC
+ organization scope
+ hospital scope
+ unit scope
+ location scope
+ patient relationship
+ clinical privilege
+ purpose of use
+ temporal restriction
+ segregation of duties
```

---

## 6.2 Role Minimum

### Administratif

- Platform Administrator
- Hospital Administrator
- IAM Administrator
- Registration Officer
- Admission Officer
- Medical Record Officer
- Casemix Officer
- Billing Officer
- Cashier
- Claim Officer
- Procurement Officer
- Inventory Officer
- Finance Officer
- HR Officer

### Klinis

- General Practitioner
- Specialist Doctor
- Dentist
- Nurse
- Midwife
- Pharmacist
- Pharmacy Technician
- Laboratory Analyst
- Radiographer
- Nutritionist
- Physiotherapist
- Other Health Professional
- Clinical Supervisor
- Clinical Educator
- Student or Trainee

### Tata Kelola

- Hospital Director
- Unit Head
- Medical Committee
- Nursing or Health Professional Committee
- Quality Committee
- Patient Safety Team
- Infection Control Committee
- Pharmacy and Therapeutics Committee
- Ethics and Legal Committee
- Risk Management Committee
- Internal Audit
- Supervisory Board
- Credentialing Assessor
- Complaint Investigator
- Ad Hoc Panel Member

### Eksternal Terbatas

- Patient
- Patient Family or Proxy
- External Auditor
- Regulator
- Insurance Verifier
- Referral Facility
- Research Monitor

---

## 6.3 Permission Domain Minimum

```text
IAM.*
ORGANIZATION.*
PATIENT.*
REGISTRATION.*
APPOINTMENT.*
ENCOUNTER.*
EMR.*
CONSENT.*
ORDER.*
LAB.*
RADIOLOGY.*
PHARMACY.*
NURSING.*
INPATIENT.*
EMERGENCY.*
SURGERY.*
REFERRAL.*
BILLING.*
CLAIM.*
FINANCE.*
INVENTORY.*
PROCUREMENT.*
ASSET.*
EQUIPMENT.*
CREDENTIALING.*
PRIVILEGE.*
QUALITY.*
PATIENT_SAFETY.*
RISK.*
INFECTION_CONTROL.*
ANTIMICROBIAL.*
COMMITTEE.*
AUDIT.*
COMPLAINT.*
LEGAL.*
EDUCATION.*
RESEARCH.*
REPORTING.*
INTEGRATION.*
SECURITY.*
```

---

## 6.4 Permission Sensitif yang Harus Dipisah

```text
emr.read
emr.create
emr.sign
emr.amend
emr.unlock
emr.break_glass

prescription.create
prescription.verify
medication.dispense
medication.administer

invoice.create
invoice.approve
payment.receive
refund.request
refund.approve

credentialing.assess
credentialing.recommend
clinical_privilege.approve
clinical_privilege.revoke

incident.report
incident.investigate
incident.approve_closure

complaint.view_identity
complaint.investigate
complaint.decide

audit.finding.create
audit.response.submit
audit.finding.close
```

---

## 6.5 Segregation of Duties

| Proses | Pemisahan |
|---|---|
| Refund | Pemohon berbeda dari approver |
| Pengadaan | Pemohon, approver, dan penerima barang dipisahkan |
| Kredensial | Pemohon, assessor, dan pemberi keputusan dipisahkan |
| Clinical privilege | Tenaga yang dinilai tidak menyetujui privilege sendiri |
| Audit internal | Auditor tidak mengaudit aktivitas sendiri |
| Insiden | Pelapor, investigator, dan approver closure dapat dipisahkan |
| Pengaduan | Pihak yang diadukan tidak menjadi investigator |
| Rekam medis | Pembuat dokumen tidak dapat menghapus jejak perubahan |
| Obat | Prescriber, verifier, dispenser, dan administrator dicatat terpisah |
| Master tarif | Pembuat perubahan berbeda dari approver aktivasi |

---

# 7. Workflow Wajib

## 7.1 Kredensial dan Privilege

```text
Pengajuan
→ pemeriksaan kelengkapan
→ verifikasi dokumen
→ penilaian kompetensi
→ peer review
→ rekomendasi komite
→ keputusan pimpinan
→ penerbitan clinical privilege
→ penugasan klinis
→ monitoring
→ recredentialing / suspend / revoke
```

## 7.2 Insiden Keselamatan Pasien

```text
Pelaporan
→ validasi awal
→ klasifikasi insiden
→ grading risiko
→ immediate action
→ penunjukan investigator
→ investigasi / RCA
→ rekomendasi
→ CAPA
→ verifikasi efektivitas
→ closure
→ agregasi indikator
```

## 7.3 Audit Klinis

```text
Penetapan topik
→ kriteria audit
→ pemilihan sampel
→ pengumpulan data
→ analisis gap
→ rekomendasi
→ perbaikan
→ re-audit
→ evaluasi hasil
```

## 7.4 Pengaduan

```text
Penerimaan
→ proteksi identitas
→ validasi administratif
→ triage
→ pemeriksaan konflik kepentingan
→ penunjukan panel atau investigator
→ pengumpulan fakta dan bukti
→ klarifikasi
→ keputusan
→ tindak lanjut
→ pelaporan
→ arsip dan retensi
```

## 7.5 Pelaporan Nasional

```text
Data transaksi
→ validasi
→ standardisasi kode
→ agregasi atau transformasi
→ persetujuan penanggung jawab
→ pengiriman
→ acknowledgment
→ error handling
→ perbaikan
→ resubmission
→ rekonsiliasi
```

## 7.6 Perubahan Data Rumah Sakit

```text
Permintaan perubahan
→ verifikasi dokumen
→ approval
→ effective dating
→ pembaruan master data
→ sinkronisasi sistem nasional
→ verifikasi hasil
→ audit trail
```

---

# 8. Requirement Integrasi

## 8.1 Integration Gateway

Komponen minimum:

- API gateway;
- FHIR adapter;
- terminology service;
- mapping engine;
- message queue;
- transactional outbox;
- retry;
- dead-letter queue;
- idempotency;
- consent enforcement;
- integration audit;
- data validation;
- reconciliation dashboard.

## 8.2 Integrasi Prioritas

- SATUSEHAT;
- Sistem Informasi Kesehatan Nasional;
- BPJS atau penjamin;
- laboratorium;
- radiologi dan PACS;
- alat medis;
- farmasi;
- payment gateway atau bank;
- Dukcapil apabila dasar hukum dan akses tersedia;
- sistem rujukan;
- sistem pelaporan regulator;
- sistem penelitian dan pendidikan apabila relevan.

---

# 9. Requirement Keamanan dan Audit

## 9.1 Kontrol Minimum

- MFA untuk akun istimewa;
- identitas pengguna unik;
- role dan scope-based access;
- encryption in transit;
- encryption at rest;
- field-level encryption untuk identitas pengadu;
- immutable audit trail;
- break-glass;
- session timeout;
- device dan login history;
- privileged access management;
- periodic access review;
- temporary access;
- approval workflow;
- record versioning;
- backup terenkripsi;
- disaster recovery;
- legal hold;
- data retention;
- monitoring dan alerting;
- export control;
- watermark dokumen sensitif;
- anomaly detection;
- audit perubahan konfigurasi.

## 9.2 Audit Event Minimum

```text
login dan logout
login gagal
MFA event
lihat rekam medis
buat, ubah, dan tanda tangan rekam medis
amendment
break-glass
download, export, dan print
perubahan role
perubahan privilege
perubahan tarif
refund
perubahan master data
pengiriman laporan
gagal integrasi
akses identitas pengadu
perubahan hasil pemeriksaan
perubahan atau pembatalan order
```

---

# 10. Prioritas Implementasi

## P0 — Fondasi Wajib

1. IAM, RBAC, scope, dan audit trail.
2. Enterprise master data.
3. Pasien, registrasi, ADT, dan encounter.
4. Rekam medis elektronik.
5. Order management.
6. Farmasi.
7. Billing dan klaim.
8. Pelaporan dan integration gateway.
9. Kerahasiaan, consent, dan security controls.
10. Credentialing dan clinical privilege.

## P1 — Tata Kelola Rumah Sakit

1. Mutu dan indikator.
2. Insiden keselamatan pasien.
3. Manajemen risiko.
4. Komite dan tindak lanjut keputusan.
5. Audit klinis.
6. PPI dan resistensi antimikroba.
7. Pengawasan internal.
8. Pengaduan, etik, dan hukum.
9. Asset, fasilitas, dan alat kesehatan.
10. Unit cost dan laporan keuangan.

## P2 — Kondisional

1. Rumah sakit pendidikan.
2. Penelitian klinis.
3. Rumah sakit bergerak.
4. Rumah sakit lapangan.
5. Multi-hospital group.
6. Advanced analytics.
7. Patient engagement lanjutan.

---

# 11. Bounded Context yang Direkomendasikan

```text
Platform Kernel
├── IAM
├── RBAC and Policy
├── Organization and Scope
├── Audit
├── Workflow
├── Document
├── Consent and Privacy
├── Integration
├── Notification
└── Reporting

Clinical Domain
├── Patient
├── Appointment
├── Registration and ADT
├── Encounter
├── EMR
├── Order Management
├── Nursing
├── Pharmacy
├── Laboratory
├── Radiology
├── Inpatient
├── Emergency
├── Surgery
└── Referral

Governance Domain
├── Credentialing
├── Clinical Privilege
├── Committee
├── Quality
├── Patient Safety
├── Risk Management
├── Infection Control
├── Clinical Audit
├── Ethics and Legal
├── Complaint
└── Internal Audit

Enterprise Domain
├── Workforce
├── Facility
├── Asset and Equipment
├── Inventory
├── Procurement
├── Billing
├── Claim
├── Finance
├── Costing
├── Education
└── Research
```

---

# 12. Risiko Desain Fundamental

## 12.1 Menganggap RBAC Sama dengan Kewenangan Klinis

Role dokter tidak otomatis memberi izin melakukan semua prosedur. Clinical privilege harus menjadi policy terpisah.

## 12.2 Tidak Menyimpan Histori Organisasi

Struktur unit, pimpinan, privilege, tarif, dan klasifikasi perlu memiliki:

```text
effective_from
effective_until
status
approved_by
approval_reference
version
```

## 12.3 Menggunakan Hard Delete

Untuk rekam medis, hasil pemeriksaan, insiden, keputusan komite, tarif, pembayaran, dan pengaduan gunakan:

- void;
- cancel;
- supersede;
- amend;
- revoke;
- archive.

Setiap perubahan harus menyimpan alasan dan audit trail.

## 12.4 Membuat Aplikasi Terpisah untuk Setiap Komite

Pendekatan tersebut menghasilkan duplikasi. Lebih tepat menggunakan configurable governance engine.

## 12.5 Integrasi Langsung Tanpa Rekonsiliasi

Kewajiban pelaporan memerlukan:

- status pengiriman;
- acknowledgment;
- retry;
- dead-letter handling;
- error resolution;
- resubmission;
- reconciliation.

---

# 13. Compliance Matrix

| Area | Kebutuhan |
|---|---|
| Struktur dan klasifikasi rumah sakit | Wajib |
| SDM dan kompetensi | Wajib |
| Kredensial dan privilege | Wajib |
| Rekam medis | Wajib |
| Sistem rujukan | Wajib |
| Mutu dan keselamatan pasien | Wajib |
| Indikator mutu | Wajib |
| Insiden keselamatan pasien | Wajib |
| Manajemen risiko | Wajib |
| PPI dan resistensi antimikroba | Wajib secara fungsi tata kelola |
| Formularium dan penggunaan obat rasional | Wajib secara fungsi tata kelola |
| Audit klinis dan evaluasi profesi | Wajib |
| Komite dan keputusan | Wajib secara fungsi organisasi |
| Pengawasan internal | Wajib sesuai struktur dan fungsi rumah sakit |
| Keuangan dan unit cost | Wajib |
| Pencatatan dan pelaporan nasional | Wajib |
| Kerahasiaan | Wajib |
| Pengaduan dan investigasi | Wajib sebagai bukti kepatuhan |
| Rumah sakit pendidikan | Kondisional |
| Penelitian klinis | Kondisional |
| Rumah sakit bergerak atau lapangan | Kondisional |

---

# 14. Catatan Kepatuhan

Dokumen ini dapat digunakan sebagai:

- blueprint arsitektur SIMRS;
- backlog produk;
- dasar gap analysis;
- dasar perancangan database;
- dasar katalog permission;
- dasar penyusunan workflow;
- dasar audit kesiapan SIMRS.

Namun, dokumen ini bukan pendapat hukum dan belum menggantikan:

- telaah pasal per pasal;
- aturan rekam medis elektronik;
- aturan SATUSEHAT;
- aturan pelindungan data pribadi;
- standar akreditasi;
- aturan BPJS;
- pedoman teknis yang diterbitkan Kementerian Kesehatan.

Implementasi sebaiknya menggunakan konfigurasi, effective dating, versioning, dan policy engine agar perubahan regulasi tidak memerlukan desain ulang besar.

---

# 15. Sumber Utama

1. Kementerian Kesehatan Republik Indonesia, **Peraturan Menteri Kesehatan Nomor 6 Tahun 2026 tentang Rumah Sakit**, JDIH Kementerian Kesehatan.
2. Berita Negara Republik Indonesia Tahun 2026 Nomor 382.

Dokumen resmi:
https://jdih.kemkes.go.id/documents/peraturan-menteri-kesehatan-nomor-6-tahun-2026
