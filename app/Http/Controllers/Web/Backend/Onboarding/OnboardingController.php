<?php
namespace App\Http\Controllers\Web\Backend\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OnboardingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Onboarding::latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', fn(Onboarding $o) => $o->name)
                ->addColumn('is_active', function (Onboarding $o) {
                    $next    = $o->is_active ? 0 : 1;
                    $checked = $o->is_active ? 'checked' : '';
                    return '
                        <a href="#" class="change_status"
                            data-id="' . $o->id . '"
                            data-enabled="' . $next . '"
                            data-title="Do you want to ' . ($next ? 'Enable' : 'Disable') . ' it?"
                            data-description="' . ($next ? 'It will be enabled.' : 'It will be disabled.') . '"
                            data-bs-toggle="modal" data-bs-target="#statusModal">
                            <label class="switch">
                                <input type="checkbox" ' . $checked . '>
                                <span class="slider round"></span>
                            </label>
                        </a>';
                })
                ->addColumn('action', function (Onboarding $o) {
                    return view('components.action-buttons', [
                        'id'        => $o->id,
                        'editModal' => true,
                        'delete'    => true,
                    ])->render();
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('backend.layouts.onboardings.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);
        Onboarding::create($validated);

        return response()->json(['status' => 'success']);
    }

    public function edit(Onboarding $onboarding)
    {
        return response()->json($onboarding);
    }

    public function update(Request $request, Onboarding $onboarding)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);
        $onboarding->update($validated);

        return response()->json(['status' => 'success']);
    }

    public function destroy(Onboarding $onboarding)
    {
        $onboarding->delete();

        return response()->json(['status' => 'success']);
    }

    public function updateStatus(Request $request, Onboarding $onboarding)
    {
        $request->validate(['is_active' => 'required|boolean']);
        $onboarding->update(['is_active' => (bool) $request->is_active]);

        return response()->json(['status' => 'success']);
    }
}
