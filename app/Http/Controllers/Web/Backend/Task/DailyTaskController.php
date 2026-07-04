<?php

namespace App\Http\Controllers\Web\Backend\Task;

use App\Http\Controllers\Controller;
use App\Models\DailyTask;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DailyTaskController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DailyTask::with(['user', 'assignedBy', 'user.currentSalon.salon'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', fn(DailyTask $t) => $t->user?->name ?? 'N/A')
                ->addColumn('assigned_by_name', fn(DailyTask $t) => $t->assignedBy?->name ?? 'N/A')
                ->addColumn('salon_name', fn(DailyTask $t) => $t->user?->currentSalon?->salon?->name ?? 'N/A')
                ->addColumn('title', fn(DailyTask $t) => $t->title ?? 'N/A')
                ->addColumn('description', function (DailyTask $t) {
                    $text = $t->description ?? 'N/A';
                    return '<span title="' . e($text) . '">' . Str::limit(e($text), 50) . '</span>';
                })
                ->addColumn('target_date', fn(DailyTask $t) => $t->target_date?->format('d M Y') ?? 'N/A')
                ->addColumn('is_completed', function (DailyTask $t) {
                    return $t->is_completed
                        ? '<span class="badge bg-success">Completed</span>'
                        : '<span class="badge bg-warning text-dark">Pending</span>';
                })
                ->addColumn('action', function (DailyTask $t) {
                    return view('components.action-buttons', [
                        'id'   => $t->id,
                        'show' => 'admin.tasks.show',
                    ])->render();
                })
                ->rawColumns(['description', 'is_completed', 'action'])
                ->make(true);
        }

        return view('backend.layouts.tasks.index');
    }

    public function show(DailyTask $task)
    {

        $task->load([
            'user',
            'assignedBy',
            'user.currentSalon.salon',
        ]);

        return view('backend.layouts.tasks.show', compact('task'));
    }
}
