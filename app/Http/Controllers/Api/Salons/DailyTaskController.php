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
        $dailyTask->update(['status' => 'completed']);
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
        $tasks = $user->myTask()->where('target_date', today())->orWhere('target_date', today()->subDay())->latest()->get();
        return $this->success($tasks, "Successfully fetched my tasks");
    }
}
