<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\UserSkill;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $skills = $request->user()->userSkill()->get()->groupBy('skill_category')->map(function ($items, $category) {
            return [
                'skill_category' => $category,
                'skills'         => $items->values(),
            ];
        })->values();
        return $this->success($skills, "Successfully fetched skills");
    }

    public function show(Request $request, UserSkill $skill)
    {
        if (! $skill) {
            return $this->error([], "Skill not found");
        }
        return $this->success($skill, "Successfully fetched skill");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'skill_name'     => "required|string",
            'skill_category' => "required|string",
            'skill_level'    => "required|string",
            'note'           => "nullable|string",
        ]);

        $skill = $request->user()->userSkill()->create($validated);

        return $this->success($skill, "Skill created successfully");
    }

    public function update(Request $request, UserSkill $skill)
    {
        $validated = $request->validate([
            'skill_name'     => "sometimes|required|string",
            'skill_category' => "sometimes|required|string",
            'skill_level'    => "sometimes|required|string",
            'note'           => "nullable|string",
        ]);

        if (! $skill) {
            return $this->error([], "Skill not found");
        }

        $skill->update($validated);

        return $this->success($skill, "Skill updated successfully");
    }

    public function destroy(Request $request, UserSkill $skill)
    {
        if (! $skill) {
            return $this->error([], "Skill not found");
        }

        $skill->delete();

        return $this->success([], "Skill deleted successfully");
    }

}
