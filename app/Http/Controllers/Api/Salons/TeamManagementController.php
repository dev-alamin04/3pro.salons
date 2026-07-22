<?php

namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Http\Resources\UserResource;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeamManagementController extends Controller
{
    use ApiResponse;

    public function createAccount(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error([], "Only Owner or manager can create new account", 403);
        }
        $salon_id = $request->user()->currentSalon?->salon_id;
        if (! $salon_id) {
            return $this->error([], 'No salon assigned.', 404);
        }
        $user = app(UserController::class)->userCreate($request, $salon_id);
        return $this->success($user, 'Account created successfully');
    }

    public function team(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id) {
            return $this->error([], 'No salon assigned.', 404);
        }

        $teamMembers = UserSalon::team($salon_id, 'owner')->where('is_current', 1)
            ->with([
                'user:id,name,email,role,avatar_path,specialist,pronoun,experience_level,trail_end_date,tier_level,is_trail',
                'user.myPiller:id,user_id,name,level,completed',
            ])->get();

        $expiredUserIds = [];
        $teamMembers->each(function ($teamMember) use (&$expiredUserIds) {
            $totalCompleted = $teamMember->user->myPiller->sum('completed');
            $score = min(5, floor($totalCompleted / 60));
            $teamMember->user->setAttribute('score', $score);

            $remainingDays = null;
            $isTrail = $teamMember->user->is_trail;

            Log::info('trails ' . ($isTrail ? 'true' : 'false'));

            if ($isTrail && $teamMember->user->trail_end_date) {
                $remainingDays = Carbon::today()->diffInDays(Carbon::parse($teamMember->user->trail_end_date), false);

                Log::info("User ID: {$teamMember->user->id}, Remaining Days: {$remainingDays}");

                if ($remainingDays <= 0) {
                    $remainingDays = 0;
                    $isTrail = false;
                    $expiredUserIds[] = $teamMember->user->id;
                }
            }
            $teamMember->user->setAttribute('trail_end_date', $remainingDays);
            $teamMember->user->setAttribute('is_trail', $isTrail);
        });

        if (! empty($expiredUserIds)) {
            User::whereIn('id', $expiredUserIds)->update(['is_trail' => false]);
        }

        return $teamMembers;
    }
    // public function myTeams(Request $request)
    // {
    //     $teamMembers = $this->team($request);
    //     $badges  = $teamMembers->myBadges()->with(['assinedBy:id,name', 'salon:id,name'])->where('is_visible', true)->latest()->get();
    //     $history = $teamMembers->myBadges()->selectRaw('YEAR(created_at) as year, COUNT(*) as count')->groupBy('year')->orderBy('year', 'desc')->get();

    //     $response = [
    //         'teammember' => $teamMembers,
    //         'badges'     => $badges,
    //         'history'    => $history
    //     ];
    //     return $this->success($response, 'Successfully fetched team members.');
    // }


    public function myTeams(Request $request)
    {
        $teamMembers = $this->team($request);
        $userIds = $teamMembers->pluck('user.id')->filter()->values();
        $badges = Badge::whereIn('user_id', $userIds)->with(['assinedBy:id,name', 'salon:id,name'])->where('is_visible', true)->latest()->get();
        $history = Badge::whereIn('user_id', $userIds)->selectRaw('YEAR(created_at) as year, COUNT(*) as count')->groupBy('year')->orderBy('year', 'desc')->get();

        $response = [
            'teammember' => $teamMembers,
            'badges'     => $badges,
            'history'    => $history,
        ];

        return $this->success($response, 'Successfully fetched team members.');
    }
    public function teamswitch(Request $request, User $user)
    {
        if (! in_array($request->user()->role, ['owner', 'lead'])) {
            return $this->error([], "Only Owner or manager can switch account", 403);
        }

        $salon_id = $request->user()->currentSalon->salon_id ?? null;

        if (empty($salon_id)) {
            return $this->error([], " You can't take this action");
        }

        if ($user->currentSalon?->salon_id === $salon_id) {
            return $this->error([], "This user is already part of your salon", 403);
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
        $user->setAttribute('current_salon_id', $user->currentSalon?->salon_id);
        $user->setAttribute('current_salon_name', $user->currentSalon?->salon?->name);
        $user->makeHidden('currentSalon');
        return $this->success($user, "find user successfully");
    }

    public function ProfileHistory(Request $request, User $user)
    {
        $badges  = $user->myBadges()->with(['assinedBy:id,name', 'salon:id,name'])->where('is_visible', true)->latest()->get();
        $history = $user->myBadges()->selectRaw('YEAR(created_at) as year, COUNT(*) as count')->groupBy('year')->orderBy('year', 'desc')->get();

        return $this->success([
            'badges'  => $badges,
            'history' => $history,
        ], 'Profile history fetched successfully');
    }

    public function dashboard(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id || $user->role !== 'owner') {
            return $this->error([], 'No salon assigned.');
        }

        $teamMembersCount = UserSalon::team($salon_id, 'owner')->count();
        $leaderCount      = UserSalon::where('salon_id', $salon_id)->whereHas('user', function ($query) {
            $query->whereIn('role', ['lead']);
        })->count();
        $teamMembers = $this->team($request);
        $badgesCount = $teamMembers->sum(function ($member) use ($salon_id) {
            return $member->user?->myBadges()->where('salon_id', $salon_id)->count() ?? 0;
        });
        $onboardings   = $user->currentSalon?->salon?->salonOnboardings()->latest()->get() ?? collect();
        $pendingBadges = $user->currentSalon?->salon?->salonBadges()->where('status', 'pending')->latest()->get() ?? collect();

        return $this->success([
            'user'               => UserResource::make($user),
            'team_members_count' => $teamMembersCount,
            'leader_count'       => $leaderCount,
            'team_members'       => $teamMembers,
            'badges_count'       => $badgesCount,
            'onboardings'        => $onboardings,
            'pendingBadges'      => $pendingBadges,
        ], 'Dashboard data fetched successfully');
    }

    public function userlist(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id) {
            return $this->error([], 'No salon assigned.', 404);
        }
        $teamMembers = UserSalon::team($salon_id, 'owner')->with(['user:id,name,specialist,pronoun',])->get();
        return $this->success($teamMembers, 'successfully get teammember list');
    }


    public function trail(Request $request, User $user)
    {
        $rUser  = $request->user();
        $salon  = $user->currentSalon?->salon_id;
        $rSalon = $rUser->currentSalon?->salon_id;

        if ($rUser->role !== 'owner' || $salon !== $rSalon) {
            return $this->error([], "you can't take this action", 403);
        }

        $validated = $request->validate([
            'trail_end_date' => 'nullable|integer|min:1',
        ]);

        $days = $validated['trail_end_date'] ?? 90;

        $baseDate = $user->trail_end_date && Carbon::parse($user->trail_end_date)->isFuture()
            ? Carbon::parse($user->trail_end_date) : Carbon::today();

        $newEndDate = $baseDate->addDays($days);

        $user->update([
            'trail_end_date' => $newEndDate->toDateString(),
            'is_trail'        => true,
        ]);

        return $this->success($user->fresh(), 'Trial updated successfully');
    }

    public function PromotionDemotion(Request $request, User $user)
    {
        $rUser = $request->user();
        if ($rUser->role !== 'owner' && $rUser->currentSalon->salon_id !== $user->currentSalon->salon_id) {
            return $this->error([], "you can't take this action", 403);
        }
        //toggle role between lead and staff
        $newRole = $user->role === 'lead' ? 'staff' : 'lead';
        $user->update(['role' => $newRole]);
        return $this->success($user, 'User role updated successfully');
    }
}
