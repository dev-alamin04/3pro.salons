<?php
namespace App\Http\Controllers\Web\Backend\User;

use App\Http\Controllers\Controller;
use App\Mail\SecretKeyMail;
use App\Models\Salon;
use App\Models\User;
use App\Models\UserSalon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

    public function create()
    {
        $salons = Salon::latest()->get();
        return view('backend.layouts.users.create', compact('salons'));
    }

    public function userCreate(Request $request, $salon_id)
    {
        $validated = $request->validate([
            'name'  => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'role'  => 'required|string|in:staff,owner,lead',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'joined_at' => now(),
        ]);

        if (! empty($salon_id)) {
            $salon = Salon::findOrFail($salon_id);

            $request->user()->salon_assigned_by()->create([
                'user_id'  => $user->id,
                'salon_id' => $salon_id,
            ]);

            $user->update(['secret_key' => $this->generateSecretKey($user, $salon)]);
            Mail::to($user->email)->send(new SecretKeyMail($user));
        }

        return $user;
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'salon_id' => 'required|exists:salons,id',
        ]);
        $this->userCreate($request, $validated['salon_id']);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
    private function generateSecretKey(User $user, Salon $salon): string
    {
        $assignedCount = UserSalon::where('salon_id', $salon->id)->count();
        $nextNumber    = ($salon->start_sequence ?? 1000) + $assignedCount;
        $initials      = collect(explode(' ', trim($user->name)))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');

        return '3PRO-' . $nextNumber . '-' . $initials;
    }
}
