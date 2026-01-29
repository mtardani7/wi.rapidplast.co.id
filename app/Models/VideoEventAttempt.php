<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEventAttempt extends Model
{
    use HasFactory;

    protected $table = 'video_event_attempts';

    protected $fillable = [
        'participant_id',
        'wi_video_id',
        'wi_video_event_id',
        'selected_index',
        'is_correct',
        'attempt_no',
    ];

    protected $casts = [
        'selected_index' => 'integer',
        'is_correct' => 'boolean',
        'attempt_no' => 'integer',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function video()
    {
        return $this->belongsTo(WiVideo::class, 'wi_video_id');
    }

    public function event()
    {
        return $this->belongsTo(WiVideoEvent::class, 'wi_video_event_id');
    }
}
