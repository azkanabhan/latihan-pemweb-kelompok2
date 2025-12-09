# Penjelasan Lengkap: Integrasi Tiket ke Google Calendar

Dokumen ini menjelaskan bagaimana sistem integrasi tiket/event ke Google Calendar bekerja di aplikasi ini.

## 📋 Status Implementasi

**Status Saat Ini:** ⚠️ **DISABLED / TIDAK AKTIF**

Kode integrasi Google Calendar sudah ada di codebase, namun **sedang di-comment (dinonaktifkan)** karena package mungkin belum dikonfigurasi dengan benar atau ada masalah dengan credentials.

---

## 🏗️ Arsitektur Integrasi

### **1. Package yang Digunakan**

**Library:** `spatie/laravel-google-calendar` (v3.8)

**Lokasi:** `composer.json` baris 15
```json
"spatie/laravel-google-calendar": "^3.8"
```

**Package ini menyediakan:**
- Wrapper untuk Google Calendar API
- Method untuk create, update, delete event
- Authentication dengan Service Account atau OAuth

---

### **2. Database Structure**

**Migration:** `database/migrations/2025_11_16_044844_add_google_calendar_event_id_to_events_table.php`

**Kolom yang Ditambahkan:**
```php
$table->string('google_calendar_event_id')->nullable()->after('rejected_at');
```

**Tujuan:**
- Menyimpan ID event dari Google Calendar
- Digunakan untuk tracking dan update event di Google Calendar
- `nullable` karena tidak semua event harus di-sync ke Google Calendar

**Contoh Data:**
```
events table:
- event_id: 1
- event_name: "Konser Musik Jazz"
- google_calendar_event_id: "abc123xyz456@google.com" (ID dari Google Calendar)
```

---

### **3. Konfigurasi**

**File:** `config/google-calendar.php`

**Konfigurasi yang Tersedia:**

#### **3.1. Authentication Profile**
```php
'default_auth_profile' => env('GOOGLE_CALENDAR_AUTH_PROFILE', 'service_account'),
```

**Dua metode autentikasi:**

**A. Service Account (Default)**
```php
'service_account' => [
    'credentials_json' => storage_path('app/google-calendar/latihan-pemweb-kelompok2-59773324faa5.json'),
],
```
- Menggunakan file JSON credentials dari Google Cloud Console
- File harus ada di: `storage/app/google-calendar/`
- Lebih mudah untuk aplikasi server-side

**B. OAuth**
```php
'oauth' => [
    'credentials_json' => storage_path('app/google-calendar/oauth-credentials.json'),
    'token_json' => storage_path('app/google-calendar/oauth-token.json'),
],
```
- Menggunakan OAuth 2.0 flow
- Perlu user consent
- Lebih kompleks tapi lebih fleksibel

#### **3.2. Calendar ID**
```php
'calendar_id' => env('GOOGLE_CALENDAR_ID'),
```
- ID calendar Google Calendar yang akan digunakan
- Bisa primary calendar atau calendar khusus
- Format: email address atau calendar ID

#### **3.3. User Impersonation**
```php
'user_to_impersonate' => env('GOOGLE_CALENDAR_IMPERSONATE'),
```
- Untuk service account, bisa impersonate user tertentu
- Berguna jika ingin event muncul di calendar user tertentu

---

## 🔄 Alur Kerja Integrasi (Berdasarkan Kode yang Di-comment)

### **Skenario 1: Admin Approve Event**

**Lokasi:** `app/Http/Controllers/Admin/EventController.php` - Method `approve()`

**Alur:**

```
1. Admin klik "Approve" event
   ↓
2. Event status diubah menjadi 'approved'
   ↓
3. Cek apakah event sudah punya google_calendar_event_id?
   ↓
   A. Jika SUDAH ADA (event sudah pernah di-sync):
      → Ambil event dari Google Calendar
      → Update data event di Google Calendar:
        - name = event_name
        - description = event_description
        - startDateTime = start_date
        - endDateTime = end_date
        - location = event_location
      → Save perubahan
   ↓
   B. Jika BELUM ADA (event baru):
      → Buat event baru di Google Calendar:
        GoogleCalendarEvent::create([
            'name' => event_name,
            'description' => event_description,
            'startDateTime' => start_date,
            'endDateTime' => end_date,
            'location' => event_location,
        ])
      → Simpan ID yang dikembalikan ke database:
        event.google_calendar_event_id = calendarEvent.id
      → Save event
   ↓
4. Jika error → Log error, tapi tidak gagalkan proses approve
```

**Kode yang Di-comment (Baris 107-131):**
```php
try {
    if ($event->google_calendar_event_id) {
        // Update existing event
        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
        $calendarEvent->name = $event->event_name;
        $calendarEvent->description = $event->event_description;
        $calendarEvent->startDateTime = $event->start_date;
        $calendarEvent->endDateTime = $event->end_date;
        $calendarEvent->location = $event->event_location;
        $calendarEvent->save();
    } else {
        // Create new event
        $calendarEvent = GoogleCalendarEvent::create([
            'name' => $event->event_name,
            'description' => $event->event_description,
            'startDateTime' => $event->start_date,
            'endDateTime' => $event->end_date,
            'location' => $event->event_location,
        ]);
        $event->google_calendar_event_id = $calendarEvent->id;
        $event->save();
    }
} catch (\Exception $e) {
    Log::error('Gagal sinkronisasi Google Calendar saat approve: ' . $e->getMessage());
}
```

---

### **Skenario 2: Admin Update Event**

**Lokasi:** `app/Http/Controllers/Admin/EventController.php` - Method `update()`

**Alur:**

```
1. Admin update data event
   ↓
2. Event di-update di database
   ↓
3. Cek:
   - Apakah status = 'approved'? ✅
   - Apakah ada google_calendar_event_id? ✅
   ↓
4. Jika kedua kondisi terpenuhi:
   → Ambil event dari Google Calendar
   → Update semua field:
     - name
     - description
     - startDateTime
     - endDateTime
     - location
   → Save perubahan
   ↓
5. Jika error → Log error, tapi tidak gagalkan proses update
```

**Kode yang Di-comment (Baris 57-74):**
```php
if ($event->status === 'approved' && $event->google_calendar_event_id) {
    try {
        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
        if ($calendarEvent) {
            $calendarEvent->name = $event->event_name;
            $calendarEvent->description = $event->event_description;
            $calendarEvent->startDateTime = $event->start_date;
            $calendarEvent->endDateTime = $event->end_date;
            $calendarEvent->location = $event->event_location;
            $calendarEvent->save();
        }
    } catch (\Exception $e) {
        Log::error('Gagal update event GCal saat admin update: ' . $e->getMessage());
    }
}
```

---

### **Skenario 3: Admin Reject Event**

**Lokasi:** `app/Http/Controllers/Admin/EventController.php` - Method `reject()`

**Alur:**

```
1. Admin klik "Reject" event
   ↓
2. Event status diubah menjadi 'rejected'
   ↓
3. Cek apakah ada google_calendar_event_id?
   ↓
4. Jika ADA:
   → Ambil event dari Google Calendar
   → Delete event dari Google Calendar
   → Set google_calendar_event_id = null di database
   → Save
   ↓
5. Jika error → Log error, tapi tidak gagalkan proses reject
```

**Kode yang Di-comment (Baris 150-164):**
```php
if ($event->google_calendar_event_id) {
    try {
        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
        if ($calendarEvent) {
            $calendarEvent->delete();
        }
        $event->google_calendar_event_id = null;
        $event->save();
    } catch (\Exception $e) {
        Log::error('Gagal menghapus event GCal saat reject: ' . $e->getMessage());
    }
}
```

---

### **Skenario 4: Admin Delete Event**

**Lokasi:** `app/Http/Controllers/Admin/EventController.php` - Method `destroy()`

**Alur:**

```
1. Admin delete event
   ↓
2. Cek apakah ada google_calendar_event_id?
   ↓
3. Jika ADA:
   → Ambil event dari Google Calendar
   → Delete event dari Google Calendar
   ↓
4. Delete event dari database
```

**Kode yang Di-comment (Baris 81-93):**
```php
if ($event->google_calendar_event_id) {
    try {
        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
        if ($calendarEvent) {
            $calendarEvent->delete();
        }
    } catch (\Exception $e) {
        Log::error('Gagal menghapus event GCal saat destroy: ' . $e->getMessage());
    }
}
```

---

## 🔑 Poin Penting Integrasi

### **1. Kapan Event Di-sync ke Google Calendar?**

✅ **Hanya saat event di-APPROVE oleh admin**
- Event dengan status `requested` → **TIDAK** di-sync
- Event dengan status `approved` → **DI-SYNC** (create/update)
- Event dengan status `rejected` → **DI-DELETE** dari Google Calendar (jika sudah ada)

### **2. Data yang Di-sync**

**Field yang di-sync ke Google Calendar:**
- `name` → `event_name`
- `description` → `event_description`
- `startDateTime` → `start_date`
- `endDateTime` → `end_date`
- `location` → `event_location`

**Field yang TIDAK di-sync:**
- `event_capacity`
- `status`
- `tickets`
- `photos`
- Data lainnya

### **3. Tracking dengan google_calendar_event_id**

**Fungsi kolom `google_calendar_event_id`:**
- Menyimpan ID event dari Google Calendar
- Digunakan untuk update/delete event yang sudah ada
- Mencegah duplikasi event saat update
- `null` = event belum di-sync atau sudah dihapus dari Google Calendar

### **4. Error Handling**

**Strategi:**
- Semua operasi Google Calendar dibungkus dalam `try-catch`
- Jika error → Log error ke Laravel log
- **TIDAK menggagalkan** proses utama (approve/update/reject/delete)
- Aplikasi tetap berjalan meski Google Calendar sync gagal

---

## 📊 Flowchart Integrasi

```
Event Created (Status: 'requested')
    ↓
Admin Approve Event
    ↓
Status = 'approved'
    ↓
Cek: google_calendar_event_id ada?
    ↓
    ├─ TIDAK ADA → Create Event di Google Calendar
    │                ↓
    │            Simpan ID → google_calendar_event_id
    │                ↓
    │            ✅ Event muncul di Google Calendar
    │
    └─ SUDAH ADA → Update Event di Google Calendar
                     ↓
                 ✅ Event di-update di Google Calendar
```

```
Admin Update Event (Status: 'approved')
    ↓
Update di Database
    ↓
Cek: google_calendar_event_id ada?
    ↓
    └─ ADA → Update Event di Google Calendar
              ↓
          ✅ Data ter-sync
```

```
Admin Reject/Delete Event
    ↓
Cek: google_calendar_event_id ada?
    ↓
    └─ ADA → Delete Event dari Google Calendar
              ↓
          Set google_calendar_event_id = null
              ↓
          ✅ Event dihapus dari Google Calendar
```

---

## 🛠️ Teknologi yang Digunakan

### **Client-Side:**
- Tidak ada (semua proses di server)

### **Server-Side:**
1. **spatie/laravel-google-calendar** - Package Laravel untuk Google Calendar API
2. **Google Calendar API** - REST API dari Google
3. **Service Account / OAuth** - Authentication method
4. **Laravel Eloquent** - Database operations

---

## ⚙️ Konfigurasi yang Diperlukan

### **1. File Credentials**

**Service Account:**
- File JSON dari Google Cloud Console
- Lokasi: `storage/app/google-calendar/latihan-pemweb-kelompok2-59773324faa5.json`
- Berisi: private key, client email, project ID

**OAuth (jika digunakan):**
- `oauth-credentials.json` - Client credentials
- `oauth-token.json` - Access token (auto-generated)

### **2. Environment Variables**

**File `.env` harus berisi:**
```env
GOOGLE_CALENDAR_AUTH_PROFILE=service_account
GOOGLE_CALENDAR_ID=your-calendar-id@group.calendar.google.com
GOOGLE_CALENDAR_IMPERSONATE=user@example.com (optional)
```

### **3. Google Cloud Console Setup**

**Yang perlu disiapkan:**
1. Project di Google Cloud Console
2. Enable Google Calendar API
3. Create Service Account atau OAuth Client
4. Download credentials JSON
5. Share calendar dengan service account email (jika service account)

---

## 🔍 Contoh Data Flow

### **Contoh 1: Event Baru Di-approve**

```
1. Event di database:
   - event_id: 1
   - event_name: "Konser Musik Jazz"
   - status: 'requested'
   - google_calendar_event_id: null

2. Admin approve:
   - status: 'approved'
   - google_calendar_event_id: null (belum ada)

3. System create di Google Calendar:
   - GoogleCalendarEvent::create([...])
   - Google Calendar return ID: "abc123xyz456@google.com"

4. System simpan ID:
   - google_calendar_event_id: "abc123xyz456@google.com"

5. Hasil:
   - Event muncul di Google Calendar
   - Database punya reference ID
```

### **Contoh 2: Event Di-update**

```
1. Event di database:
   - event_id: 1
   - event_name: "Konser Musik Jazz"
   - status: 'approved'
   - google_calendar_event_id: "abc123xyz456@google.com"

2. Admin update:
   - event_name: "Konser Musik Jazz 2024" (diubah)

3. System update di Google Calendar:
   - GoogleCalendarEvent::find("abc123xyz456@google.com")
   - calendarEvent->name = "Konser Musik Jazz 2024"
   - calendarEvent->save()

4. Hasil:
   - Event di Google Calendar ter-update
   - Database tetap sama (hanya nama yang berubah)
```

### **Contoh 3: Event Di-reject**

```
1. Event di database:
   - event_id: 1
   - status: 'approved'
   - google_calendar_event_id: "abc123xyz456@google.com"

2. Admin reject:
   - status: 'rejected'

3. System delete dari Google Calendar:
   - GoogleCalendarEvent::find("abc123xyz456@google.com")
   - calendarEvent->delete()

4. System update database:
   - google_calendar_event_id: null

5. Hasil:
   - Event dihapus dari Google Calendar
   - Database: status = 'rejected', google_calendar_event_id = null
```

---

## ⚠️ Catatan Penting

### **1. Status Saat Ini: DISABLED**

Kode integrasi **sedang di-comment** karena:
- Package mungkin belum dikonfigurasi dengan benar
- Credentials mungkin belum disetup
- Atau ada masalah teknis lainnya

**Untuk mengaktifkan:**
1. Uncomment kode di `Admin/EventController.php`
2. Pastikan credentials file ada
3. Pastikan environment variables sudah di-set
4. Test dengan event yang di-approve

### **2. Hanya Event Approved yang Di-sync**

- Event `requested` → Tidak di-sync
- Event `approved` → Di-sync (create/update)
- Event `rejected` → Di-delete dari Google Calendar

### **3. Error Handling**

- Error tidak menggagalkan proses utama
- Error di-log untuk debugging
- Aplikasi tetap berjalan meski Google Calendar sync gagal

### **4. One-Way Sync**

**Arah Sync:** Database → Google Calendar (satu arah)
- Update di database → Update di Google Calendar ✅
- Update di Google Calendar → **TIDAK** update di database ❌

### **5. Tidak Ada Integrasi dengan Tiket**

**Penting:** Integrasi ini hanya untuk **Event**, bukan untuk **Tiket individual**.

- Setiap event (yang approved) → 1 event di Google Calendar
- Bukan setiap tiket → 1 event di Google Calendar
- Tiket tidak memiliki integrasi langsung ke Google Calendar

---

## 📝 Kesimpulan

Integrasi Google Calendar di aplikasi ini dirancang untuk:
1. ✅ Sync event yang di-approve ke Google Calendar
2. ✅ Update event di Google Calendar saat data event diubah
3. ✅ Hapus event dari Google Calendar saat di-reject atau di-delete
4. ✅ Tracking dengan `google_calendar_event_id`

**Status:** Kode sudah ada tapi **sedang dinonaktifkan**. Untuk mengaktifkan, perlu:
- Uncomment kode
- Setup credentials
- Test integrasi

