<?php

namespace Tests\Feature;

use App\Models\ClickAnalytic;
use App\Models\UserSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test daily stats API
     *
     * @return void
     */
    public function test_daily_stats()
    {
        // Tạo dữ liệu mẫu
        UserSession::factory(10)->create();
        ClickAnalytic::factory(20)->create();

        // Gọi API
        $response = $this->get('/api/analytics/daily-stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total_visitors',
                     'total_time_minutes',
                     'total_clicks',
                     'clicks_by_type'
                 ]);

        // Verify dữ liệu
        $data = $response->json();
        $this->assertGreaterThanOrEqual(0, $data['total_visitors']);
        $this->assertGreaterThanOrEqual(0, $data['total_time_minutes']);
        $this->assertGreaterThanOrEqual(0, $data['total_clicks']);
    }

    /**
     * Test three day stats API
     *
     * @return void
     */
    public function test_three_day_stats()
    {
        // Tạo dữ liệu mẫu
        UserSession::factory(5)->create();
        ClickAnalytic::factory(15)->create();

        // Gọi API
        $response = $this->get('/api/analytics/three-day-stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'date',
                         'date_display',
                         'article_clicks',
                         'product_clicks',
                         'total_clicks'
                     ]
                 ]);
    }

    /**
     * Test export daily data
     *
     * @return void
     */
    public function test_export_daily_data()
    {
        // Tạo dữ liệu mẫu
        UserSession::factory(5)->create();
        ClickAnalytic::factory(10)->create();

        // Test export hôm nay
        $today = now()->format('Y-m-d');
        $response = $this->get("/api/analytics/export-daily?date={$today}");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', "attachment; filename=\"analytics_{$today}.csv\"");

        // Verify có nội dung
        $content = $response->getContent();
        $this->assertNotEmpty($content);
        $this->assertStringContains('=== USER SESSIONS ===', $content);
        $this->assertStringContains('=== CLICK ANALYTICS ===', $content);
    }
}
