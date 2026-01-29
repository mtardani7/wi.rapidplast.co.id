<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'plan',
    ];

    public function progresses()
    {
        return $this->hasMany(ParticipantVideoProgress::class, 'participant_id');
    }

    public function attempts()
    {
        return $this->hasMany(VideoEventAttempt::class, 'participant_id');
    }
}
