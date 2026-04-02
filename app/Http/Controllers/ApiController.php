<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiController extends Controller
{
   
    public function sendOtp(Request $request)
    {
        Log::info('SEND OTP REQUEST', $request->all());

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            Log::warning('SEND OTP FAILED - INVALID CREDENTIALS', [
                'email' => $request->email
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        // OTP (for testing)
        $otp = 123456;

        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        Log::info('OTP GENERATED', [
            'email' => $user->email,
            'otp' => $otp
        ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // remove in production
        ]);
    }

    
    public function loginWithOtp(Request $request)
    {
        Log::info('LOGIN OTP REQUEST', $request->all());

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {

            Log::warning('LOGIN FAILED - USER NOT FOUND', [
                'email' => $request->email
            ]);

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $otpMatch = (string)$user->otp === (string)$request->otp;
        $notExpired = Carbon::now()->lt($user->otp_expires_at);

        if ($otpMatch && $notExpired) {

            // Create token
            $token = $user->createToken('api-token')->plainTextToken;

            Log::info('LOGIN SUCCESS', [
                'email' => $user->email,
                'token' => $token
            ]);

            // Clear OTP
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user
            ]);
        }

        Log::warning('LOGIN FAILED - INVALID OTP', [
            'email' => $request->email
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired OTP'
        ], 401);
    }

    public function resetPassword(Request $request)
    {
        Log::info('RESET PASSWORD REQUEST', $request->all());

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!$user) {
            Log::error('RESET PASSWORD FAILED - USER NULL');

            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        if (!Hash::check($request->current_password, $user->password)) {

            Log::warning('RESET PASSWORD FAILED - WRONG OLD PASSWORD', [
                'email' => $user->email
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Old password incorrect'
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('PASSWORD UPDATED', [
            'email' => $user->email
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('LOGOUT REQUEST', [
            'user' => $request->user()
        ]);

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}