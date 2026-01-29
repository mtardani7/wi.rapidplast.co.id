# Dokumentasi Fitur Video dengan Multiple Source Support

## Daftar Perubahan (Implementation Summary)

Fitur berikut telah diimplementasikan untuk mendukung upload video dan video dari link/CDN:

### 1. Database Migrations
- **File**: `database/migrations/2026_01_29_000001_add_video_source_to_wi_videos.php`
- **Perubahan**:
  - Menambah kolom `video_source_type` (string) - untuk menyimpan tipe sumber video
  - Menambah kolom `embed_code` (text, nullable) - untuk custom embed code
  - Menambah index pada `video_source_type`

#### Tipe Video yang Didukung:
- `upload` - File video upload langsung (MP4)
- `youtube` - Link YouTube (youtu.be atau youtube.com)
- `vimeo` - Link Vimeo (vimeo.com)
- `cdn` - Link CDN atau URL langsung ke file video

### 2. Model Updates
- **File**: `app/Models/WiVideo.php`
- **Perubahan**:
  - Menambah `video_source_type` dan `embed_code` ke dalam `$fillable`
  - Menambah method `getEmbedHtml()` untuk generate HTML embed berdasarkan tipe video
  - Menambah helper methods:
    - `extractYoutubeId()` - Extract video ID dari YouTube URL
    - `extractVimeoId()` - Extract video ID dari Vimeo URL
    - `isExternalVideo()` - Check apakah video adalah external (non-upload)

### 3. Admin Controller Updates
- **File**: `app/Http/Controllers/Admin/WiVideoAdminController.php`
- **Perubahan pada `store()` method**:
  - Validasi dinamis berdasarkan tipe video
  - Untuk `upload`: validasi file MP4, max 500MB
  - Untuk `youtube`: validasi URL format YouTube
  - Untuk `vimeo`: validasi URL format Vimeo
  - Untuk `cdn`: validasi URL umum

- **Perubahan pada `update()` method**:
  - Support perubahan tipe video
  - Jika berubah dari upload ke external, hapus file lama
  - Jika tetap upload, file bisa diupdate atau dibiarkan

### 4. Admin Interface Updates
- **File**: `resources/views/admin/wi_videos/index.blade.php`
- **Perubahan pada Modal Upload**:
  - Menambah radio buttons untuk pilih tipe video (Upload, YouTube, Vimeo, CDN)
  - Conditional input fields yang muncul sesuai tipe yang dipilih:
    - Upload: File input
    - YouTube: Text input untuk URL YouTube
    - Vimeo: Text input untuk URL Vimeo
    - CDN: Text input untuk URL CDN/direct link
  - Alpine.js untuk handle reactive UI

- **Perubahan pada Modal Edit**:
  - Sama seperti modal upload
  - Support switching antara tipe video

### 5. Participant Video Player Updates
- **File**: `resources/views/participant/wi/play.blade.php`
- **Perubahan**:
  - Conditional rendering berdasarkan `video_source_type`
  - Untuk YouTube: embed iframe YouTube
  - Untuk Vimeo: embed iframe Vimeo
  - Untuk upload/CDN: HTML5 video player

**PENTING**: Quiz system TIDAK perlu diubah karena:
- Quiz tracking bekerja berdasarkan `time_seconds` pada video
- Semua tipe video (HTML5, YouTube iframe, Vimeo iframe) support property `currentTime`
- Quiz dapat diakses dan dimanipulasi secara normal di semua tipe video

---

## Cara Penggunaan

### Admin Upload Video

#### 1. Upload File MP4
1. Buka admin panel → Video WI
2. Klik "Upload Video"
3. Pilih "Upload File"
4. Upload file MP4 (max 500MB)
5. Klik "Upload"

#### 2. Tambah Video dari YouTube
1. Buka admin panel → Video WI
2. Klik "Upload Video"
3. Pilih "YouTube"
4. Paste link YouTube (contoh: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`)
5. Klik "Upload"

#### 3. Tambah Video dari Vimeo
1. Buka admin panel → Video WI
2. Klik "Upload Video"
3. Pilih "Vimeo"
4. Paste link Vimeo (contoh: `https://vimeo.com/123456789`)
5. Klik "Upload"

#### 4. Tambah Video dari CDN
1. Buka admin panel → Video WI
2. Klik "Upload Video"
3. Pilih "CDN/Link"
4. Paste URL CDN atau direct link (contoh: `https://cdn.example.com/video.mp4`)
5. Klik "Upload"

### Edit Video

Untuk mengedit video yang sudah ada:
1. Klik tombol Edit pada baris video
2. Bisa mengubah:
   - Judul
   - Deskripsi
   - Urutan
   - Tipe video (bisa switch dari satu tipe ke tipe lain)
   - File (jika upload) atau URL (jika external)
3. Klik "Update"

**Catatan**: Jika switch dari upload ke external atau sebaliknya, file upload lama akan otomatis dihapus.

### Quiz/Assessment

Quiz berfungsi normal di semua tipe video:
- Quiz tetap trigger pada waktu yang sudah ditentukan
- Jawaban tetap valid dan scored
- Progress tersimpan otomatis
- Participant harus score minimum 70 untuk lulus
- Jika jawaban salah, video bisa di-rewind sesuai setting

---

## Struktur Data

### WiVideo Table
```sql
CREATE TABLE wi_videos (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    work_instruction_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(255) NOT NULL,
    video_source_type VARCHAR(255) DEFAULT 'upload',
    embed_code TEXT,
    duration_seconds INT,
    sort_order INT DEFAULT 1,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Migration Command
```bash
php artisan migrate
```

---

## API Endpoints (Unchanged)

Quiz endpoint tetap bekerja dengan semua tipe video:
- `GET /wi/video/{video}/events` - Dapatkan list quiz events
- `POST /wi/video/{video}/events/{event}/attempt` - Submit jawaban quiz
- `POST /wi/video/{video}/progress` - Save progress video

---

## Troubleshooting

### Video tidak muncul di player
- **Upload**: Check apakah file ada di `storage/app/public/wi_videos/`
- **YouTube**: Check apakah URL valid dan video tidak private
- **Vimeo**: Check apakah URL valid dan video tidak private
- **CDN**: Check apakah URL accessible dari client browser

### Quiz tidak trigger
- Check apakah quiz sudah ditambahkan untuk video tersebut
- Check apakah time_seconds sesuai durasi video
- Check apakah `is_active = true`

### File tidak terhapus saat switch type
- Cek logs di `storage/logs/laravel.log`
- Ensure `storage/app/public/` writable

---

## Catatan Teknis

1. **Extract IDs dari External Video**:
   - YouTube: Menggunakan regex pattern untuk extract dari berbagai format URL
   - Vimeo: Menggunakan regex pattern untuk extract ID numerik

2. **Conditional Rendering**:
   - Frontend menggunakan `x-show` Alpine.js untuk hide/show form fields
   - Backend menggunakan Laravel conditional Blade `@if` untuk render HTML

3. **File Cleanup**:
   - File upload lama otomatis dihapus saat update atau delete
   - Gunakan Laravel Storage facade untuk operasi file

4. **Security**:
   - YouTube/Vimeo iframe dengan allowlist
   - Video URL di-escape untuk prevent XSS
   - File upload validated berdasarkan MIME type

---

## Future Enhancements

1. Auto-extract video duration dari external sources
2. Support lebih banyak platform (TikTok, Instagram, etc)
3. Fallback player jika external video tidak loadable
4. Custom video player controls
5. Subtitle/Caption support

---

**Last Updated**: 2026-01-29
**Version**: 1.0
