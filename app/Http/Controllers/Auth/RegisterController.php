<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * URL to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Initialize the controller and apply middleware.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form with a specified role.
     *
     * @param  string  $role
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showRegistrationForm(string $role = 'user')
    {
        return $this->view('auth.register', compact('role'));
    }

    /**
     * Show the admin registration form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showAdminRegistrationForm()
    {
        return $this->showRegistrationForm('admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, $this->validationRules());
    }

    /**
     * Define validation rules for registration.
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        $role = request()->input('role', 'user'); // Default role

        return User::create($this->userData($data, $role));
    }

    /**
     * Prepare user data for creation.
     *
     * @param  array  $data
     * @param  string  $role
     * @return array
     */
    protected function userData(array $data, string $role): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ];
    }

    /**
     * Handle the post-registration process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, User $user): Response
    {
        return $this->redirectUser($user);
    }

    /**
     * Redirect users based on their role.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectUser(User $user): Response
    {
        if ($user->isAdmin()) {
            return Redirect::route('admin.dashboard');
        }

        return Redirect::intended($this->redirectPath());
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