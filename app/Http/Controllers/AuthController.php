<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    //  SEND OTP (BREVO API)
    public function sendOtp(Request $request)
    {
        Log::info('Send OTP Request Started', [
            'email' => $request->email
        ]);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user=User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            Log::warning('Invalid email or password attempt', [
                'email' => $request->email
            ]);

            return back()->with('error', 'Invalid email or password');
        }

        //  Generate OTP
        $otp = 123456; 

        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        Log::info('OTP Generated', [
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => $user->otp_expires_at
        ]);

        //  BREVO API CALL
        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            "sender" => [
                "name" => "OTP Login",
                "email" => "vijayj888888@gmail.com" // MUST be verified in Brevo
            ],
            "to" => [
                [
                    "email" => $user->email,
                    "name" => $user->name ?? "User"
                ]
            ],
            "subject" => "Your OTP Code",
            "htmlContent" => "
                <h2>OTP Verification</h2>
                <p>Your OTP is: <b>$otp</b></p>
                <p>This OTP is valid for 5 minutes.</p>
            "
        ]);

        Log::info('Brevo API Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if (!$response->successful()) {

            Log::error('Brevo OTP Failed', [
                'response' => $response->body(),
                'email' => $user->email
            ]);

            return back()->with('error', 'Failed to send OTP');
        }

        Log::info('OTP Email Sent Successfully', [
            'email' => $user->email
        ]);

        return back()
            ->with('success', 'OTP sent successfully')
            ->withInput($request->only('email', 'password'));
    }

    //  VERIFY OTP & LOGIN
    public function loginWithOtp(Request $request)
    {
        Log::info('OTP Login Attempt Started', [
            'email' => $request->email,
            'input_otp' => $request->otp
        ]);

        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user){
            Log::error('User not found', [
                'email' => $request->email
            ]);

            return back()->with('error', 'Invalid email');
        }

        Log::info('User Found', [
            'email' => $user->email,
            'db_otp' => $user->otp,
            'expires_at' => $user->otp_expires_at
        ]);

        $otpMatch = (string)$user->otp === (string)$request->otp;
        $notExpired = $user->otp_expires_at
            ? Carbon::now()->lt($user->otp_expires_at)
            : false;

        Log::info('OTP Validation Result', [
            'otp_match' => $otpMatch,
            'not_expired' => $notExpired,
            'current_time' => Carbon::now()->toDateTimeString()
        ]);

        if ($otpMatch && $notExpired) {

             Auth::login($user);

            $user->update([
                'otp' => null,
                'otp_expires_at' => null
            ]);

            Log::info('OTP Login Successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect('/dashboard')->with('success', 'Login successful');
        }

        Log::warning('OTP Login Failed', [
            'email' => $request->email,
            'reason' => 'Invalid or expired OTP'
        ]);

        return back()->with('error', 'Invalid or expired OTP');
    }


public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Logged out successfully');
}
public function resetPassword(Request $request)
{
    Log::info('Reset Password Attempt Started', [
        'user_id' => Auth::id()
    ]);

    // 1. VALIDATION
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password',
    ], [
        'current_password.required' => 'Please enter current password',
        'new_password.required' => 'Please enter new password',
        'confirm_password.required' => 'Please confirm password',
        'confirm_password.same' => 'New password and confirm password do not match',
    ]);

    $user = Auth::user();

    Log::info('Checking old password', [
        'user_id' => $user->id
    ]);

    // 2. CHECK OLD PASSWORD
    if(!Hash::check($request->current_password, $user->password)){

        Log::warning('Old password mismatch', [
            'user_id' => $user->id
        ]);

        return back()
            ->withErrors(['current_password' => 'Old password is incorrect'])
            ->withInput();
    }

    // 3. UPDATE PASSWORD
    $user->password = Hash::make($request->new_password);
    $user->save();

    Log::info('Password updated successfully', [
        'user_id' => $user->id
    ]);

    return back()->with('success', 'Password updated successfully');
}


}