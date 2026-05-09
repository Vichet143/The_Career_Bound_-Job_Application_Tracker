<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Send reset link
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->input('email'))->first();  //first() is take only one record that match the condition

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $token = $this->createToken($user);

        $user->sendPasswordResetNotification($token);

        return response()->json([
            'message' => 'Password reset token generated and emailed.',
            'token' => $token
        ]);
    }

    private function createToken(User $user): string
    {
        // broker can respons for reset token ,checking token and reset password
        $broker = Password::broker();

        if (method_exists($broker, 'createToken')) {
            return $broker->createToken($user);
        }

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        return $token;
    }

    // Reset password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));
            }
        );

        return response()->json([
            'message' => __($status)
        ]);
    }
}
