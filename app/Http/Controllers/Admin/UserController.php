<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Initialize controller and apply middleware.
     */
    public function __construct()
    {
     
        $this->middleware('admin');
        $this->middleware('isadmin');
      
        
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all(); // Retrieve all users
        return $this->view('layouts.admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating or editing a user.
     *
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(User $user = null)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }
        return $this->view('layouts.admin.users.create', compact('user'));

    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateUser($request);

        User::create($this->userData($request));

        $users = User::all(); // Retrieve all users
        return $this->view('layouts.admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }
        $user = User::findOrFail($id); // Find user by ID or fail
        return $this->view('layouts.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->validateUser($request);

        $user->fill($this->userData($request));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save(); // Save updated user

        return redirect()->back()->with('message', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete(); // Delete the user
        return redirect()->back()->with('message', 'Blog post deleted successfully.');
    }

    /**
     * Validate user request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateUser(Request $request): void
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users' . ($request->route('user') ? ",{$request->route('user')->id}" : ''),
            'role' => 'required|string|in:admin,user,author',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Prepare user data for creation or update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function userData(Request $request): array
    {
        return [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : null,
        ];
    }

    /**
     * Redirect with success message.
     *
     * @param  string  $route
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithSuccess(string $route, string $message)
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Return a view with the given path and data.
     *
     * @param  string  $path
     * @param  array   $data
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function view(string $path, array $data)
    {
        return view($path, $data);
    }
}
