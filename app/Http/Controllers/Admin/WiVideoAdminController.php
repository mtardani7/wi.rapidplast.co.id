<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkInstruction;
use App\Models\WiVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WiVideoAdminController extends Controller
{
    public function index(WorkInstruction $wi)
    {
        $videos = $wi->videos()->orderBy('sort_order')->get();
        return view('admin.wi_videos.index', compact('wi', 'videos'));
    }

    public function store(Request $request, WorkInstruction $wi)
    {
        $videoSourceType = $request->input('video_source_type', 'upload');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'video_source_type' => ['required', 'in:upload,youtube,vimeo,cdn,google_drive,onedrive'],
        ];

        if ($videoSourceType === 'upload') {
            $rules['video_file'] = ['required', 'file', 'mimes:mp4', 'max:512000'];
        } elseif ($videoSourceType === 'youtube') {
            $rules['video_url'] = ['required', 'url', 'regex:/youtube\.com|youtu\.be/'];
        } elseif ($videoSourceType === 'vimeo') {
            $rules['video_url'] = ['required', 'url', 'regex:/vimeo\.com/'];
        } elseif ($videoSourceType === 'cdn') {
            $rules['video_url'] = ['required', 'url'];
        } elseif ($videoSourceType === 'google_drive') {
            $rules['video_url'] = ['required', 'url', 'regex:/drive\.google\.com|docs\.google\.com/'];
        } elseif ($videoSourceType === 'onedrive') {
            $rules['video_url'] = ['required', 'url', 'regex:/1drv\.ms|onedrive\.live\.com/'];
        }

        $request->validate($rules);

        $videoUrl = null;
        $embedCode = null;

        if ($videoSourceType === 'upload') {
            $path = $request->file('video_file')->store('wi_videos', 'public');
            $videoUrl = asset('storage/' . $path);
        } else {
            $videoUrl = $request->input('video_url');
        }

        WiVideo::create([
            'work_instruction_id' => $wi->id,
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'video_source_type' => $videoSourceType,
            'embed_code' => $embedCode,
            'duration_seconds' => null,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Video berhasil diupload.');
    }

    public function update(Request $request, WorkInstruction $wi, WiVideo $video)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);

        $videoSourceType = $request->input('video_source_type', $video->video_source_type);

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'video_source_type' => ['required', 'in:upload,youtube,vimeo,cdn,google_drive,onedrive'],
        ];

        if ($videoSourceType === 'upload') {
            $rules['video_file'] = ['nullable', 'file', 'mimes:mp4', 'max:512000'];
        } elseif ($videoSourceType === 'youtube') {
            $rules['video_url'] = ['required', 'url', 'regex:/youtube\.com|youtu\.be/'];
        } elseif ($videoSourceType === 'vimeo') {
            $rules['video_url'] = ['required', 'url', 'regex:/vimeo\.com/'];
        } elseif ($videoSourceType === 'cdn') {
            $rules['video_url'] = ['required', 'url'];
        } elseif ($videoSourceType === 'google_drive') {
            $rules['video_url'] = ['required', 'url', 'regex:/drive\.google\.com|docs\.google\.com/'];
        } elseif ($videoSourceType === 'onedrive') {
            $rules['video_url'] = ['required', 'url', 'regex:/1drv\.ms|onedrive\.live\.com/'];
        }

        $request->validate($rules);

        $video->title = $request->title;
        $video->description = $request->description;
        $video->sort_order = $request->sort_order;
        $video->is_active = $request->boolean('is_active');
        $video->video_source_type = $videoSourceType;

        if ($videoSourceType === 'upload') {
            if ($request->hasFile('video_file')) {
                if ($video->video_url && str_contains($video->video_url, '/storage/')) {
                    $relative = str_replace(asset('storage') . '/', '', $video->video_url);
                    Storage::disk('public')->delete($relative);
                }

                $path = $request->file('video_file')->store('wi_videos', 'public');
                $video->video_url = asset('storage/' . $path);
            }
        } else {
            $video->video_url = $request->input('video_url');
            if ($video->video_url && str_contains($video->video_url, '/storage/')) {
                $relative = str_replace(asset('storage') . '/', '', $video->video_url);
                Storage::disk('public')->delete($relative);
            }
        }

        $video->save();

        return back()->with('success', 'Video berhasil diupdate.');
    }

    public function destroy(WorkInstruction $wi, WiVideo $video)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);

        if ($video->video_url && str_contains($video->video_url, '/storage/')) {
            $relative = str_replace(asset('storage') . '/', '', $video->video_url);
            Storage::disk('public')->delete($relative);
        }

        $video->delete();

        return back()->with('success', 'Video berhasil dihapus.');
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
