<?php

namespace App\Console\Commands;

use App\Models\ClickAnalytic;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupAnalyticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:cleanup {--days=3 : Số ngày giữ dữ liệu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa dữ liệu analytics cũ hơn số ngày chỉ định';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Đang xóa dữ liệu analytics cũ hơn {$days} ngày...");

        // Xóa user sessions cũ
        $deletedSessions = UserSession::where('started_at', '<', $cutoffDate)->delete();
        $this->info("Đã xóa {$deletedSessions} user sessions");

        // Xóa click analytics cũ
        $deletedClicks = ClickAnalytic::where('clicked_at', '<', $cutoffDate)->delete();
        $this->info("Đã xóa {$deletedClicks} click records");

        $this->info('Hoàn thành cleanup dữ liệu analytics!');

        return Command::SUCCESS;
    }
}
