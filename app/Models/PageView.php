<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'url',
        'session_id',
        'ip_address',
        'user_agent',
        'referer_url',
        'page_type',
        'page_id',
        'device_type',
        'browser',
        'platform',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }
}
