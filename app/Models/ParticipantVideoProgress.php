<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantVideoProgress extends Model
{
    use HasFactory;

    protected $table = 'participant_video_progress';

    protected $fillable = [
        'participant_id',
        'wi_video_id',
        'last_time_seconds',
        'score',
        'completed_at',
    ];

    protected $casts = [
        'last_time_seconds' => 'integer',
        'score' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function video()
    {
        return $this->belongsTo(WiVideo::class, 'wi_video_id');
    }
}
