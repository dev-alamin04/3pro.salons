<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\UserSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiResponse;

    public function index(User $user)
    {
        $reports = $user->myReport()->get();
        return $this->success($reports, "Successfully fetched reports");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => "required|exists:users,id",
            'progress_type' => "required|string",
            'report_text'   => "required|string",
        ]);

        if (! $this->authorized($request->user(), $validated['user_id'])) {
            return $this->error([], "You can't take this action");
        }

        $validated['salon_id'] = $request->user()->currentSalon->salon_id ?? null;
        if (empty($validated['salon_id'])) {
            return $this->error([], "You can't take this action");
        }

        $report = $request->user()->report_assigned_by()->create($validated);
        return $this->success($report, "Report created successfully");
    }

    public function show(Report $report)
    {
        return $this->success($report->load(['user:id,name', 'reportedBy:id,name']), "Successfully fetched report");
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'progress_type' => "sometimes|required|string",
            'report_text'   => "sometimes|required|string",
        ]);

        if ($report->repoted_by !== $request->user()->id) {
            return $this->error([], "You are not authorized to update this report");
        }

        $report->update($validated);
        return $this->success($report->fresh(), "Report updated successfully");
    }

    public function destroy(Request $request, Report $report)
    {
        if ($report->repoted_by !== $request->user()->id) {
            return $this->error([], "You are not authorized to delete this report");
        }

        $report->delete();
        return $this->success([], "Report deleted successfully");
    }

    public function reportSummary(Request $request)
    {

        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;
        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }
        $reports          = Report::with(['user:id,name', 'reportedBy:id,name'])->where('salon_id', $salon_id)->where('created_at', '>=', now()->subDays(30))->latest()->get();
        $totalReports     = $reports->count();
        $teamMembersCount = UserSalon::team($salon_id, 'owner')->count();

        $teamMembers = app(TeamManagementController::class)->team($request);
        $badgesCount = $teamMembers->sum(function ($member) use ($salon_id) {
            return $member->user?->myBadges()->where('salon_id', $salon_id)->where('created_at', '>=', now()->subDays(30))->count() ?? 0;
        });

        $praiseCount = $teamMembers->sum(function ($member) use ($salon_id) {
            return $member->user?->myBadges()->where('salon_id', $salon_id)->where('is_visible', true)->where('created_at', '>=', now()->subDays(30))->count() ?? 0;
        });
        return $this->success([
            'total_reports'      => $totalReports,
            'team_members_count' => $teamMembersCount,
            'praise_count'       => $praiseCount,
            'badges_count'       => $badgesCount,
            'reports'            => $reports,
        ], "Successfully fetched report summary");

    }

    public function authorized(User $requester, int $targetUserId): bool
    {
        if (! in_array($requester->role, ['owner', 'lead'])) {
            return false;
        }

        $targetSalonId = User::find($targetUserId)?->currentSalon?->salon_id;

        return $requester->currentSalon?->salon_id === $targetSalonId && $targetSalonId !== null;
    }
}
