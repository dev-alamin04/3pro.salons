<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Models\User;
use App\Models\UserSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TeamManagementController extends Controller
{
    use ApiResponse;

    public function createAccount(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error("Only Owner or manager can create new account");
        }
        $salon_id = $request->user()->currentSalon?->salon_id;
        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }
        $user = app(UserController::class)->userCreate($request, $salon_id);
        return $this->success($user, 'Account created successfully');
    }

    public function myTeams(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }

        $teamMembers = UserSalon::team($salon_id, 'owner')
            ->with([
                'user:id,name,email,role,avatar_path,specialist,pronoun,exprience_level',
                'user.myPiller:id,user_id,name,level,completed',
            ])->get();

        return $this->success($teamMembers, 'Successfully fetched team members.');
    }

    public function teamswitch(Request $request, User $user)
    {
        if (! in_array($request->user()->role, ['owner', 'lead'])) {
            return $this->error("Only Owner or manager can switch account");
        }

        $salon_id = $request->user()->currentSalon->salon_id ?? null;

        if (empty($salon_id)) {
            return $this->error([], " You can't take this action");
        }

        $user->currentSalon?->update(['is_current' => false]);

        $request->user()->salon_assigned_by()->updateOrCreate(
            [
                'user_id'  => $user->id,
                'salon_id' => $salon_id,
            ],
            [
                'is_current' => true,
            ]
        );

        return $this->success([], "salon assign successfully");
    }

    public function findbySecretKey(Request $request)
    {
        $request->validate([
            "secret_key" => "required|string|exists:users,secret_key",
        ]);
        $user = User::where("secret_key", $request->secret_key)->first();
        return $this->success($user, "find user successfully");
    }

    public function ProfileHistory(Request $request, User $user)
    {
        $badges = $user->myBadges()->with(['assinedBy:id,name', 'salon:id,name'])->where('is_visible', true)->latest()->get();
        $history = $user->myBadges()->selectRaw('YEAR(created_at) as year, COUNT(*) as count')->groupBy('year')->orderBy('year', 'desc')->get();

        return $this->success([
            'badges'  => $badges,
            'history' => $history,
        ], 'Profile history fetched successfully');
    }

}
