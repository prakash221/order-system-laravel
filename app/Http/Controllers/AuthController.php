<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match on the system.', 401);
        }
        $user = User::where('email', $request->email)->first();
        $expirationTime = now()->addHours(24); // set the expiration time to 24 hours from now


        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name, ['*'], now()->addHours(48))->plainTextToken,
            'expire_time' => $expirationTime
        ], 'Login Successful');
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of' . $user->name)->plainTextToken
        ], 'Registration Successful');
    }



    public function forgotpassword(ForgotPasswordRequest $request)
    {
        $request->validated($request->all());

        $user = User::find($request->id);
        if ($user && $user->email == $request->email) {
            //            $user->email = $request->newpassword;
            //            $user->save();
        }

        return $this->success(null, 'Please check your mail and proceed to password reset from the link.');
    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success(null, 'You have been logged out');
    }
}
