<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkInstruction;
use App\Models\WiVideo;
use App\Models\WiVideoEvent;
use Illuminate\Http\Request;

class WiVideoEventAdminController extends Controller
{
    public function index(WorkInstruction $wi, WiVideo $video)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);

        $events = WiVideoEvent::where('wi_video_id', $video->id)
            ->orderBy('time_seconds')
            ->get();

        return view('admin.wi_video_events.index', compact('wi', 'video', 'events'));
    }

    public function store(Request $request, WorkInstruction $wi, WiVideo $video)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);

        $request->validate([
            'minute' => ['required', 'integer', 'min:0'],
            'second' => ['required', 'integer', 'min:0', 'max:59'],

            'question' => ['required', 'string', 'max:255'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],

            'correct_index' => ['required', 'integer', 'min:0', 'max:3'],
            'explanation' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'rewind_minute' => ['nullable', 'integer', 'min:0'],
            'rewind_second' => ['nullable', 'integer', 'min:0', 'max:59'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $timeSeconds = ((int)$request->minute * 60) + (int)$request->second;

        $rewindSeconds = null;
        if ($request->filled('rewind_minute') || $request->filled('rewind_second')) {
            $rewindSeconds = ((int)($request->rewind_minute ?? 0) * 60) + (int)($request->rewind_second ?? 0);
        }

        WiVideoEvent::create([
            'wi_video_id' => $video->id,
            'time_seconds' => $timeSeconds,
            'type' => 'quiz',
            'question' => $request->question,
            'options' => [
                $request->option_a,
                $request->option_b,
                $request->option_c,
                $request->option_d,
            ],
            'correct_index' => (int)$request->correct_index,
            'explanation' => $request->explanation,
            'is_required' => $request->boolean('is_required'),
            'rewind_to_seconds' => $rewindSeconds,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Quiz event berhasil ditambahkan.');
    }

    public function update(Request $request, WorkInstruction $wi, WiVideo $video, WiVideoEvent $event)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);
        abort_if($event->wi_video_id !== $video->id, 404);

        $request->validate([
            'minute' => ['required', 'integer', 'min:0'],
            'second' => ['required', 'integer', 'min:0', 'max:59'],
            'question' => ['required', 'string', 'max:255'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_index' => ['required', 'integer', 'min:0', 'max:3'],
            'explanation' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'rewind_minute' => ['nullable', 'integer', 'min:0'],
            'rewind_second' => ['nullable', 'integer', 'min:0', 'max:59'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $timeSeconds = ((int)$request->minute * 60) + (int)$request->second;

        $rewindSeconds = null;
        if ($request->filled('rewind_minute') || $request->filled('rewind_second')) {
            $rewindSeconds = ((int)($request->rewind_minute ?? 0) * 60) + (int)($request->rewind_second ?? 0);
        }

        $event->update([
            'time_seconds' => $timeSeconds,
            'question' => $request->question,
            'options' => [
                $request->option_a,
                $request->option_b,
                $request->option_c,
                $request->option_d,
            ],
            'correct_index' => (int)$request->correct_index,
            'explanation' => $request->explanation,
            'is_required' => $request->boolean('is_required'),
            'rewind_to_seconds' => $rewindSeconds,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Quiz event berhasil diupdate.');
    }

    public function destroy(WorkInstruction $wi, WiVideo $video, WiVideoEvent $event)
    {
        abort_if($video->work_instruction_id !== $wi->id, 404);
        abort_if($event->wi_video_id !== $video->id, 404);

        $event->delete();

        return back()->with('success', 'Quiz event berhasil dihapus.');
    }
}
