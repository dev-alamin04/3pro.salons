<?php

namespace App\Http\Controllers\Web\Backend\Badge;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Badge::with(['user', 'assinedBy', 'salon', 'pillar'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', fn(Badge $b) => $b->user?->name ?? 'N/A')
                ->addColumn('assigned_by_name', fn(Badge $b) => $b->assinedBy?->name ?? 'N/A')
                ->addColumn('salon_name', fn(Badge $b) => $b->salon?->name ?? 'N/A')
                ->addColumn('pillar_name', fn(Badge $b) => $b->pillar?->name ?? 'N/A')
                ->addColumn('perfomence_level', function (Badge $b) {
                    $class = match ($b->perfomence_level) {
                        'mastery'   => 'bg-success text-white',
                        'advanced'  => 'bg-info text-white',
                        'foundation' => 'bg-warning text-dark',
                        default     => 'bg-secondary text-white',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($b->perfomence_level ?? 'N/A') . '</span>';
                })
                ->addColumn('status', function (Badge $b) {
                    $class = match ($b->status) {
                        'approved'  => 'bg-success',
                        'rejected'  => 'bg-danger',
                        default     => 'bg-warning text-dark',
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($b->status ?? 'pending') . '</span>';
                })
                ->addColumn('is_visible', function (Badge $b) {
                    return $b->is_visible
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>';
                })
                ->addColumn('notes', function (Badge $b) {
                    $text = $b->notes ?? 'N/A';
                    return '<span title="' . e($text) . '">' . Str::limit(e($text), 50) . '</span>';
                })
                ->addColumn('action', function (Badge $b) {
                    return view('components.action-buttons', [
                        'id'   => $b->id,
                        'show' => 'admin.badges.show',
                    ])->render();
                })
                ->rawColumns(['perfomence_level', 'status', 'is_visible', 'notes', 'action'])
                ->make(true);
        }

        return view('backend.layouts.badges.index');
    }

    public function show(Badge $badge)
    {
        $badge->load(['user', 'assinedBy', 'salon', 'pillar']);

        return view('backend.layouts.badges.show', compact('badge'));
    }
}
