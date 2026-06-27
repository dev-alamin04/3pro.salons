<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use App\Models\OnboardingSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    use ApiResponse;

    // Preset suggestions list
    public function index(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error([], "you can't take this action");
        }

        $presets = Onboarding::where('is_active', true)->select(['id', 'name'])->get();
        return $this->success($presets, 'Successfully fetched onboarding suggestions.');
    }

    public function getSalonOnboardings(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error([], "you can't take this action");
        }

        $salon = $request->user()->mysalon->salon;

        if (! $salon) {
            return $this->error([], 'No salon assigned.');
        }

        $onboardings = $salon->salonOnboardings()->select(['id', 'title', 'is_active'])->get();

        return $this->success($onboardings, 'Successfully fetched salon onboardings.');
    }

    public function syncOnboardings(Request $request)
    {
        $request->validate([
            'onboardings'   => 'required|array|min:1',
            'onboardings.*' => 'required|string|max:255',
        ]);

        if ($request->user()->role !== 'owner') {
            return $this->error([], "you can't take this action");
        }

        $salon = $request->user()->mysalon->salon;

        if (! $salon) {
            return $this->error([], 'No salon assigned.');
        }

        $existingTitles = $salon->salonOnboardings()->pluck('title')->toArray();

        $newOnboardings = collect($request->onboardings)->filter(fn($title) => ! in_array($title, $existingTitles))
            ->map(fn($title) => [
                'salon_id'   => $salon->id,
                'title'      => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

        if (! empty($newOnboardings)) {
            OnboardingSalon::insert($newOnboardings);
        }

        return $this->success([], 'Successfully added onboardings.');
    }

    // Single onboarding remove
    public function removeOnboarding(Request $request, OnboardingSalon $salonOnboarding)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error([], "you can't take this action");
        }

        $salon = $request->user()->mysalon->salon;

        if (! $salon || $salonOnboarding->salon_id !== $salon->id) {
            return $this->error([], 'Unauthorized.');
        }

        $salonOnboarding->delete();

        return $this->success([], 'Successfully removed onboarding.');
    }

    public function addOnboarding(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        if ($request->user()->role !== 'owner') {
            return $this->error([], "you can't take this action");
        }

        $salon = $request->user()->mysalon->salon;

        if (! $salon) {
            return $this->error([], 'No salon assigned.');
        }

        $onboarding = OnboardingSalon::create([
            'salon_id' => $salon->id,
            'title'    => $request->title,
        ]);

        return $this->success($onboarding, 'Successfully added onboarding.');
    }
}
