<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserPiller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BadgeController extends Controller
{
    use ApiResponse;

    const BADGES_PER_PILLAR = 60;

    const EXPERIENCE_LEVELS = [
        'foundation',
        'advanced',
        'mastry',
    ];

    public function index(Request $request)
    {
        $badges = Badge::with(['user', 'pillar'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->piller_id, fn($q) => $q->where('piller_id', $request->piller_id))
            ->latest()->paginate($request->per_page ?? 15);

        return $this->success($badges, "badges fetched successfully");
    }

    public function show(Badge $badge)
    {
        $badge->load(['user', 'pillar']);

        return $this->success($badge, "badge fetched successfully");
    }

    public function storeBadge(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'piller_id'        => 'required|exists:user_pillers,id',
            'notes'            => 'nullable|string',
            'perfomence_level' => 'required|string',
            'is_visible'       => 'required|boolean',
        ]);
        $validated['salon_id'] = $request->user()->currentSalon->salon_id ?? null;

        $badge = DB::transaction(function () use ($request, $validated) {
            $badge = $request->user()->badge_assigned_by()->create($validated);

            if ($request->user()->role === 'owner') {
                $badge->update(['status' => 'approved']);
                $this->incrementDecrement($validated['piller_id'], $validated['user_id']);
            }
            return $badge;
        });

        return $this->success($badge, "badges create successfully");
    }

    public function updateBadge(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'piller_id'        => 'sometimes|required|exists:user_pillers,id',
            'notes'            => 'nullable|string',
            'perfomence_level' => 'sometimes|required|string',
            'is_visible'       => 'sometimes|required|boolean',
        ]);

        $validated['user_id'] = $badge->user_id;
        if (isset($validated['piller_id']) && $validated['piller_id'] != $badge->piller_id) {
            $oldPillar = UserPiller::find($badge->piller_id);
            $oldPillar?->decrement('completed');

            $newPillar = UserPiller::find($validated['piller_id']);
            $newPillar?->increment('completed');
        }

        $badge->update($validated);

        return $this->success($badge->fresh(), "badge updated successfully");
    }

    public function acceptReject(Request $request, Badge $badge)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
        ]);

        $user = $request->user();

        if ($user->role === 'owner' && $badge->salon_id === $user->currentSalon?->salon_id) {
            $badge->update(['status' => $request->status]);
            if ($request->status === 'approved') {
                $this->incrementDecrement($badge->piller_id, $badge->user_id);
            }
            return $this->success($badge->fresh(), 'update successfully');
        }
        return $this->success($badge->fresh(), "you can't take this action");
    }
    public function destroy(Badge $badge)
    {
        DB::transaction(function () use ($badge) {
            $pillar = UserPiller::find($badge->piller_id);
            $pillar?->decrement('completed');

            $user = $badge->user;
            if ($user && $user->badge > 0) {
                $user->decrement('badge');
            }

            $badge->delete();
        });

        return $this->success(null, "badge deleted successfully");
    }

    public function badgesHistory(User $user)
    {
        $badges = $user->myBadges()->with(['pillar', 'user:id,name', 'assinedBy:id,name'])->orderBy("created_at", "desc")->get();
        return $this->success($badges, "successfully get badges history");
    }

    private function checkAndLevelUp(User $user): void
    {
        $pillarCount = UserPiller::where('user_id', $user->id)->count();
        $threshold   = $pillarCount * self::BADGES_PER_PILLAR;

        if ($pillarCount === 0 || $user->badge < $threshold) {
            return;
        }

        UserPiller::where('user_id', $user->id)->update(['completed' => 0]);
        $user->badge = 0;

        $currentIndex = array_search($user->experience_level, self::EXPERIENCE_LEVELS);
        if ($currentIndex !== false && isset(self::EXPERIENCE_LEVELS[$currentIndex + 1])) {
            $user->experience_level = self::EXPERIENCE_LEVELS[$currentIndex + 1];
        }
        $user->save();
    }

    public function incrementDecrement($piller_id, $user_id)
    {
        $pillar = UserPiller::find($piller_id);

        $pillar->increment('completed');
        $targetUser = User::find($user_id);
        $targetUser->increment('badge');

        $this->checkAndLevelUp($targetUser->fresh());

        return true;
    }
    public function pillarDetails(Request $request, UserPiller $pillar)
    {
        $badges = $request->user()->myBadges()->where('piller_id', $pillar->id)->with(['assinedBy:id,name', 'pillar:id,name,level,completed'])->latest()->get();
        return $this->success($badges, "successfully get pillar details");
    }
}
