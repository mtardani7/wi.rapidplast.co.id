<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkInstruction;
use App\Models\WiVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WiVideoController extends Controller
{
    public function index($wiId)
    {
        $wi = WorkInstruction::findOrFail($wiId);
        $videos = $wi->videos()->orderBy('sort_order')->get();
        
        return view('admin.wi_videos.index', compact('wi', 'videos'));
    }

    public function store(Request $request, $wiId)
    {
        $wi = WorkInstruction::findOrFail($wiId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:1',
            'video_source_type' => 'required|in:upload,youtube,vimeo,cdn,google_drive,onedrive',
            'video_file' => 'nullable|file|mimes:mp4|max:512000',
            'video_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $videoUrl = null;

        if ($validated['video_source_type'] === 'upload' && $request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('videos/wi-' . $wiId, $filename, 'public');
            $videoUrl = Storage::url($path);
        } elseif (in_array($validated['video_source_type'], ['youtube', 'vimeo', 'cdn', 'google_drive', 'onedrive'])) {
            $videoUrl = $validated['video_url'];
        }

        if (!$videoUrl) {
            return back()->withErrors(['video_file' => 'Video file or URL is required']);
        }

        WiVideo::create([
            'work_instruction_id' => $wiId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'],
            'video_source_type' => $validated['video_source_type'],
            'video_url' => $videoUrl,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Video berhasil diupload');
    }

    public function update(Request $request, $wiId, $videoId)
    {
        $wi = WorkInstruction::findOrFail($wiId);
        $video = WiVideo::where('work_instruction_id', $wiId)->findOrFail($videoId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:1',
            'video_source_type' => 'required|in:upload,youtube,vimeo,cdn,google_drive,onedrive',
            'video_file' => 'nullable|file|mimes:mp4|max:512000',
            'video_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $videoUrl = $video->video_url;

        if ($validated['video_source_type'] === 'upload') {
            if ($request->hasFile('video_file')) {
                if ($video->video_source_type === 'upload' && $video->video_url) {
                    $oldPath = str_replace('/storage/', 'public/', $video->video_url);
                    Storage::delete($oldPath);
                }
                
                $file = $request->file('video_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('videos/wi-' . $wiId, $filename, 'public');
                $videoUrl = Storage::url($path);
            }
        } else {
            $videoUrl = $validated['video_url'] ?? $video->video_url;
        }

        $video->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'],
            'video_source_type' => $validated['video_source_type'],
            'video_url' => $videoUrl,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Video berhasil diperbarui');
    }

    public function destroy($wiId, $videoId)
    {
        $wi = WorkInstruction::findOrFail($wiId);
        $video = WiVideo::where('work_instruction_id', $wiId)->findOrFail($videoId);

        if ($video->video_source_type === 'upload' && $video->video_url) {
            $path = str_replace('/storage/', 'public/', $video->video_url);
            Storage::delete($path);
        }

        $video->delete();

        return back()->with('success', 'Video berhasil dihapus');
    }

    public function player(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $videoUrl = $request->query('url');
        $videoType = $request->query('type', 'upload');

        if (!$videoUrl) {
            abort(404, 'Video not found');
        }

        return view('admin.wi_videos.player', compact('videoUrl', 'videoType'));
    }
}
