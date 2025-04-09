<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class LoginController extends Controller
{   
    // @desc Show login form 
    // @route GET /register
    public function Login(): View 
    {
        return View('auth.login');
    }
}
