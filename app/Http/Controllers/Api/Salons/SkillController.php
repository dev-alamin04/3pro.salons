<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSkill;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $skills = $request->user()->userSkill()->with('assignedBy:id,name')->latest()->get()->groupBy('skill_category')->map(function ($items, $category) {
            return [
                'skill_category' => $category,
                'skills'         => $items->values(),
            ];
        })->values();

        return $this->success($skills, "Successfully fetched skills");
    }

    public function userSkills(Request $request, User $user)
    {
        $skills = $user->userSkill()->with('assignedBy:id,name')->where('salon_id', $user->currentSalon?->salon_id)->latest()->get();
        return $this->success($skills, "Successfully fetched user skills");
    }

    public function show(Request $request, UserSkill $skill)
    {
        return $this->success($skill->load('assignedBy:id,name'), "Successfully fetched skill");
    }

    /**
     * Create a personal skill (assigned_by = null, user_id = auth user).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'skill_name'     => "required|string",
            'skill_category' => "required|string",
            'skill_level'    => "required|string",
            'note'           => "nullable|string",
        ]);

        $validated['salon_id'] = $request->user()->currentSalon?->salon_id;
        $skill                 = $request->user()->userSkill()->create($validated);

        return $this->success($skill, "Skill created successfully");
    }

    public function assignSkill(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => "required|exists:users,id",
            'skill_name'     => "required|string",
            'skill_category' => "required|string",
            'skill_level'    => "required|string",
            'note'           => "nullable|string",
        ]);

        $validated['salon_id']   = $request->user()->currentSalon?->salon_id;
        $validated['skill_type'] = 'assigned';
        $skill                   = $request->user()->skill_assigned_by()->create($validated);
        return $this->success($skill, "Skill assigned successfully");
    }

    public function update(Request $request, UserSkill $skill)
    {
        $validated = $request->validate([
            'skill_name'     => "sometimes|required|string",
            'skill_category' => "sometimes|required|string",
            'skill_level'    => "sometimes|required|string",
            'note'           => "nullable|string",
        ]);

        $user = $request->user();
        if ($skill->skill_type === "assigned" && ($skill->salon_id !== $user->currentSalon?->salon_id || $user->role === "staff")) {
            return $this->error([], "You can't update this skill");
        }
        $skill->update($validated);
        return $this->success($skill, "Skill updated successfully");
    }

    public function updateLevel(Request $request, UserSkill $skill)
    {
        $request->validate([
            'progress' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $user = $request->user();
        if ($skill->skill_type === "assigned" && ($skill->salon_id !== $user->currentSalon?->salon_id || $user->role === "staff")) {
            return $this->error([], "You can't update this skill");
        }
        $skill->update(['progress' => $request->progress]);
        $skill->update([
            'status' => $skill->progress >= 5 ? 'completed' : 'in_progress',
        ]);

        return $this->success($skill, 'Skill progress updated successfully');
    }

    public function destroy(Request $request, UserSkill $skill)
    {
        $user = $request->user();

        if ($skill->skill_type === "assigned" && ($skill->salon_id !== $user->currentSalon?->salon_id || $user->role === "staff")) {
            return $this->error([], "You can't delete this skill");
        }
        $skill->delete();
        return $this->success([], "Skill deleted successfully");
    }
}
