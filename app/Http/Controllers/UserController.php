<?php
namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function login(Request $request): JsonResponse
    {
        try {
            $mail    = $request->email;
            $otp     = rand(100000, 999999);
            $details = [
                'code' => $otp,
            ];
            Mail::to($mail)->send(new OTPMail($details));
            User::updateOrCreate(
                [
                    'email' => $mail,
                ],
                ['email' => $mail,
                    'otp'    => $otp,
                ]
            );
            return ResponseHelper::success($details, 'OTP sent successfully');

        } catch (Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }

    }

    public function verifyOtp(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
            if ($user) {
                User::where('email', $request->email)->update(['otp' => 0]);
                $token = JWTToken::generateToken($user->email, $user->id);
                return ResponseHelper::success(['token' => $token], 'OTP verified successfully')->cookie('token', $token, 60 * 24 * 30);
            }
            return ResponseHelper::error('Invalid OTP');
        } catch (Exception $e) {
            return ResponseHelper::error($e->getMessage());
        }
    }

    public function logout()
    {
        return ResponseHelper::success(['message' => 'Logged out successfully'])->cookie('token', '', -1);
    }

}
