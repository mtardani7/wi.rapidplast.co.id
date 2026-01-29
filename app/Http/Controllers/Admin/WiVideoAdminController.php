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
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],

            // WAJIB UPLOAD MP4
            'video_file' => ['required', 'file', 'mimes:mp4', 'max:512000'], // 500MB
        ]);

        $path = $request->file('video_file')->store('wi_videos', 'public');
        $videoUrl = asset('storage/' . $path);

        WiVideo::create([
            'work_instruction_id' => $wi->id,
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $videoUrl,
            'duration_seconds' => null,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Video berhasil diupload.');
    }

    public function update(Request $request, WorkInstruction $wi, WiVideo $video)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],

            // optional kalau mau replace file
            'video_file' => ['nullable', 'file', 'mimes:mp4', 'max:512000'],
        ]);

        // kalau upload file baru -> hapus lama
        if ($request->hasFile('video_file')) {

            // hapus file lama kalau berasal dari storage
            if ($video->video_url && str_contains($video->video_url, '/storage/')) {
                $relative = str_replace(asset('storage') . '/', '', $video->video_url);
                Storage::disk('public')->delete($relative);
            }

            $path = $request->file('video_file')->store('wi_videos', 'public');
            $video->video_url = asset('storage/' . $path);
        }

        $video->title = $request->title;
        $video->description = $request->description;
        $video->sort_order = $request->sort_order;
        $video->is_active = $request->boolean('is_active');
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
}
