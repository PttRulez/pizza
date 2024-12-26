<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): Response
    {
       
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return Response([
                'message' => 'Successful login!'
            ], 200);
        }
        
        return Response([
            'email' => 'Неверные email или пароль'
        ], 401);
    }
    
    public function logout(Request $request): Response
    {
        Auth::logout();
        
        return Response('разлогинился', 200);
    }
}
