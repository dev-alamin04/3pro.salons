<?php
namespace App\Http\Controllers\Api\Goals;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Goal;
use App\Models\User;
use App\Models\UserSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validated = $request->validate([
            "user_id"     => "required|exists:users,id",
            "title"       => "required|string",
            "description" => "nullable|string",
            "level"       => "required|string|in:foundation,advanced,mastery",
            "piller"      => "nullable|in:time,cleanliness,appearance,self_motivation,downtime",
            "target_date" => "nullable|date",
            "is_public"   => "boolean",
        ]);

        $validated['target_date'] = $request->target_date ?? today()->toDateString();
        $gaol                     = $request->user()->goal_assigned_by()->create($validated);

        return $this->success($gaol, 'Gaol create successfully');
    }

    public function lastGaol(Request $request, User $user)
    {
        $goal = $user->mygoal()->with('assinedBy:id,name')->where('assigned_by', $request->user()->id)->where('is_public', true)->latest()->get();
        return $this->success($goal, 'successfully get goal');
    }

    public function myGoals(Request $request)
    {
        $user = $request->user();
        $goal = $user->mygoal()->with('assinedBy:id,name')->where('is_active', true)->when($request->filled('is_public'), fn($q) => $q->where('is_public', filter_var($request->is_public, FILTER_VALIDATE_BOOLEAN)))->latest()->get();
        return $this->success($goal, 'successfully get my goals');
    }

    public function updateLevel(Request $request, Goal $goal)
    {
        $request->validate([
            'progress' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $user = $request->user();

        if ($user->id !== $goal->user_id && $goal->is_public !== false) {
            return $this->error([], "You can only update your personal goal status");
        }

        $salon_id = $goal->user->currentSalon?->salon_id;

        if ($user->currentSalon?->salon_id !== $salon_id) {
            return $this->error([], "You can't take this action");
        }

        $goal->update(['progress' => $request->progress]);
        if( $goal->progress >= 5) {
            $goal->update(['status' => 'completed']);
        }else {
            $goal->update(['status' => 'in_progress']);
        }

        return $this->success($goal, 'Goal updated successfully');
    }
    public function pillerDetails(User $user)
    {
        $pillars = $user->myPiller()->select(['id', 'user_id', 'name', 'completed', 'level'])->latest()->get();

        $response = [
            'user'    => UserResource::make($user),
            'pillars' => $pillars,
        ];

        return $this->success($response, "pillar details fetched successfully");
    }

    public function salonUser(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }

        $teamMembers = UserSalon::team($salon_id, 'owner')
            ->with([
                'user:id,name,email,role,avatar_path,specialist,pronoun,experience_level',
            ])->get();

        return $this->success($teamMembers, 'Successfully fetched team members.');
    }
}
