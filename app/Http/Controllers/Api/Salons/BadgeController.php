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
        'mastery',
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
            'perfomence_level' => 'required|string|in:foundation,advanced,mastery',
            'is_visible'       => 'required|boolean',
        ]);

        $salonId = $request->user()->currentSalon->salon_id ?? null;
        if (! $salonId) {
            return $this->error([], 'No salon assigned.', 404);
        }
        $validated['salon_id'] = $salonId;

        $user   = User::findOrFail($validated['user_id']);
        $pillar = UserPiller::where('id', $validated['piller_id'])->where('user_id', $validated['user_id'])->first();

        if (! $pillar) {
            return $this->error([], 'This pillar does not belong to the selected user', 422);
        }
        if ($user->experience_level !== $validated['perfomence_level']) {
            return $this->error([], "You can only assign badges for this {$user->experience_level} level", 422);
        }
        if ($pillar->completed >= self::BADGES_PER_PILLAR) {
            return $this->error([], 'Pillar already completed', 422);
        }

        $isOwner = $request->user()->role === 'owner';
        if ($isOwner) {
            $validated['status'] = 'approved';
        }

        $badge = DB::transaction(function () use ($request, $validated, $user, $pillar, $isOwner) {
            $badge = $request->user()->badge_assigned_by()->create($validated);

            if ($isOwner) {
                $this->incrementDecrement($pillar, $user);
            }

            return $badge;
        });

        return $this->success($badge, 'badges create successfully');
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
            DB::transaction(function () use ($badge, $validated) {
                $oldPillar = UserPiller::find($badge->piller_id);
                $oldPillar?->decrement('completed');

                $newPillar = UserPiller::find($validated['piller_id']);
                $newPillar?->increment('completed');
            });
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

        if ($user->role !== 'owner' || $badge->salon_id !== $user->currentSalon?->salon_id) {
            return $this->error([], "you can't take this action", 403);
        }

        $badge->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            $pillar     = UserPiller::findOrFail($badge->piller_id);
            $targetUser = User::findOrFail($badge->user_id);
            $this->incrementDecrement($pillar, $targetUser);
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

    public function pillarDetails(Request $request, UserPiller $pillar)
    {
        $badges = $request->user()->myBadges()->where('piller_id', $pillar->id)->with(['assinedBy:id,name', 'pillar:id,name,level,completed'])->latest()->get();
        return $this->success($badges, "successfully get pillar details");
    }

    public function incrementDecrement(UserPiller $pillar, User $user): bool
    {
        $pillar->increment('completed');
        $user->increment('badge');
        $this->checkAndLevelUp($user);

        return true;
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
            $user->tier_level = $currentIndex + 1;
        }
        $user->save();
    }

}
