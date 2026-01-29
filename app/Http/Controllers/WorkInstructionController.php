<?php

namespace App\Http\Controllers;

use App\Models\WorkInstruction;
use App\Models\ParticipantVideoProgress;
use App\Models\WiVideo;
use Illuminate\Http\Request;

class WorkInstructionController extends Controller
{
    public function index()
    {
        $participantId = session('participant_id');

        $wis = WorkInstruction::where('is_published', 1)
            ->with(['videos' => function ($q) {
                $q->where('is_active', 1)->orderBy('sort_order');
            }])
            ->latest()
            ->get();

        $progressMap = ParticipantVideoProgress::where('participant_id', $participantId)
            ->get()
            ->keyBy('wi_video_id');

        return view('participant.wi.index', compact('wis', 'progressMap'));
    }

    public function playVideo(WiVideo $video)
    {
        if (!$video->is_active) {
            abort(404);
        }
        $participantId = session('participant_id');

        $progress = ParticipantVideoProgress::where('participant_id', $participantId)
            ->where('wi_video_id', $video->id)
            ->first();

        return view('participant.wi.play', compact('video', 'progress'));
    }
}
