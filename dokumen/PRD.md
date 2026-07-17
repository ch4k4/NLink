## PRD Formal

### 1. Scope Utama

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Product | Platform mendukung layanan on-demand perawatan luka diabetes berbasis episode dan homecare visit. | Sistem memiliki alur inti dari intake sampai outcome dan episode menjadi container utama. [^1] | P0 |
| Roles | Sistem mendukung portal pasien, coordinator, nurse, supervisor. | Setiap role hanya melihat fitur sesuai hak aksesnya. [^1] | P0 |
| Workflow | Sistem memakai lifecycle: intake, screening, registration, episode, assessment, treatment, visit, procedure, outcome. | Status episode dapat berpindah sesuai alur yang didefinisikan dan tercatat di audit log. [^1] | P0 |

### 2. Intake dan Screening

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Intake Request | Pasien atau referral dapat membuat request layanan. | Request tersimpan dengan data dasar pasien, kontak, alamat, keluhan luka, referral source, consent, dan foto awal. [^1] | P0 |
| Clinical Screening | Coordinator melakukan screening kelayakan dan risiko awal. | Sistem menampilkan risk badge, screening DM, eligibility notes, dan keputusan eligible/reject/schedule. [^1] | P0 |
| Lead Queue | Lead masuk ke antrean screening. | Lead dapat difilter berdasarkan prioritas dan status risiko. [^1] | P1 |

### 3. Patient dan Episode

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Patient Registration | Sistem menyimpan data MRN, NIK, demografi, kontak, DM profile, payer, emergency contact. | Data pasien dapat dibuat, diperbarui, dan divalidasi minimal pada field wajib. [^1] | P0 |
| Episode Creation | Setiap pasien bisa punya banyak woundcare episode. | Episode baru dapat dibuat dan terhubung ke pasien yang sudah terdaftar. [^1] | P0 |
| Episode Dashboard | Dashboard menampilkan status episode, visit count, progress, days open, risk, dan cost. | Ringkasan episode tampil dalam satu layar dan timeline event bisa dibaca. [^1] | P1 |

### 4. Assessment dan Care Plan

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Initial Assessment | Perawat menginput vitals, DM control, neuropathy/vascular assessment, risk flags. | Assessment dapat disimpan sebagai draft dan final, serta tersimpan per episode. [^1] | P0 |
| Wound Assessment | Sistem mendukung wound location, etiology, measurement, pain, tissue, exudate, infection, periwound, dan multi-photo upload. | Data assessment luka tersimpan lengkap dan dapat dipanggil kembali per versi. [^1] | P0 |
| Versioning | Assessment harus versioned: draft, final, amended. | Perubahan assessment tidak menimpa data final sebelumnya, tetapi menambah versi baru. [^1] | P0 |
| Treatment Plan | Sistem mendukung goals, dressing plan, infection management, offloading, patient education, follow-up plan, dan escalation criteria. | Care plan bisa dibuat, direview, dan diubah statusnya sesuai review cycle. [^1] | P0 |

### 5. Visit dan Dokumentasi

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Assignment | Coordinator dapat meng-assign nurse berdasarkan kompetensi, STR/SIP aktif, availability, wilayah, workload, dan blacklist. | Assignment tersimpan dengan nurse, waktu, dan alasan penugasan. [^1] | P0 |
| Homecare Visit | Visit memiliki status seperti scheduled, assigned, en_route, arrived, in_progress, completed. | Status visit dapat diubah oleh role yang berwenang dan tercatat dalam log. [^1] | P0 |
| EVV Dasar | Nurse dapat clock-in dan clock-out visit. | Sistem menyimpan timestamp dan status lokasi/waktu visit. [^1] | P0 |
| Procedure Documentation | Nurse menginput tindakan, preparation checklist, consumables, pain before/after, signature, dan foto before/after. | Visit tidak bisa ditutup tanpa dokumentasi minimum yang wajib. [^1] | P0 |

### 6. Outcome dan Follow-up

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Outcome Recording | Sistem mencatat healing trend, goal achievement, adherence, escalation, next visit planning. | Outcome tersimpan per episode dan bisa dipakai untuk keputusan status episode. [^1] | P0 |
| Episode Closure | Episode dapat ditandai active, healing, resolved, closed, referred out. | Status closure tercatat dan tidak bisa diubah tanpa alur amendment yang sah. [^1] | P0 |
| Timeline | Semua visit, assessment, procedure, outcome tampil dalam timeline. | Timeline episode dapat ditelusuri dari awal sampai akhir. [^1] | P1 |

### 7. Audit dan Compliance

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Audit Trail | Semua perubahan status, assignment, note, upload foto, dan approval tercatat. | Audit log immutable tersedia per episode dan per visit. [^1] | P0 |
| Amendment Flow | Data klinis yang salah harus bisa diamend, bukan overwrite. | Sistem menyediakan status amendment_requested dan amended. [^1] | P0 |
| File Evidence | Foto dan file evidence disimpan via file service, bukan direct public path. | File evidence dapat diakses sesuai hak akses dan terhubung ke episode/visit. [^1] | P0 |

### 8. Modul Non-MVP

| Area | Requirement | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| Inventory | Item master, stock reservation, deduction, lot/expiry tracking. | Bisa ditambahkan sebagai modul terpisah setelah workflow klinis stabil. [^1] | P2 |
| Finance | Tariff package, invoice draft, billing status, payer flow. | Billing dapat dimulai dari completed visit setelah modul klinis matang. [^1] | P2 |
| Payroll | Payroll claim, approval, paid. | Payroll berjalan setelah visit verified, documentation complete, dan billing policy terpenuhi. [^1] | P2 |
| Quality | Indicators, review queue, adverse event tracking. | Quality review tersedia untuk kasus high-risk atau outlier. [^1] | P2 |

## Prioritization Rule

- **P0** = harus ada di MVP.
- **P1** = penting, tetapi bisa masuk iterasi setelah MVP inti.
- **P2** = fase berikutnya setelah clinical workflow stabil.


## Catatan Implementasi

Dokumen Anda secara jelas menempatkan episode sebagai inti dan visit sebagai operasi lapangan, jadi prioritas utama memang harus ada di workflow klinis dan audit, bukan langsung ke payroll atau finance kompleks [^1]. [High confidence] Ini konsisten dengan lifecycle, status besar, dan data object yang sudah Anda definisikan [^1].


**PRD dengan kolom owner, dependency, dan estimate**. 

## PRD with Owner, Dependency, Estimate

| Area | Requirement | Acceptance Criteria | Priority | Owner | Dependency | Estimate |
| :-- | :-- | :-- | :-- | :-- | :-- | :-- |
| Product | Platform on-demand woundcare berbasis episode dan visit. | Alur inti dari intake sampai outcome tersedia. [^1] | P0 | Product + Backend + UX | Scope final, workflow final | 1 sprint |
| Auth | Login, logout, RBAC untuk pasien, coordinator, nurse, supervisor. | User hanya bisa akses fitur sesuai role. [^1] | P0 | Backend | User model, role matrix | 1 sprint |
| Intake | Pasien/referral membuat request layanan. | Request tersimpan dengan data dasar, keluhan, alamat, consent, foto awal. [^1] | P0 | Frontend Web + Backend | Auth, patient model, file upload | 1 sprint |
| Screening | Coordinator melakukan screening dan triage risiko. | Lead punya risk badge dan status eligible/reject/schedule. [^1] | P0 | Frontend Web + Backend | Intake, role coordinator | 1 sprint |
| Registration | Registrasi pasien dan data klinis dasar. | MRN, NIK, demografi, DM profile, payer, emergency contact tersimpan. [^1] | P0 | Frontend Web + Backend | Intake, screening | 1 sprint |
| Episode | Episode dibuat untuk satu pasien dan bisa banyak wound site. | Episode dashboard menampilkan status, timeline, visit count. [^1] | P0 | Backend + Frontend Web | Patient registration | 1–2 sprint |
| Assessment | Initial assessment dan wound assessment. | Data assessment tersimpan lengkap dan versioned. [^1] | P0 | Mobile + Backend | Episode, file service | 2 sprint |
| Care Plan | Treatment plan, review cycle, escalation criteria. | Care plan bisa dibuat, direview, revisi, dan disimpan. [^1] | P0 | Frontend Web + Backend | Assessment | 1 sprint |
| Assignment | Coordinator assign nurse berdasarkan kompetensi dan availability. | Assignment log tersimpan dan bisa ditinjau supervisor. [^1] | P0 | Frontend Web + Backend | Nurse master, credential data | 1 sprint |
| Visit | Homecare visit lifecycle dan EVV dasar. | Status visit berubah benar, ada clock-in dan clock-out. [^1] | P0 | Mobile + Backend | Assignment, schedule | 1 sprint |
| Procedure | Dokumentasi tindakan di lapangan. | Checklist, tindakan, consumables, foto before/after, signature tersimpan. [^1] | P0 | Mobile + Backend | Visit, file service | 1–2 sprint |
| Outcome | Outcome dan follow-up episode. | Healing trend, next visit, escalation, close episode dapat dicatat. [^1] | P0 | Frontend Web + Backend | Procedure, assessment versioning | 1 sprint |
| Audit | Immutable audit trail untuk semua event. | Setiap status transition dan perubahan data tercatat. [^1] | P0 | Backend | Semua modul inti | 1 sprint |
| File Evidence | Upload dan akses foto/evidence via file service. | File terhubung ke episode/visit dan tidak public direct path. [^1] | P0 | Backend | Auth, storage service | 1 sprint |
| Timeline | Timeline episode dari intake sampai closed. | Timeline dapat dibaca untuk review klinis dan operasional. [^1] | P1 | Frontend Web | Episode, audit log | 1 sprint |
| Inventory | Item master, reservation, deduction, lot/expiry. | Item dan movement inventory tercatat benar. [^1] | P2 | Backend | Procedure consumables | 1–2 sprint |
| Finance | Tariff, invoice draft, payment flow. | Billing ready setelah visit complete. [^1] | P2 | Backend + Frontend Web | Procedure complete, inventory linkage | 1–2 sprint |
| Payroll | Payroll rules dan claim. | Claim hanya muncul setelah visit verified dan docs complete. [^1] | P2 | Backend | Visit verified, finance policy | 1 sprint |
| Quality | Indicators, review queue, adverse event. | Quality queue bisa dipakai untuk kasus high-risk/outlier. [^1] | P2 | Backend + Frontend Web | Outcome, audit, clinical events | 1 sprint |

## Estimasi Delivery

Untuk MVP yang sehat, urutan implementasi yang paling masuk akal adalah:

1. Auth, intake, screening, registration.
2. Episode, assessment, care plan.
3. Assignment, visit mobile, procedure documentation.
4. Outcome, audit, timeline.
5. Baru inventory, finance, payroll, quality. [^1]

### Catatan Estimasi

- **P0**: wajib ada di MVP.
- **P1**: disarankan masuk jika kapasitas tim cukup.
- **P2**: fase setelah workflow klinis stabil.

[High confidence] Urutan ini mengikuti dependensi yang tersurat di dokumen: episode sebagai container inti, visit sebagai operasi lapangan, lalu inventory/finance/payroll sebagai modul turunan [^1].

Berikut backlog user story yang bisa langsung dipakai sebagai dasar delivery.

## Epic 1: Auth dan Role

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-01 | Sebagai user, saya ingin login agar bisa mengakses sistem sesuai role saya. | User berhasil login dan diarahkan ke portal sesuai role. [^1] | P0 |
| US-02 | Sebagai admin, saya ingin mengelola role user agar akses fitur terkontrol. | Role dapat dibuat, diubah, dan dibatasi sesuai matrix akses. [^1] | P0 |
| US-03 | Sebagai sistem, saya ingin membatasi fitur berdasarkan role agar data klinis aman. | User hanya melihat menu yang sesuai hak aksesnya. [^1] | P0 |

## Epic 2: Intake dan Screening

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-04 | Sebagai pasien, saya ingin mengirim request layanan agar bisa mendapatkan perawatan rumah. | Request tersimpan dengan data dasar, keluhan luka, alamat, referral, consent, foto awal. [^1] | P0 |
| US-05 | Sebagai coordinator, saya ingin melihat lead intake agar bisa menilai kelayakan kasus. | Lead muncul di queue screening dengan status dan risk badge. [^1] | P0 |
| US-06 | Sebagai coordinator, saya ingin melakukan screening agar bisa menentukan eligible atau reject. | Hasil screening tersimpan beserta notes dan keputusan akhir. [^1] | P0 |

## Epic 3: Patient dan Episode

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-07 | Sebagai coordinator, saya ingin mendaftarkan pasien agar data pasien resmi tersimpan. | MRN, NIK, demografi, kontak, DM profile, payer, dan emergency contact tersimpan. [^1] | P0 |
| US-08 | Sebagai sistem, saya ingin membuat episode agar setiap kasus luka punya container klinis sendiri. | Episode dapat dibuat untuk pasien yang sudah terdaftar. [^1] | P0 |
| US-09 | Sebagai coordinator, saya ingin melihat dashboard episode agar status dan progres mudah dipantau. | Dashboard menampilkan visit count, progress, risk, days open, cost, dan timeline. [^1] | P1 |

## Epic 4: Assessment dan Care Plan

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-10 | Sebagai nurse, saya ingin mengisi initial assessment agar kondisi pasien tercatat. | Vitals, DM control, neuropathy/vascular check, dan risk flags tersimpan. [^1] | P0 |
| US-11 | Sebagai nurse, saya ingin mengisi wound assessment agar luka terdokumentasi lengkap. | Location, etiology, ukuran, tissue, exudate, pain, infection, dan foto tersimpan. [^1] | P0 |
| US-12 | Sebagai sistem, saya ingin menyimpan versi assessment agar koreksi tidak menimpa data lama. | Draft, final, dan amended memiliki versi yang berbeda. [^1] | P0 |
| US-13 | Sebagai nurse/supervisor, saya ingin menyusun treatment plan agar tindakan terarah. | Care plan berisi goals, dressing, infection management, offloading, education, dan follow-up. [^1] | P0 |

## Epic 5: Assignment dan Visit

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-14 | Sebagai coordinator, saya ingin meng-assign nurse agar visit ditangani orang yang tepat. | Assignment berdasarkan kompetensi, STR/SIP, availability, wilayah, workload, dan blacklist. [^1] | P0 |
| US-15 | Sebagai nurse, saya ingin melihat visit saya agar saya tahu tugas lapangan. | Visit tampil dengan status, jadwal, alamat, dan detail kasus. [^1] | P0 |
| US-16 | Sebagai nurse, saya ingin clock-in dan clock-out visit agar bukti EVV tercatat. | Timestamp dan status lokasi/waktu tersimpan dengan benar. [^1] | P0 |

## Epic 6: Procedure Documentation

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-17 | Sebagai nurse, saya ingin mengisi dokumentasi prosedur agar tindakan lapangan lengkap. | Checklist, tindakan, consumables, pain before/after, dan signature tersimpan. [^1] | P0 |
| US-18 | Sebagai nurse, saya ingin upload foto before/after agar bukti klinis terdokumentasi. | Foto terhubung ke visit dan episode. [^1] | P0 |
| US-19 | Sebagai sistem, saya ingin menutup visit hanya jika dokumentasi minimum lengkap. | Visit tidak bisa completed sebelum field wajib terpenuhi. [^1] | P0 |

## Epic 7: Outcome dan Closure

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-20 | Sebagai nurse/supervisor, saya ingin mencatat outcome agar progres luka dapat dipantau. | Healing trend, adherence, escalation, next visit, dan outcome note tersimpan. [^1] | P0 |
| US-21 | Sebagai coordinator, saya ingin menutup episode agar status layanan final jelas. | Episode bisa ditandai active, healing, resolved, closed, atau referred out. [^1] | P0 |
| US-22 | Sebagai sistem, saya ingin membuat timeline episode agar semua aktivitas mudah ditelusuri. | Timeline menampilkan intake, screening, assessment, visit, procedure, outcome. [^1] | P1 |

## Epic 8: Audit dan Evidence

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-23 | Sebagai sistem, saya ingin mencatat audit trail agar semua perubahan terlacak. | Setiap status transition dan perubahan data tersimpan di log immutable. [^1] | P0 |
| US-24 | Sebagai sistem, saya ingin mendukung amendment agar koreksi tidak overwrite. | Perubahan data lama tetap bisa ditelusuri. [^1] | P0 |
| US-25 | Sebagai sistem, saya ingin menyimpan evidence via file service agar akses aman. | File tidak public direct path dan terkait ke visit/episode. [^1] | P0 |

## Epic 9: Modul Fase Berikutnya

| Story ID | User Story | Acceptance Criteria | Priority |
| :-- | :-- | :-- | :-- |
| US-26 | Sebagai tim inventory, saya ingin mencatat consumables agar pemakaian barang terkontrol. | Stock movement, lot, expiry, dan reservation tercatat. [^1] | P2 |
| US-27 | Sebagai tim finance, saya ingin membuat billing draft agar visit bisa ditagihkan. | Invoice draft muncul setelah visit complete. [^1] | P2 |
| US-28 | Sebagai tim payroll, saya ingin membuat payroll claim agar kompensasi perawat bisa diproses. | Claim hanya muncul setelah visit verified dan docs complete. [^1] | P2 |
| US-29 | Sebagai tim quality, saya ingin melihat indikator mutu agar kasus berisiko bisa ditinjau. | Queue quality tersedia untuk high-risk atau outlier. [^1] | P2 |

## Urutan Sprint yang Disarankan

1. Auth dan role.
2. Intake dan screening.
3. Patient registration dan episode.
4. Assessment dan care plan.
5. Assignment dan visit.
6. Procedure documentation.
7. Outcome, audit, dan evidence.
8. Baru masuk inventory, finance, payroll, quality. [^1]

