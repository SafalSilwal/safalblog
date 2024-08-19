<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verify the user is logged in
        if (!$this->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        // Verify the user has admin privileges
        if ($this->isAdmin()) {
            return $next($request);
        }

        return $this->redirectToPosts();
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Redirect to the login route with an error message.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToLogin(): Response
    {
        return redirect()->route('login')->with('error', 'Please log in to access this page.');
    }

    /**
     * Check if the authenticated user is an admin.
     *
     * @return bool
     */
    protected function isAdmin(): bool
    {
        return Auth::user()-> isAdmin();
    }

    /**
     * Redirect to the posts index route with an error message.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToPosts(): Response
    {
        return redirect()->route('posts.index')->with('error', 'You do not have permission to access admin.');
    }
}
