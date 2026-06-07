<?php

namespace Tests\Feature;

use App\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_view_can_be_created()
    {
        PageView::create([
            'url' => '/test',
            'session_id' => 'test-session',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent/1.0',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'platform' => 'Windows',
            'visited_at' => now(),
        ]);

        $this->assertDatabaseHas('page_views', [
            'url' => '/test',
            'session_id' => 'test-session',
        ]);
    }
}
