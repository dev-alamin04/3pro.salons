<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    // Dashboard metrics
    public function getDashboardMetrics()
    {
        return Cache::remember('analytics:dashboard_metrics', 2, function () {
            return [
                'total_users' => User::count(),
            ];
        });
    }

    // Applications chart (last $range months)
    public function getApplicationsChartData(int $range = 8)
    {
        return Cache::remember("analytics:applications_chart:$range", 2, function () use ($range) {
            $labels = [];
            $data   = [];

            for ($i = $range - 1; $i >= 0; $i--) {
                $date     = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M');

            }

            return compact('labels', 'data');
        });
    }

    // Clear cache
    public function clearCache()
    {
        Cache::forget('analytics:dashboard_metrics');
        Cache::forget('analytics:applications_chart');
        Cache::forget('analytics:jobs_by_category');
    }
}
