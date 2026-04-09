<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'sex' => 'required|in:male,female',
            'dob' => 'required|date',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create tenant linked to user
        $tenant = Tenant::create([
            'user_id' => $user->id,
            'sex' => $request->sex,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}