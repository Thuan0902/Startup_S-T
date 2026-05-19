<?php

namespace App\Http\Controllers;

use App\Models\ClickAnalytic;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function recordClick(Request $request)
    {
        $request->validate([
            'item_type' => 'required|string',
            'item_id' => 'required|integer',
        ]);

        ClickAnalytic::create([
            'user_id' => Auth::id(),
            'item_type' => $request->item_type,
            'item_id' => $request->item_id,
            'clicked_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function startSession(Request $request)
    {
        $session = UserSession::create([
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'started_at' => now(),
        ]);

        // Store session ID in session
        session(['analytics_session_id' => $session->id]);

        return response()->json(['session_id' => $session->id]);
    }

    public function endSession(Request $request)
    {
        $sessionId = session('analytics_session_id');
        if ($sessionId) {
            $session = UserSession::find($sessionId);
            if ($session && !$session->ended_at) {
                $session->update([
                    'ended_at' => now(),
                    'duration_seconds' => now()->diffInSeconds($session->started_at),
                    'total_clicks' => ClickAnalytic::where('user_id', $session->user_id)
                        ->whereBetween('clicked_at', [$session->started_at, now()])
                        ->count(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function getDailyStats()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $stats = UserSession::whereBetween('started_at', [$today, $tomorrow])
            ->selectRaw('COUNT(*) as visitors')
            ->selectRaw('SUM(duration_seconds) as total_time')
            ->selectRaw('SUM(total_clicks) as clicks')
            ->first();

        return response()->json($stats);
    }

    public function getStats(Request $request)
    {
        $period = $request->get('period', 'day'); // day, week, month, year

        $startDate = match($period) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfDay(),
        };

        $endDate = Carbon::now();

        $stats = UserSession::whereBetween('started_at', [$startDate, $endDate])
            ->selectRaw('DATE(started_at) as date')
            ->selectRaw('COUNT(*) as visitors')
            ->selectRaw('SUM(duration_seconds) as total_time')
            ->selectRaw('SUM(total_clicks) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($stats);
    }

    // Lấy thống kê 3 ngày gần nhất
    public function getThreeDayStats()
    {
        $stats = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $endDate = $date->clone()->addDay();

            $clicksByType = ClickAnalytic::whereBetween('clicked_at', [$date, $endDate])
                ->selectRaw("item_type, COUNT(*) as count")
                ->groupBy('item_type')
                ->pluck('count', 'item_type');

            $stats[] = [
                'date' => $date->format('Y-m-d'),
                'visitors' => UserSession::whereBetween('started_at', [$date, $endDate])->count(),
                'clicks' => $clicksByType->sum(),
                'clicks_by_type' => $clicksByType->toArray(),
            ];
        }

        return response()->json($stats);
    }
}