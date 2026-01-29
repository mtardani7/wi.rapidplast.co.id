<?php

namespace App\Http\Controllers;

use App\Models\WiVideo;
use App\Models\WiVideoEvent;
use App\Models\ParticipantVideoProgress;
use App\Models\VideoEventAttempt;
use Illuminate\Http\Request;

class ParticipantVideoController extends Controller
{
    public function saveProgress(Request $request, WiVideo $video)
    {
        $participantId = session('participant_id');

        $request->validate([
            'last_time_seconds' => ['required', 'integer', 'min:0'],
        ]);

        ParticipantVideoProgress::updateOrCreate(
            [
                'participant_id' => $participantId,
                'wi_video_id' => $video->id,
            ],
            [
                'last_time_seconds' => (int) $request->last_time_seconds,
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function getEvents(WiVideo $video)
    {
        $events = WiVideoEvent::where('wi_video_id', $video->id)
            ->where('is_active', 1)
            ->orderBy('time_seconds')
            ->get([
                'id',
                'time_seconds',
                'type',
                'question',
                'options',
                'correct_index', // NOTE: nanti sebaiknya jangan dikirim (untuk keamanan). MVP dulu.
                'explanation',
                'is_required',
                'rewind_to_seconds',
            ]);

        return response()->json($events);
    }

    public function getEvent(WiVideo $video)
    {
        $events = WiVideoEvent::where('wi_video_id', $video->id)
            ->where('is_active', 1)
            ->orderBy('time_seconds')
            ->get([
                'id',
                'time_seconds',
                'type',
                'question',
                'options',
                'is_required',
                'rewind_to_seconds',
                // ❌ jangan kirim correct_index untuk keamanan
            ]);

        return response()->json($events);
    }

    public function submitAttempt(Request $request, WiVideo $video, WiVideoEvent $event)
    {
        $participantId = session('participant_id');

        if ($event->wi_video_id !== $video->id) {
            abort(404);
        }

        $request->validate([
            'selected_index' => ['required', 'integer', 'min:0', 'max:3'],
        ]);

        $selected = (int) $request->selected_index;
        $isCorrect = ($selected === (int) $event->correct_index);

        // attempt_no otomatis increment
        $attemptNo = VideoEventAttempt::where('participant_id', $participantId)
            ->where('wi_video_id', $video->id)
            ->where('wi_video_event_id', $event->id)
            ->count() + 1;

        VideoEventAttempt::create([
            'participant_id' => $participantId,
            'wi_video_id' => $video->id,
            'wi_video_event_id' => $event->id,
            'selected_index' => $selected,
            'is_correct' => $isCorrect,
            'attempt_no' => $attemptNo,
        ]);

        /**
         * =========================
         * HITUNG SCORE TOTAL 100
         * =========================
         * Score = jumlah quiz required yang sudah pernah benar * (100 / total quiz required)
         */
        $requiredEventIds = WiVideoEvent::where('wi_video_id', $video->id)
            ->where('is_active', 1)
            ->where('is_required', 1)
            ->pluck('id');

        $totalRequired = $requiredEventIds->count();

        $score = 0;

        if ($totalRequired > 0) {
            $correctRequiredCount = VideoEventAttempt::where('participant_id', $participantId)
                ->where('wi_video_id', $video->id)
                ->whereIn('wi_video_event_id', $requiredEventIds)
                ->where('is_correct', 1)
                ->distinct('wi_video_event_id')
                ->count('wi_video_event_id');

            $pointPerQuiz = 100 / $totalRequired;
            $score = (int) round($correctRequiredCount * $pointPerQuiz);

            if ($score > 100) $score = 100;
            if ($score < 0) $score = 0;
        }

        // simpan score ke progress
        ParticipantVideoProgress::updateOrCreate(
            [
                'participant_id' => $participantId,
                'wi_video_id' => $video->id,
            ],
            [
                'score' => $score,
            ]
        );

        return response()->json([
            'ok' => true,
            'is_correct' => $isCorrect,
            'score' => $score, // ✅ kirim balik ke frontend biar bisa ditampilkan
            'rewind_to_seconds' => $event->rewind_to_seconds,
            'explanation' => $event->explanation,
        ]);
    }


    private function recalcScore(int $participantId, int $videoId): int
    {
        // total quiz required dalam video
        $requiredEventIds = WiVideoEvent::where('wi_video_id', $videoId)
            ->where('is_active', 1)
            ->where('is_required', 1)
            ->pluck('id');

        $totalRequired = $requiredEventIds->count();

        if ($totalRequired === 0) {
            return 0;
        }

        // event required yang sudah pernah dijawab benar
        $correctRequiredCount = VideoEventAttempt::where('participant_id', $participantId)
            ->where('wi_video_id', $videoId)
            ->whereIn('wi_video_event_id', $requiredEventIds)
            ->where('is_correct', 1)
            ->distinct('wi_video_event_id')
            ->count('wi_video_event_id');

        $pointPerQuiz = 100 / $totalRequired;
        $score = (int) round($correctRequiredCount * $pointPerQuiz);

        if ($score > 100) $score = 100;
        if ($score < 0) $score = 0;

        return $score;
    }
        
}
