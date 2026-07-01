<?php
namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\Salon;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use ApiResponse;

    private const REGISTER_CACHE_TTL_MINUTES = 10;

    public function register(StoreUserRequest $request)
    {
        $data              = $request->validated();
        $data['role']      = 'owner'; // Default role for registration
        $data['password']  = Hash::make($request->password);
        $data['joined_at'] = now()->toDateTimeString();

        Cache::put(
            $this->registerDataCacheKey($data['email']),
            $data,
            now()->addMinutes(self::REGISTER_CACHE_TTL_MINUTES)
        );

        return OtpHelper::sendEmailOtp($data['email'], 'register');
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        if (! Cache::has($this->registerDataCacheKey($request->email))) {
            return $this->error(null, 'No pending registration found for this email', 404);
        }

        return OtpHelper::sendEmailOtp($request->email, 'register');
    }

    public function verifyRegisterOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        $otpCacheKey = 'user_otp_' . $request->email;
        $cachedOtp   = Cache::get($otpCacheKey);

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return $this->error(null, 'OTP expired or invalid!', 400);
        }

        $dataCacheKey = $this->registerDataCacheKey($request->email);
        $data         = Cache::get($dataCacheKey);

        if (! $data) {
            return $this->error(null, 'Registration data expired, please register again', 400);
        }

        $location = $data['location'] ?? null;
        unset($data['location']);

        $user = User::create($data);

        $code = app(UserController::class)->generateSecretKey($user);
        $user->update(['secret_key' => $code]);

        $piller = ['time', 'cleanliness', 'appearance', 'self_motivation', 'downtime'];
        foreach ($piller as $p) {
            $user->myPiller()->create(['name' => $p]);
        }

        $salon = Salon::create([
            'name'     => $data['name'],
            'location' => $location,
        ]);

        $user->salon_assigned_by()->create([
            'salon_id'   => $salon->id,
            'is_current' => true,
            'user_id'    => $user->id,
        ]);

        $user->update([
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Clear OTP and cached registration data
        Cache::forget($otpCacheKey);
        Cache::forget($dataCacheKey);

        return $this->success([
            'user'  => $user->fresh(),
            'token' => $token,
        ], 'Email verified successfully!');
    }

    private function registerDataCacheKey(string $email): string
    {
        return 'user_register_data_' . $email;
    }
}