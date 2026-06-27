<?php
namespace App\Http\Controllers\Api\Auth;

use App\Actions\CreateTenantAction;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use ApiResponse;
    

    // public function register(StoreUserRequest $request)
    // {
    //     $data = $request->validated();
    //     $role = $data['role'];
    //     unset($data['role']);
    //     $data['password'] = Hash::make($request->password);

    //     $data['joined_at'] = now()->toDateTimeString();
        
    //     $user = User::create($data);

    //     $user->settings()->create([]);

    //     // Assign role based on selection
    //     $user->assignRole($role);
        
    //     if ($user) {
    //         return OtpHelper::sendEmailOtp($user->email, 'register');
    //     }

    //     return $this->error($user, 'Failed to register user', 500);
    // }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        return OtpHelper::sendEmailOtp($request->email, 'register');
    }

    public function verifyRegisterOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        $cacheKey  = 'user_otp_' . $request->email;
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return $this->error(null, 'OTP expired or invalid!', 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error(null, 'User not found', 404);
        }

        // Verify email
        $user->update([
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Clear OTP
        Cache::forget($cacheKey);

        return $this->success([
            'user' => $user->fresh()->load('roles'),
            'token' => $token,
        ], 'Email verified successfully!');
    }
}