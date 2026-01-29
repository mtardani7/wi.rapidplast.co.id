<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function videos()
    {
        return $this->hasMany(WiVideo::class, 'work_instruction_id')
            ->orderBy('sort_order');
    }

    public function activeVideos()
    {
        return $this->hasMany(WiVideo::class, 'work_instruction_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }
}
