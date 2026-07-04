<?php
namespace App\Http\Controllers\Web\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, AnalyticsService $analytics)
    {
        $range = (int) $request->get('range', 8); // last 8 months by default

        // Centralized data fetch
        $metrics = $analytics->getDashboardMetrics();

        return view('backend.layouts.dashboard.index', [
             ...$metrics,
        ]);
    }
}
