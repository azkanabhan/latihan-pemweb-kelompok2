# Penjelasan Lengkap: Proses Scanning QR Code dari PDF

Dokumen ini menjelaskan secara detail bagaimana sistem scanning QR code dari PDF bekerja, dari awal hingga validasi akhir.

## 📋 Alur Proses Lengkap

### **Tahap 1: Upload dan Validasi File PDF (Client-Side)**

```
User Upload PDF
    ↓
Validasi File:
  - Cek ukuran file (maks. 5MB)
  - Cek format file (harus PDF)
  - Stop kamera jika sedang aktif
    ↓
File diterima → Lanjut ke Tahap 2
```

**Lokasi Kode:**
- File: `resources/views/creator/qrcode-scanner.blade.php`
- Function: `handlePdfFile(file)`
- Validasi: Baris 530-535

---

### **Tahap 2: Membaca dan Parse PDF (Client-Side)**

```
PDF File (ArrayBuffer)
    ↓
PDF.js Library:
  - pdfjsLib.getDocument({ data: arrayBuffer })
  - Parse struktur PDF
  - Baca metadata (jumlah halaman, dll)
    ↓
PDF Object dengan informasi:
  - numPages: Jumlah halaman
  - getPage(pageNum): Method untuk ambil halaman
```

**Teknologi:**
- **Library:** PDF.js v3.11.174 (Mozilla)
- **Worker:** PDF.js Worker untuk proses background
- **Lokasi:** Baris 461-463

**Contoh Kode:**
```javascript
const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
const pdf = await loadingTask.promise;
const numPages = pdf.numPages; // Jumlah halaman PDF
```

---

### **Tahap 3: Render Setiap Halaman ke Canvas (Client-Side)**

```
Untuk setiap halaman (1 sampai numPages):
    ↓
1. Ambil halaman:
   page = await pdf.getPage(pageNum)
    ↓
2. Buat viewport dengan scale 2.0:
   viewport = page.getViewport({ scale: 2.0 })
   (Scale 2.0 = resolusi tinggi untuk scan QR yang lebih akurat)
    ↓
3. Buat canvas HTML5:
   canvas = document.createElement('canvas')
   canvas.width = viewport.width
   canvas.height = viewport.height
    ↓
4. Render halaman PDF ke canvas:
   await page.render({
       canvasContext: context,
       viewport: viewport
   }).promise
    ↓
Canvas berisi gambar halaman PDF (sebagai image data)
```

**Lokasi:** Baris 468-479

**Mengapa Scale 2.0?**
- QR code yang lebih besar = lebih mudah di-scan
- Resolusi tinggi = detail QR code lebih jelas
- Meningkatkan akurasi scanning

---

### **Tahap 4: Extract Image Data dari Canvas (Client-Side)**

```
Canvas (berisi gambar halaman PDF)
    ↓
Get ImageData:
  context.getImageData(0, 0, canvas.width, canvas.height)
    ↓
ImageData Object:
  - data: Array pixel data (RGBA)
  - width: Lebar gambar
  - height: Tinggi gambar
```

**Lokasi:** Function `scanQrCodeFromCanvas(canvas)` - Baris 600+

---

### **Tahap 5: Scan QR Code dari Image Data (Client-Side)**

```
ImageData dari Canvas
    ↓
jsQR Library:
  jsQR(imageData.data, imageData.width, imageData.height, {
      inversionAttempts: 'dontInvert'
  })
    ↓
Hasil Scan:
  - Jika QR code ditemukan:
    → code.data = String QR code (contoh: "QR-12345-67890")
    → code.location = Koordinat QR code di gambar
  - Jika tidak ditemukan:
    → null
```

**Teknologi:**
- **Library:** jsQR v1.4.0
- **Method:** `jsQR(pixelData, width, height, options)`
- **Lokasi:** Function `scanQrCodeFromCanvas()` - Baris 600+

**Proses Scanning:**
1. jsQR menganalisis pixel data
2. Mencari pola QR code (finder patterns, alignment patterns)
3. Decode data QR code menjadi string
4. Return hasil atau null jika tidak ditemukan

---

### **Tahap 6: Loop Semua Halaman (Client-Side)**

```
Halaman 1 → Scan QR → Tidak ditemukan
Halaman 2 → Scan QR → Tidak ditemukan
Halaman 3 → Scan QR → DITEMUKAN! → QR Code: "QR-12345-67890"
    ↓
Stop loop, lanjut ke Tahap 7
```

**Lokasi:** Baris 468-500

**Optimasi:**
- Loop berhenti saat QR code ditemukan
- Tidak perlu scan halaman berikutnya
- Menghemat waktu proses

---

### **Tahap 7: Auto-Submit Form (Client-Side)**

```
QR Code ditemukan: "QR-12345-67890"
    ↓
Set nilai input hidden:
  qrCodeInput.value = "QR-12345-67890"
    ↓
Submit form ke server:
  qrCodeForm.submit()
    ↓
POST request ke: /creator/qrcode/scan
  Data: { qr_code: "QR-12345-67890", _token: "..." }
```

**Lokasi:** Baris 490-496

---

### **Tahap 8: Validasi di Server (Server-Side)**

#### **8.1. Validasi Input**

```php
$request->validate([
    'qr_code' => 'required|string|max:255',
]);

$qrCode = trim($request->input('qr_code'));
// Contoh: "QR-12345-67890"
```

**Lokasi:** `app/Http/Controllers/Creator/QRCodeController.php` - Baris 26-30

---

#### **8.2. Cari Ticket Holder di Database**

```php
$ticketHolder = TicketHolder::with('event.creator')
    ->where('qr_code', $qrCode)
    ->first();
```

**Query SQL (contoh):**
```sql
SELECT * FROM ticket_holders 
WHERE qr_code = 'QR-12345-67890'
LIMIT 1
```

**Hasil:**
- **Jika ditemukan:** Object TicketHolder dengan relasi event dan creator
- **Jika tidak ditemukan:** `null` → Return error "QR Code tidak ditemukan"

**Lokasi:** Baris 33-41

---

#### **8.3. Verifikasi Creator yang Login**

```php
$userId = Auth::id(); // ID user yang sedang login
$creator = EventCreator::getByUserId($userId);
```

**Query:**
```sql
SELECT * FROM event_creators 
WHERE user_id = [current_user_id]
LIMIT 1
```

**Validasi:**
- Cek apakah user adalah creator yang terdaftar
- Jika bukan → Return error "Anda bukan creator yang terdaftar"

**Lokasi:** Baris 44-51

---

#### **8.4. Verifikasi Event Milik Creator**

```php
if ($ticketHolder->event->events_creators_id !== $creator->id) {
    return error: "QR Code ini bukan milik event Anda."
}
```

**Logika:**
```
TicketHolder → event → events_creators_id
                    ↓
            Bandingkan dengan creator->id
                    ↓
        Jika SAMA → Valid (event milik creator)
        Jika BEDA → Invalid (event bukan milik creator)
```

**Contoh:**
- Creator ID yang login: `5`
- Event creator ID dari ticket: `5` → ✅ **VALID**
- Event creator ID dari ticket: `7` → ❌ **INVALID** (bukan event creator ini)

**Lokasi:** Baris 53-57

---

#### **8.5. Cek Status Tiket (used/active/canceled)**

```php
// Cek 1: Apakah sudah digunakan?
if ($ticketHolder->status === 'used') {
    return warning: "Tiket ini sudah digunakan sebelumnya."
}

// Cek 2: Apakah dibatalkan?
if ($ticketHolder->status === 'canceled') {
    return error: "Tiket ini telah dibatalkan."
}

// Jika status = 'active' → LANJUT ke update
```

**Status Tiket:**
- **`active`** → ✅ Bisa divalidasi (belum digunakan)
- **`used`** → ⚠️ Sudah digunakan (tidak bisa divalidasi lagi)
- **`canceled`** → ❌ Dibatalkan (tidak bisa divalidasi)

**Lokasi:** Baris 59-71

---

#### **8.6. Update Status Tiket ke 'used'**

```php
$ticketHolder->status = 'used';
$ticketHolder->save();
```

**Query SQL:**
```sql
UPDATE ticket_holders 
SET status = 'used', updated_at = NOW()
WHERE ticket_holder_id = [ticket_holder_id]
```

**Lokasi:** Baris 73-75

---

#### **8.7. Return Success Response**

```php
return redirect()
    ->route('creator.qrcode.index')
    ->with('success', 'Tiket berhasil divalidasi');
```

**Lokasi:** Baris 77-79

---

## 🔍 Ringkasan Validasi Lengkap

### **Validasi yang Dilakukan:**

1. ✅ **QR Code Format Valid**
   - String tidak kosong
   - Maksimal 255 karakter

2. ✅ **QR Code Ada di Database**
   - Cek di tabel `ticket_holders`
   - QR code harus exist

3. ✅ **User adalah Creator**
   - User yang login harus punya record di `event_creators`

4. ✅ **Event Milik Creator**
   - `ticket_holder.event.events_creators_id` === `creator.id`
   - Hanya creator pemilik event yang bisa validasi

5. ✅ **Status Tiket Valid**
   - Status harus `active` (belum digunakan)
   - Bukan `used` (sudah digunakan)
   - Bukan `canceled` (dibatalkan)

### **Flowchart Validasi:**

```
QR Code dari PDF
    ↓
[1] Validasi Format? → ❌ → Error: "Format tidak valid"
    ↓ ✅
[2] Ada di Database? → ❌ → Error: "QR Code tidak ditemukan"
    ↓ ✅
[3] User adalah Creator? → ❌ → Error: "Bukan creator"
    ↓ ✅
[4] Event milik Creator? → ❌ → Error: "Bukan event Anda"
    ↓ ✅
[5] Status = 'active'? → ❌ → Warning/Error sesuai status
    ↓ ✅
[6] Update status → 'used'
    ↓
✅ Success: "Tiket berhasil divalidasi"
```

---

## 🔐 Keamanan

### **Client-Side (PDF Processing):**
- ✅ File tidak dikirim ke server (privasi)
- ✅ Validasi ukuran file (maks. 5MB)
- ✅ Validasi format file (hanya PDF)

### **Server-Side (Validation):**
- ✅ Validasi input (required, max length)
- ✅ Autentikasi (harus login sebagai creator)
- ✅ Authorization (hanya bisa validasi event sendiri)
- ✅ Status check (hanya `active` yang bisa divalidasi)
- ✅ SQL injection protection (Laravel Eloquent)

---

## 📊 Contoh Data Flow

### **Contoh 1: QR Code Valid**

```
PDF Upload
  → Halaman 1: Tidak ada QR
  → Halaman 2: QR ditemukan! "QR-12345-67890"
  → Submit ke server
    ↓
Server:
  → Cari ticket_holder dengan qr_code = "QR-12345-67890"
  → Ditemukan: ticket_holder_id = 100
  → Event ID: 50, Creator ID: 5
  → User login: Creator ID 5 ✅
  → Status: 'active' ✅
  → Update: status = 'used'
  → Response: Success
```

### **Contoh 2: QR Code dari Event Lain**

```
PDF Upload
  → QR ditemukan: "QR-99999-88888"
  → Submit ke server
    ↓
Server:
  → Cari ticket_holder dengan qr_code = "QR-99999-88888"
  → Ditemukan: ticket_holder_id = 200
  → Event ID: 60, Creator ID: 7
  → User login: Creator ID 5 ❌
  → Response: Error "QR Code ini bukan milik event Anda"
```

### **Contoh 3: QR Code Sudah Digunakan**

```
PDF Upload
  → QR ditemukan: "QR-11111-22222"
  → Submit ke server
    ↓
Server:
  → Cari ticket_holder dengan qr_code = "QR-11111-22222"
  → Ditemukan: ticket_holder_id = 300
  → Event ID: 50, Creator ID: 5 ✅
  → Status: 'used' ❌
  → Response: Warning "Tiket ini sudah digunakan sebelumnya"
```

---

## 🛠️ Teknologi yang Digunakan

### **Client-Side:**
1. **PDF.js** - Parse dan render PDF
2. **jsQR** - Scan QR code dari image data
3. **HTML5 Canvas** - Render PDF halaman ke gambar
4. **File API** - Baca file PDF dari input

### **Server-Side:**
1. **Laravel Framework** - Backend processing
2. **Eloquent ORM** - Database queries
3. **Authentication** - User verification
4. **Validation** - Input validation

---

## ⚡ Optimasi

1. **Early Exit:** Loop berhenti saat QR code ditemukan
2. **High Resolution:** Scale 2.0 untuk scan yang lebih akurat
3. **Client-Side Processing:** Tidak membebani server
4. **Eager Loading:** Load relasi event.creator sekaligus

---

## 📝 Catatan Penting

1. **QR Code Format:** Harus sesuai format yang disimpan di database (contoh: "QR-XXXXX-XXXXX")
2. **PDF Quality:** QR code di PDF harus cukup besar dan jelas
3. **Browser Support:** PDF.js dan jsQR memerlukan browser modern
4. **File Size:** Maksimal 5MB untuk performa optimal
5. **Multi-Page:** Sistem scan semua halaman sampai QR code ditemukan

