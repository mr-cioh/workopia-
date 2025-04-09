<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    // @desc Show register form
    // @route GET /register
    public function register(): View
    {
        return View('auth.register');
    }

    // @desc  Store new user
    // @route POST /register
    public function store(Request $request): RedirectResponse
    {   
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);
        
        return redirect()->route('home')->with('success', 'Registration successful You can now log in!');

    }
}
