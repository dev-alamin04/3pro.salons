<?php
namespace App\Http\Controllers\Web\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $query = User::where('role', '!=', 'admin')->latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', fn(User $u) => $u->name ?? 'N/A')
                ->addColumn('email', fn(User $u) => $u->email ?? 'N/A')
                ->addColumn('joined_at', fn(User $u) => $u->joined_at?->format('d-M-Y h:i A') ?? 'N/A')
                ->addColumn('is_active', function (User $u) {
                    $next    = $u->is_active ? 0 : 1;
                    $checked = $u->is_active ? 'checked' : '';
                    return '
                        <a href="#" class="change_status" data-id="' . $u->id . '" data-enabled="' . $next . '"
                            data-title="Do you want to ' . ($next ? 'Enable' : 'Disable') . ' it?"
                            data-description="' . ($next ? 'He will access account' : 'He will be disabled') . '"
                            data-bs-toggle="modal" data-bs-target="#statusModal">
                            <label class="switch">
                                <input type="checkbox" ' . $checked . '>
                                <span class="slider round"></span>
                            </label>
                        </a>';
                })
                ->addColumn('action', function (User $u) {
                    return view('components.action-buttons', [
                        'id'     => $u->id,
                        'show'   => 'users.show',
                        'delete' => true,
                    ])->render();
                })

                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('backend.layouts.users.index');
    }

    /**
     * Register .
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for approval!');
    }

    public function show(User $user)
    {
        return view('backend.layouts.users.show', compact('user'));
    }

    public function updateAccountStatus(Request $request, User $user)
    {
        $request->validate(['is_active' => 'required|boolean']);
        $user->update(['is_active' => (bool) $request->is_active]);
        return response()->json(['status' => 'success']);
    }

    public function destroy(User $user)
    {
        deleteFile($user->avatar_path);
        $user->delete();
        return response()->json(['status' => 'success']);
    }
}
