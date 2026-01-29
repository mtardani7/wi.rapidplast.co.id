# Setup & Testing Guide - Video Upload & CDN Support

## Prerequisites
- Laravel project already running
- Database connected and migrated

## Setup Instructions

### 1. Run the Migration
```bash
php artisan migrate
```

This will add two new columns to the `wi_videos` table:
- `video_source_type` (enum: upload, youtube, vimeo, cdn)
- `embed_code` (for custom embedding)

### 2. Verify Database Changes
```bash
php artisan tinker

# Check columns
>>> Schema::getColumnListing('wi_videos')
```

You should see the new columns in the list.

### 3. Test the Admin Interface

#### Test 1: Upload Local Video File
1. Login as admin
2. Go to Work Instruction → Videos
3. Click "Upload Video"
4. Select "Upload File" option
5. Fill in:
   - Title: "Test Video Upload"
   - Description: "Testing local file upload"
   - Select an MP4 file (< 500MB)
   - Order: 1
6. Click "Upload"
7. **Expected Result**: Video appears in list with source type "upload"

#### Test 2: Add YouTube Video
1. Click "Upload Video"
2. Select "YouTube" option
3. Fill in:
   - Title: "YouTube Test"
   - Description: "Testing YouTube embedding"
   - YouTube URL: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
   - Order: 2
4. Click "Upload"
5. **Expected Result**: Video appears in list with source type "youtube"

#### Test 3: Add Vimeo Video
1. Click "Upload Video"
2. Select "Vimeo" option
3. Fill in:
   - Title: "Vimeo Test"
   - Description: "Testing Vimeo embedding"
   - Vimeo URL: `https://vimeo.com/900`
   - Order: 3
4. Click "Upload"
5. **Expected Result**: Video appears in list with source type "vimeo"

#### Test 4: Add CDN Video
1. Click "Upload Video"
2. Select "CDN/Link" option
3. Fill in:
   - Title: "CDN Test"
   - Description: "Testing CDN link"
   - CDN URL: Any accessible video MP4 URL
   - Order: 4
4. Click "Upload"
5. **Expected Result**: Video appears in list with source type "cdn"

### 4. Test Edit Functionality

#### Test 5: Edit Video (Change Type)
1. Click Edit on the YouTube video
2. In the modal, change source type to "CDN/Link"
3. Input a valid CDN URL
4. Click "Update"
5. **Expected Result**: Video source type changes, old URL is replaced

#### Test 6: Edit Upload Video (Keep File)
1. Click Edit on the uploaded video
2. Leave video file input empty
3. Change title to "Updated Upload Video"
4. Click "Update"
5. **Expected Result**: Video title changes but file remains

### 5. Test Participant Experience

#### Test 7: Play Different Video Types
1. Logout as admin
2. Login as participant
3. Select work instruction
4. Click on the videos
5. **Expected Result**: 
   - Upload video: Shows HTML5 video player
   - YouTube video: Shows YouTube embedded player
   - Vimeo video: Shows Vimeo embedded player
   - CDN video: Shows HTML5 video player

#### Test 8: Quiz Works on All Video Types
1. As admin, add quiz events to different video types
2. As participant, play each video type
3. When time reaches quiz trigger:
   - **Expected**: Quiz modal appears
   - **Expected**: Quiz answers can be submitted
   - **Expected**: Score updates correctly
   - **Expected**: Can rewind if wrong answer

### 6. Database Verification

```bash
php artisan tinker

# Check a specific video
>>> $video = App\Models\WiVideo::first()
>>> $video->video_source_type  # Should output: upload, youtube, vimeo, or cdn
>>> $video->video_url          # Should output the URL or path
```

### 7. File System Verification

Check uploaded files are stored correctly:
```bash
ls -la storage/app/public/wi_videos/
```

You should see MP4 files named with UUID prefix.

## Troubleshooting

### Migration Fails
```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check existing migrations
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback
```

### UI Issues
- Clear cache: `php artisan cache:clear`
- Clear views: `php artisan view:clear`
- Rebuild: `npm run build`

### File Upload Issues
- Check storage is linked: `php artisan storage:link`
- Check permissions: `chmod -R 775 storage/app/public/`
- Check .env: `FILESYSTEM_DRIVER=public`

### Video Doesn't Play
- **Upload**: Check file exists in storage
- **YouTube**: Try different video ID
- **Vimeo**: Make sure video is public
- **CDN**: Check CORS headers and URL accessibility

## Sample Data for Testing

### Test Videos
- YouTube: `https://www.youtube.com/watch?v=jNQXAC9IVRw` (Big Buck Bunny trailer)
- Vimeo: `https://vimeo.com/900` (Simple clip)
- CDN: `https://test-streams.mux.dev/x36xhzz/x3ksqt.m3u8` (Public test stream)

### Quiz Testing Steps
1. Add a quiz event at 5 seconds
2. Play video of each type
3. When time reaches 5 seconds, modal should appear
4. Select answer and verify feedback
5. Check score updates in the UI

## Expected File Structure After Implementation

```
project/
├── app/
│   └── Models/
│       └── WiVideo.php (✓ Updated)
├── app/Http/Controllers/Admin/
│   └── WiVideoAdminController.php (✓ Updated)
├── database/
│   └── migrations/
│       ├── 2026_01_22_075941_create_wi_videos_table.php
│       └── 2026_01_29_000001_add_video_source_to_wi_videos.php (✓ NEW)
├── resources/views/
│   ├── admin/wi_videos/
│   │   └── index.blade.php (✓ Updated)
│   └── participant/wi/
│       └── play.blade.php (✓ Updated)
└── storage/app/public/
    └── wi_videos/
        └── [uploaded video files]
```

## Verification Checklist

- [ ] Migration runs successfully
- [ ] Database columns added correctly
- [ ] Admin can upload local MP4 files
- [ ] Admin can add YouTube videos
- [ ] Admin can add Vimeo videos
- [ ] Admin can add CDN videos
- [ ] Admin can edit videos and change type
- [ ] Participant can play upload videos
- [ ] Participant can play YouTube videos
- [ ] Participant can play Vimeo videos
- [ ] Participant can play CDN videos
- [ ] Quiz works on all video types
- [ ] Score tracking works correctly
- [ ] File cleanup works on video deletion
- [ ] Video list displays correctly in admin

## Performance Notes

1. **External Videos** (YouTube, Vimeo): No storage impact, depends on external CDN
2. **Upload Videos**: Uses server storage, consider implementing cleanup policies
3. **CDN Videos**: External links, user responsible for CDN management
4. **Quiz Performance**: Unaffected by video type, still tracks by `time_seconds`

## Security Considerations

1. File uploads validated by MIME type (MP4 only)
2. External URLs validated to prevent malicious embeds
3. Storage files served through authenticated routes
4. CORS headers configured for external embeds

---

**Happy Testing!** 🎬
