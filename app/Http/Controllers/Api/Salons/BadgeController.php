<?php
namespace App\Http\Controllers\Api\Salons;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    use ApiResponse;

    public function storeBadge(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'piller_id'        => "required|exists:user_pillers,id,",
            "notes"            => "nullable|string",
            "perfomence_level" => "required|string",
            "is_visialbe"      => "required|boolean",
        ]);


    }
}
