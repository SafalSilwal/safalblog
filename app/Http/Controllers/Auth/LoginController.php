<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * URL to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Initialize the controller with middleware.
     */
    public function __construct()
    {
        $this->applyMiddlewares();
    }

    /**
     * Apply the necessary middleware.
     *
     * @return void
     */
    protected function applyMiddlewares(): void
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle user authentication and redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect users based on their role.
     *
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('posts.index');
    }
}