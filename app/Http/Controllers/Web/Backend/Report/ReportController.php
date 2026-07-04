<?php

namespace App\Http\Controllers\Web\Backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Report::with(['user', 'reportedBy', 'salon'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', fn(Report $r) => $r->user?->name ?? 'N/A')
                ->addColumn('reported_by_name', fn(Report $r) => $r->reportedBy?->name ?? 'N/A')
                ->addColumn('salon_name', fn(Report $r) => $r->salon?->name ?? 'N/A')
                ->addColumn('progress_type', fn(Report $r) => $r->progress_type ?? 'N/A')
                ->addColumn('report_text', function (Report $r) {
                    $text = $r->report_text ?? 'N/A';
                    return '<span title="' . e($text) . '">' . Str::limit(e($text), 60) . '</span>';
                })
                ->addColumn('created_at', fn(Report $r) => $r->created_at?->format('d M Y h:i A') ?? 'N/A')
                ->addColumn('action', function (Report $r) {
                    return view('components.action-buttons', [
                        'id'   => $r->id,
                        'show' => 'admin.reports.show',
                    ])->render();
                })
                ->rawColumns(['report_text', 'action'])
                ->make(true);
        }

        return view('backend.layouts.reports.index');
    }

    public function show(Report $report)
    {
        $report->load(['user', 'reportedBy', 'salon']);

        return view('backend.layouts.reports.show', compact('report'));
    }
}
