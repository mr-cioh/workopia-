<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
    public function store(): string
    {
        return 'store';
    }
}
