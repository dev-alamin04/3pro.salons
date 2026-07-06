<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $users = User::query()->verified()->active()->user()->latest()->get();

        return $this->success($users, 'Users retrieved successfully.');
    }

    public function show(User $user)
    {
        if (! $user) {
            return $this->error(null, 'User not found', 404);
        }
        return $this->success($user, 'User details retrieved');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        deleteById(User::class, $user->id, 'image');

        return $this->success([], 'Account deleted successfully.');
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('avatar_path')) {
            $data['avatar_path'] = uploadFile($request->file('avatar_path'), 'uploads/profileImages');
        }

        updateAndRespond(User::class, $data, $user->id, 'avatar_path');

        return $this->success($user->refresh(), 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|different:current_password|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        $user = $request->user(); // Authenticated user via Sanctum

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->error(null, 'Current password is incorrect.', 401);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return $this->success([], 'Password updated successfully.');
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        $user->load('m')
        return $this->success($user->load('settings'), 'User profile retrieved successfully.');
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }
        return $this->success([], 'Logged out successfully.');
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success([], 'Logged out from all devices.');
    }
}
