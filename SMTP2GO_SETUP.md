# Konfigurasi SMTP2GO untuk Forgot Password

Dokumen ini menjelaskan cara mengkonfigurasi SMTP2GO untuk fitur forgot password di aplikasi Laravel.

## Langkah-langkah Setup

### 1. Daftar/Login ke SMTP2GO

1. Kunjungi https://www.smtp2go.com
2. Buat akun baru atau login ke akun yang sudah ada
3. Setelah login, masuk ke **Settings** > **SMTP Users** atau **API Keys**

### 2. Dapatkan Kredensial SMTP2GO

SMTP2GO menyediakan beberapa opsi:
- **SMTP Users**: Username dan password untuk SMTP
- **API Keys**: API key untuk SMTP authentication

Pilih salah satu yang tersedia di dashboard SMTP2GO Anda.

### 3. Konfigurasi di File .env

Tambahkan atau update konfigurasi berikut di file `.env`:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.smtp2go.com
MAIL_PORT=2525
MAIL_USERNAME=your_smtp2go_username_or_api_key
MAIL_PASSWORD=your_smtp2go_password_or_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Opsi Port dan Encryption

SMTP2GO mendukung beberapa port dan encryption:

**Opsi 1: Port 2525 dengan TLS (Recommended)**
```env
MAIL_PORT=2525
MAIL_ENCRYPTION=tls
```

**Opsi 2: Port 587 dengan TLS**
```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

**Opsi 3: Port 465 dengan SSL**
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

**Opsi 4: Port 80 tanpa encryption (tidak direkomendasikan)**
```env
MAIL_PORT=80
MAIL_ENCRYPTION=null
```

### 5. Verifikasi Konfigurasi

Setelah mengupdate file `.env`, pastikan untuk:

1. **Clear config cache** (jika menggunakan config cache):
   ```bash
   php artisan config:clear
   ```

2. **Test email** dengan menjalankan:
   ```bash
   php artisan tinker
   ```
   Kemudian di tinker:
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('your-email@example.com')
               ->subject('Test Email');
   });
   ```

### 6. Menggunakan Forgot Password

1. Buka halaman login
2. Klik link "Forgot your password?"
3. Masukkan email yang terdaftar
4. Cek inbox email untuk link reset password
5. Klik link tersebut dan buat password baru

## Troubleshooting

### Email tidak terkirim

1. **Cek kredensial SMTP2GO**: Pastikan username dan password benar
2. **Cek port dan encryption**: Pastikan sesuai dengan yang dikonfigurasi di SMTP2GO
3. **Cek firewall**: Pastikan port SMTP tidak diblokir
4. **Cek log**: Lihat file `storage/logs/laravel.log` untuk error detail

### Error "Connection timeout"

- Coba ganti port ke 587 atau 465
- Pastikan encryption (tls/ssl) sesuai dengan port yang digunakan
- Cek apakah firewall atau antivirus memblokir koneksi SMTP

### Error "Authentication failed"

- Pastikan username dan password benar
- Jika menggunakan API key, pastikan formatnya benar
- Cek apakah akun SMTP2GO masih aktif

### Email masuk ke spam

- Pastikan `MAIL_FROM_ADDRESS` menggunakan domain yang terverifikasi di SMTP2GO
- Tambahkan SPF dan DKIM records di DNS domain Anda
- Gunakan email yang terverifikasi di SMTP2GO

## Catatan Penting

- **MAIL_FROM_ADDRESS**: Harus menggunakan email yang terverifikasi di SMTP2GO
- **Rate Limiting**: SMTP2GO memiliki batasan jumlah email per hari sesuai paket Anda
- **Security**: Jangan commit file `.env` ke repository git

## Support

Jika masih mengalami masalah:
1. Cek dokumentasi resmi SMTP2GO: https://www.smtp2go.com/docs/
2. Cek log aplikasi di `storage/logs/laravel.log`
3. Hubungi support SMTP2GO jika masalah terkait dengan akun SMTP2GO

