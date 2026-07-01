<?php
namespace App\Http\Controllers\Web\Backend\Salon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Http\Requests\Salon\SalonRequest;
use App\Models\Salon;
use App\Models\User;
use App\Models\UserSalon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SalonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Salon::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', fn(Salon $s) => $s->name ?? 'N/A')
                ->addColumn('location', fn(Salon $s) => $s->location ?? 'N/A')
                ->addColumn('address', fn(Salon $s) => $s->address ?? 'N/A')
                ->addColumn('action', function (Salon $s) {
                    return view('components.action-buttons', [
                        'id'     => $s->id,
                        'edit'   => 'salons.edit',
                        'delete' => true,
                        'extra'  => [
                            ['route' => route('salons.assign', $s->id), 'label' => 'Assign User', 'class' => 'btn-info'],
                        ],
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.salons.index');
    }

    public function create()
    {
        return view('backend.layouts.salons.create');
    }

    public function store(SalonRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $validated['avatar_path'] = $request->file('avatar')->store('salons', 'public');
        }

        unset($validated['avatar']);

        Salon::create($validated);

        return redirect()->route('salons.index')->with('success', 'Salon created successfully.');
    }

    public function edit(Salon $salon)
    {
        return view('backend.layouts.salons.edit', compact('salon'));
    }

    public function update(SalonRequest $request, Salon $salon)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            deleteFile($salon->avatar_path);
            $validated['avatar_path'] = $request->file('avatar')->store('salons', 'public');
        }

        unset($validated['avatar']);

        $salon->update($validated);

        return redirect()->route('salons.index')->with('success', 'Salon updated successfully.');
    }

    public function destroy(Salon $salon)
    {
        deleteFile($salon->avatar_path);
        $salon->delete();

        return response()->json(['status' => 'success']);
    }

    public function assignedUsers(Request $request, Salon $salon)
    {
        if ($request->ajax()) {
            $query = $salon->users()->getQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', fn(User $u) => $u->name ?? 'N/A')
                ->addColumn('email', fn(User $u) => $u->email ?? 'N/A')
                ->addColumn('action', function (User $u) use ($salon) {
                    return '<button class="btn btn-sm btn-danger remove-user"
                        data-salon="' . $salon->id . '"
                        data-user="' . $u->id . '">Remove</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $users = User::where('role', '!=', 'admin')->get();

        return view('backend.layouts.salons.assign', compact('salon', 'users'));
    }

    public function assignUser(Request $request, Salon $salon)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $exists = UserSalon::where('salon_id', $salon->id)->where('user_id', $request->user_id)->exists();

        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'User already assigned.'], 422);
        }

        $user = User::findOrFail($request->user_id);

        UserSalon::create([
            'salon_id'   => $salon->id,
            'user_id'    => $user->id,
            'assined_by' => Auth::id(),
        ]);

        $user->update(['secret_key' => app(UserController::class)->generateSecretKey($user)]);
        return response()->json(['status' => 'success']);
    }

    public function removeUser(Salon $salon, User $user)
    {
        UserSalon::where('salon_id', $salon->id)->where('user_id', $user->id)->delete();

        return response()->json(['status' => 'success']);
    }
}
