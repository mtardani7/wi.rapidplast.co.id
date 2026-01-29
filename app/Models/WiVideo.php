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
        'video_source_type',
        'embed_code',
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

    /**
     * Generate video embed HTML based on source type
     */
    public function getEmbedHtml(): string
    {
        return match($this->video_source_type) {
            'youtube' => $this->getYoutubeEmbed(),
            'vimeo' => $this->getVimeoEmbed(),
            'cdn' => $this->getCdnEmbed(),
            'embed' => $this->embed_code ?? '',
            default => $this->getUploadEmbed(),
        };
    }

    private function getUploadEmbed(): string
    {
        return sprintf(
            '<video id="wiVideo" controls playsinline preload="metadata" class="w-full rounded-lg"><source src="%s" type="video/mp4">Browser kamu tidak support video.</video>',
            htmlspecialchars($this->video_url, ENT_QUOTES)
        );
    }

    private function getYoutubeEmbed(): string
    {
        $videoId = $this->extractYoutubeId($this->video_url);
        return sprintf(
            '<iframe id="wiVideo" width="100%%" height="500" src="https://www.youtube.com/embed/%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-lg"></iframe>',
            htmlspecialchars($videoId, ENT_QUOTES)
        );
    }

    private function getVimeoEmbed(): string
    {
        $videoId = $this->extractVimeoId($this->video_url);
        return sprintf(
            '<iframe id="wiVideo" src="https://player.vimeo.com/video/%s" width="100%%" height="500" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen class="rounded-lg"></iframe>',
            htmlspecialchars($videoId, ENT_QUOTES)
        );
    }

    private function getCdnEmbed(): string
    {
        return sprintf(
            '<video id="wiVideo" controls playsinline preload="metadata" class="w-full rounded-lg"><source src="%s" type="video/mp4">Browser kamu tidak support video.</video>',
            htmlspecialchars($this->video_url, ENT_QUOTES)
        );
    }

    public function extractYoutubeId(string $url): string
    {
        if (preg_match('/youtube\.com.*[?&]v=([^&]+)/', $url, $match)) {
            return $match[1];
        }
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $match)) {
            return $match[1];
        }
        return '';
    }

    public function extractVimeoId(string $url): string
    {
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $match)) {
            return $match[1];
        }
        return '';
    }

    public function isExternalVideo(): bool
    {
        return in_array($this->video_source_type, ['youtube', 'vimeo', 'cdn', 'embed']);
    }}