<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WiVideoEvent extends Model
{
    use HasFactory;

    protected $table = 'wi_video_events';

    protected $fillable = [
        'wi_video_id',
        'time_seconds',
        'type',
        'question',
        'options',
        'correct_index',
        'explanation',
        'is_required',
        'rewind_to_seconds',
        'is_active',
    ];

    protected $casts = [
        'time_seconds' => 'integer',
        'options' => 'array',
        'correct_index' => 'integer',
        'is_required' => 'boolean',
        'rewind_to_seconds' => 'integer',
        'is_active' => 'boolean',
    ];

    public function video()
    {
        return $this->belongsTo(WiVideo::class, 'wi_video_id');
    }
}
