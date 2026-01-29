<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WiVideo extends Model
{
    use HasFactory;

    protected $table = 'wi_videos';

    protected $fillable = [
        'work_instruction_id',
        'title',
        'description',
        'video_url',
        'duration_seconds',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function workInstruction()
    {
        return $this->belongsTo(WorkInstruction::class, 'work_instruction_id');
    }

    public function events()
    {
        return $this->hasMany(WiVideoEvent::class, 'wi_video_id')
            ->orderBy('time_seconds');
    }

    public function activeEvents()
    {
        return $this->hasMany(WiVideoEvent::class, 'wi_video_id')
            ->where('is_active', true)
            ->orderBy('time_seconds');
    }
}
