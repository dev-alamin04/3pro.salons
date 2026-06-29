<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TeamManagementController extends Controller
{
    use ApiResponse;

    public function createAccount(Request $request)
    {
        if ($request->user()->role !== "owner") {
            return $this->error("Only Owner or manager can create new account");
        }
        $salon_id = $request->user()->mysalon->id;
        $user     = app(UserController::class)->userCreate($request, $salon_id);
        return $this->success($user, "account create successfully");
    }

    public function myTeams(Request $request)
    {
        $user = $request->user();

        $teammembers = $user->mysalon()->team($user->mysalon->salon_id, "owner")->get();
    }
}
