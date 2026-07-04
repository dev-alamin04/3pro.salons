<?php

namespace App\Http\Controllers\Web\Backend\Goal;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Goal::with(['user', 'assinedBy', 'user.currentSalon.salon'])->latest();

            return $this->buildGoalDataTables($query, true)->make(true);
        }

        return view('backend.layouts.goals.index');
    }

    public function show(Goal $goal)
    {
        $goal->load([
            'user',
            'assinedBy',
            'user.currentSalon.salon',
        ]);

        return view('backend.layouts.goals.show', compact('goal'));
    }

    public function userGoals(Request $request, int $userId)
    {
        $user = User::with(['currentSalon.salon'])->findOrFail($userId);

        if ($request->ajax()) {
            $query = Goal::where('user_id', $userId)->with(['assinedBy', 'user.currentSalon.salon'])->latest();

            return $this->buildGoalDataTables($query, false)->make(true);
        }

        return view('backend.layouts.goals.user-goals', compact('user'));
    }

    private function buildGoalDataTables($query, bool $includeUser)
    {
        $dt = DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('assigned_by_name', fn(Goal $g) => $g->assinedBy?->name ?? 'N/A')
            ->addColumn('title', fn(Goal $g) => $g->title ?? 'N/A')
            ->addColumn('level', function (Goal $g) {
                $class = match ($g->level) {
                    'mastery'    => 'bg-success text-white',
                    'advanced'   => 'bg-info text-white',
                    'foundation' => 'bg-warning text-dark',
                    default      => 'bg-secondary text-white',
                };
                return '<span class="badge ' . $class . '">' . ucfirst($g->level ?? 'N/A') . '</span>';
            })
            ->addColumn('progress', function (Goal $g) {
                $percent = min(((int) $g->progress / 5) * 100, 100);
                return '<div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: ' . $percent . '%"></div>
                </div>
                <small class="text-muted">' . $g->progress . '/5</small>';
            })
            ->addColumn('status', function (Goal $g) {
                $class = match ($g->status) {
                    'completed' => 'bg-success',
                    'late'      => 'bg-danger',
                    default     => 'bg-warning text-dark',
                };
                return '<span class="badge ' . $class . '">' . ucfirst($g->status ?? 'pending') . '</span>';
            })
            ->addColumn('target_date', fn(Goal $g) => $g->target_date?->format('d M Y') ?? 'N/A')
            ->addColumn('action', function (Goal $g) {
                return view('components.action-buttons', [
                    'id'   => $g->id,
                    'show' => 'admin.goals.show',
                ])->render();
            })
            ->rawColumns(['level', 'progress', 'status', 'action']);

        if ($includeUser) {
            $dt->addColumn('user_name', fn(Goal $g) => $g->user?->name ?? 'N/A')
               ->addColumn('salon_name', fn(Goal $g) => $g->user?->currentSalon?->salon?->name ?? 'N/A');
        }

        return $dt;
    }
}
