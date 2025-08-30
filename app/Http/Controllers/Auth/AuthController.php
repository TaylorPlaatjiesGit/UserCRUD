<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form
     *
     * @return View
     */
    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Perform the user login 
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        dd(User::all());

        if (Auth::attempt([ $request->email, $request->password ])) {
            return redirect()->intended('/home');
        }

        return back()
            ->withErrors([
                'email' => 'Could not log the user in. Please try again.',
            ])
            ->withInput();
    }

    /**
     * Log the user out
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        return redirect('/login');
    }
}
