<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkInstruction;
use App\Models\WiVideo;
use App\Models\WiVideoEvent;
use Illuminate\Http\Request;

class WiVideoEventAdminController extends Controller
{
    protected function getVideo(WorkInstruction $wi, $videoId): WiVideo
    {
        return WiVideo::where('id', $videoId)
            ->where('work_instruction_id', $wi->id)
            ->firstOrFail();
    }

    protected function getEvent(WiVideo $video, $eventId): WiVideoEvent
    {
        return WiVideoEvent::where('id', $eventId)
            ->where('wi_video_id', $video->id)
            ->firstOrFail();
    }

    public function index(WorkInstruction $wi, WiVideo $video)
    {
        $video = $this->getVideo($wi, $video->id);

        $events = WiVideoEvent::where('wi_video_id', $video->id)
            ->orderBy('time_seconds')
            ->get();

        return view('admin.wi_video_events.index', compact('wi', 'video', 'events'));
    }

    public function store(Request $request, WorkInstruction $wi, WiVideo $video)
    {
        $video = $this->getVideo($wi, $video->id);

        $validated = $request->validate([
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

        $timeSeconds = ($validated['minute'] * 60) + $validated['second'];

        $rewindSeconds = null;
        if (
            isset($validated['rewind_minute']) ||
            isset($validated['rewind_second'])
        ) {
            $rewindSeconds =
                ((int)($validated['rewind_minute'] ?? 0) * 60)
                + ((int)($validated['rewind_second'] ?? 0));
        }

        WiVideoEvent::create([
            'wi_video_id' => $video->id,
            'time_seconds' => $timeSeconds,
            'type' => 'quiz',
            'question' => $validated['question'],
            'options' => [
                $validated['option_a'],
                $validated['option_b'],
                $validated['option_c'],
                $validated['option_d'],
            ],
            'correct_index' => (int) $validated['correct_index'],
            'explanation' => $validated['explanation'] ?? null,
            'is_required' => $request->boolean('is_required'),
            'rewind_to_seconds' => $rewindSeconds,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Quiz event berhasil ditambahkan.');
    }

    public function update(Request $request, WorkInstruction $wi, WiVideo $video, WiVideoEvent $event)
    {
        $video = $this->getVideo($wi, $video->id);
        $event = $this->getEvent($video, $event->id);

        $validated = $request->validate([
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

        $timeSeconds = ($validated['minute'] * 60) + $validated['second'];

        $rewindSeconds = null;
        if (
            isset($validated['rewind_minute']) ||
            isset($validated['rewind_second'])
        ) {
            $rewindSeconds =
                ((int)($validated['rewind_minute'] ?? 0) * 60)
                + ((int)($validated['rewind_second'] ?? 0));
        }

        $event->update([
            'time_seconds' => $timeSeconds,
            'question' => $validated['question'],
            'options' => [
                $validated['option_a'],
                $validated['option_b'],
                $validated['option_c'],
                $validated['option_d'],
            ],
            'correct_index' => (int) $validated['correct_index'],
            'explanation' => $validated['explanation'] ?? null,
            'is_required' => $request->boolean('is_required'),
            'rewind_to_seconds' => $rewindSeconds,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Quiz event berhasil diupdate.');
    }

    public function destroy(WorkInstruction $wi, WiVideo $video, WiVideoEvent $event)
    {
        $video = $this->getVideo($wi, $video->id);
        $event = $this->getEvent($video, $event->id);

        $event->delete();

        return back()->with('success', 'Quiz event berhasil dihapus.');
    }
}
