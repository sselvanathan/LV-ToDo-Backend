<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(
            [
                'name'        => 'required|string|max:255',
                'email'       => 'required|string|email|max:255|unique:users',
                'password'    => ['required', 'confirmed', Password::defaults()],
                'device_name' => 'required',
            ]
        );

        $deviceName = $request->device_name;

        $user = User::create(
            [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'device_name' => $deviceName,
            ]
        );

        return $user->createToken($deviceName)->plainTextToken;
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                'email'       => 'required|string|email|max:255',
                'password'    => ['required', Password::defaults()],
                'device_name' => 'required',
            ]
        );

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(
                [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            );
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request): Response
    {
        $user = User::where('email', $request->email)->first();

        $user?->tokens()->delete();

        return response()->noContent();
    }
}
