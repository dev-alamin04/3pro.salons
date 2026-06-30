<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Models\UserSalon;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TeamManagementController extends Controller
{
    use ApiResponse;

    public function createAccount(Request $request)
    {
        if ($request->user()->role !== 'owner') {
            return $this->error("Only Owner or manager can create new account");
        }
        $salon_id = $request->user()->currentSalon?->salon_id;
        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }
        $user = app(UserController::class)->userCreate($request, $salon_id);
        return $this->success($user, 'Account created successfully');
    }

    public function myTeams(Request $request)
    {
        $user     = $request->user();
        $salon_id = $user->currentSalon?->salon_id;

        if (! $salon_id) {
            return $this->error([], 'No salon assigned.');
        }

        $teamMembers = UserSalon::team($salon_id, 'owner')
            ->with([
                'user:id,name,email,role,avatar_path,specialist,pronoun,exprience_level',
                'user.myPiller:id,user_id,name,level,completed',
            ])->get();

        return $this->success($teamMembers, 'Successfully fetched team members.');
    }
}
