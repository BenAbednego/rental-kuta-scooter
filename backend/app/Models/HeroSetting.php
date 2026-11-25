<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = [
        'type',
        'image',
        'slides',
        'slide_interval',
        'video_source',
        'video_path',
        'youtube_id',
        'poster',
    ];

    protected function casts(): array
    {
        return [
            'slides' => 'array',
            'slide_interval' => 'integer',
        ];
    }

    public static function youtubeIdFromUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');
        $id = null;

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $id = $query['v'] ?? null;
            } elseif (preg_match('#^(?:embed|shorts)/([^/]+)$#', $path, $matches) === 1) {
                $id = $matches[1];
            }
        } elseif (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $id = explode('/', $path)[0] ?? null;
        }

        return is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id) === 1
            ? $id
            : null;
    }
}
