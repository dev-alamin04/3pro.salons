<?php

namespace App\Http\Controllers\Web\Backend\Team;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', '!=', 'admin')
                ->with(['currentSalon.salon'])
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', fn(User $u) => $u->name ?? 'N/A')
                ->addColumn('email', fn(User $u) => $u->email ?? 'N/A')
                ->addColumn('role', function (User $u) {
                    $class = match ($u->role) {
                        'owner'  => 'bg-primary text-white',
                        'lead'   => 'bg-info text-white',
                        'staff'  => 'bg-secondary text-white',
                        default  => 'bg-dark text-white',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($u->role ?? 'N/A') . '</span>';
                })
                ->addColumn('salon_name', fn(User $u) => $u->currentSalon?->salon?->name ?? 'N/A')
                ->addColumn('specialist', fn(User $u) => $u->specialist ?? 'N/A')
                ->addColumn('is_active', function (User $u) {
                    return $u->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function (User $u) {
                    return view('components.action-buttons', [
                        'id'   => $u->id,
                        'show' => 'admin.team.show',
                    ])->render();
                })
                ->rawColumns(['role', 'is_active', 'action'])
                ->make(true);
        }

        return view('backend.layouts.team.index');
    }

    public function show(User $user)
    {
        $user->load([
            'currentSalon.salon',
            'mygoal' => fn($q) => $q->with('assinedBy')->latest()->limit(10),
            'myBadges' => fn($q) => $q->with(['assinedBy', 'salon', 'pillar'])->latest()->limit(10),
            'myTask' => fn($q) => $q->with('assignedBy')->latest()->limit(10),
            'myReport' => fn($q) => $q->with(['reportedBy', 'salon'])->latest()->limit(10),
            'userSkill',
        ]);

        return view('backend.layouts.team.show', compact('user'));
    }
}
