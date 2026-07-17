<?php

namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\DailyTask;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DailyTaskController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => "required|exists:users,id",
            'title'       => "required|string",
            "description" => "required|string",
            'target_date' => "nullable|date",
        ]);

        $validated['salon_id'] = $request->user()->currentSalon->salon_id ?? null;
        if (empty($validated['salon_id'])) {
            return $this->error([], "You can't take this action");
        }
        $task = $request->user()->task_assinged_by()->create($validated);
        return $this->success($task, "Task created successfully");
    }

    public function update(Request $request, DailyTask $dailyTask)
    {
        $validated = $request->validate([
            'title'       => "sometimes|required|string",
            "description" => "sometimes|required|string",
            'target_date' => "sometimes|required|date",
        ]);

        if ($dailyTask->assigned_by !== $request->user()->id) {
            return $this->error([], "You are not authorized to update this task");
        }

        $dailyTask->update($validated);
        return $this->success($dailyTask->fresh(), "Task updated successfully");
    }

    public function markasCompleted(Request $request, DailyTask $dailyTask)
    {
        if ($dailyTask->user_id !== $request->user()->id) {
            return $this->error([], "You are not authorized to mark this task as completed");
        }
        $dailyTask->update(['is_completed' => true]);
        return $this->success($dailyTask, "Task marked as completed");
    }

    public function destroy(Request $request, DailyTask $dailyTask)
    {
        if ($dailyTask->assigned_by !== $request->user()->id) {
            return $this->error([], "You are not authorized to delete this task");
        }

        $dailyTask->delete();
        return $this->success([], "Task deleted successfully");
    }

    public function myTasks(Request $request, User $user)
    {
        $tasks = $user->myTask()
            ->whereIn('target_date', [
                today()->toDateString(),
                today()->copy()->subDay()->toDateString(),
            ])->latest()->get();

        return $this->success($tasks, "Successfully fetched my tasks");
    }


    public function journy(Request $request)
    {
        $user = $request->user();
        $pillars = $user->myPiller()->get();

        $pillars->each(function ($pillar) use ($user) {
            $pillar->setAttribute('is_completed', $pillar->completed >= 60);
            $pillar->setAttribute('total', 60);
            if ($pillar->completed >= 60) {
                $badge = $user->myBadges()->where('pillar_id', $pillar->id)->latest()->first();
                $pillar->setAttribute('completed_at', $badge?->updated_at);
            }
        });
        $next_level_need = max(0, ($pillars->count() * 60) - (int) $pillars->sum('completed'));
        $response = [
            'user'  => $user,
            'pillars' => $pillars,
            'next_level_need' => $next_level_need,
        ];

        return $this->success($response, "Journey fetched successfully");
    }
}
