<?php

namespace Database\Seeders;

use App\Models\ClickAnalytic;
use App\Models\UserSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo 50 user sessions
        UserSession::factory(50)->create();

        // Tạo 200 click analytics
        ClickAnalytic::factory(200)->create();
    }
}
