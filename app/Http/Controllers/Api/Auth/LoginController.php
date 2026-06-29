<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;

    public function setPassword(Request $request)
    {
        $validated = $request->validate([
            "password"   => "required|string|min:8",
            "secret_key" => "required|exists:users,secret_key",
        ]);

        $user = User::where('secret_key', $validated['secret_key'])->first();
        if (! $user || $user->is_used_key == true) {
            return $this->error([], 'Secret key is invalid or already used', 422);
        }
        $user->update(['password' => Hash::make($validated['password']), 'email_verified_at' => now(), 'is_used_key' => true]);
        $piller = ['time', 'cleanliness', 'appearance', 'self_motivation', 'downtime'];
        foreach ($piller as $p) {
            $user->myPiller()->create(['name' => $p]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->success(['user' => $user,
            'token'                       => $token,
            'token_type'                  => 'Bearer'], 'user password set successfully', 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user->email_verified_at) {
            return $this->error(null, 'Please verify your email before logging in.', 403);
        }

        if (! Hash::check($request->password, $user->password)) {
            return $this->error(null, 'Invalid email or password', 401);
        }

        // Check account status
        if (! $user->is_active) {
            return $this->error(null, 'Your account is inactive. Please contact support.', 403);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user'       => $user,
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        return OtpHelper::sendEmailOtp($request->email, 'forgot_password');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'        => 'required|email|exists:users,email',
            'otp'          => 'required|integer',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        $cacheKey  = 'user_otp_' . $request->email;
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return $this->error(null, 'OTP is invalid or expired.', 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->new_password)]);
        Cache::forget($cacheKey);

        return $this->success([], 'Password reset successfully.');
    }

    public function verifyOtp(Request $request)
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

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return $this->error(null, 'OTP is invalid or expired.', 400);
        }

        return $this->success([], 'OTP verified successfully.');
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        return OtpHelper::sendEmailOtp($request->email, 'forgot_password');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'Logged out successfully.');
    }
}
