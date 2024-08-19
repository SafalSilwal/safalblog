<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogModel;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display the user's posts or redirect based on user role.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($this->isUnauthenticated($user)) {
            return $this->redirectToLogin();
        }

        if ($this->isAdmin($user)) {
            return $this->redirectToAdminDashboard();
        }

        if ($this->canViewPosts($user)) {
            $posts = $this->getUserPosts($user);
            return view('show', compact('posts'));
        }

        return $this->handleUnauthorizedAccess();
    }

    /**
     * Show the page to create a new post.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a new post in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validatePost($request);

        if (!$this->isAuthenticated()) {
            return $this->redirectToLoginWithMessage('Please log in to create a post.');
        }

        $this->createPost($request);

        return $this->redirectWithSuccess('posts.index', 'Post created successfully.');
    }

    /**
     * Show the page to edit a post.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $post = $this->findPostOrFail($id);

        if (!$this->canEditPost($post)) {
            return $this->redirectWithError('posts.index', 'You do not have permission to edit this post.');
        }

        return view('create', compact('post'));
    }

    /**
     * Update a post in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validatePost($request);

        $post = $this->findPostOrFail($id);

        if (!$this->canEditPost($post)) {
            return $this->redirectWithError('posts.index', 'You do not have permission to update this post.');
        }

        $this->updatePost($post, $request);

        return $this->redirectWithSuccess('posts.index', 'Post updated successfully.');
    }

    /**
     * Delete a post from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $post = $this->findPostOrFail($id);

        if (!$this->canEditPost($post)) {
            return $this->redirectWithError('posts.index', 'You do not have permission to delete this post.');
        }

        $post->delete();

        return $this->redirectWithSuccess('posts.index', 'Post deleted successfully.');
    }

    /**
     * Check if the user is unauthenticated.
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    protected function isUnauthenticated($user)
    {
        return !$user;
    }

    /**
     * Redirect to the login page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToLogin()
    {
        return redirect()->route('login');
    }

    /**
     * Check if the user is an admin.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function isAdmin($user)
    {
        return $user->isAdmin();
    }

    /**
     * Redirect to the admin dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToAdminDashboard()
    {
        return redirect()->route('admin.dashboard')->with('error', 'Admins are not allowed to view user or author page.');
    }

    /**
     * Determine if the user can view posts.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function canViewPosts($user)
    {
        return $user->isUser() || $user->isAuthor();
    }

    /**
     * Get posts for the user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUserPosts($user)
    {
        return BlogModel::where('user_id', $user->id)->get();
    }

    /**
     * Handle unauthorized access.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleUnauthorizedAccess()
    {
        return redirect()->back()->with('error', 'You are not allowed to view this page.');
    }

    /**
     * Validate post input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validatePost(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        return Auth::check();
    }

    /**
     * Redirect to the login page with an error message.
     *
     * @param  string  $route
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToLoginWithMessage($message)
    {
        return redirect()->route('login')->with('error', $message);
    }

    /**
     * Create a new post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function createPost(Request $request)
    {
        BlogModel::create(array_merge(
            $request->only(['title', 'author', 'description', 'date']),
            ['user_id' => Auth::id()]
        ));
    }

    /**
     * Find a post by ID or fail.
     *
     * @param  int  $id
     * @return \App\Models\BlogModel
     */
    protected function findPostOrFail($id)
    {
        return BlogModel::findOrFail($id);
    }

    /**
     * Check if the user can edit the post.
     *
     * @param  \App\Models\BlogModel  $post
     * @return bool
     */
    protected function canEditPost($post)
    {
        $user = Auth::user();
        return $user && ($user->id === $post->user_id || $user->isAdmin());
    }

    /**
     * Update a post.
     *
     * @param  \App\Models\BlogModel  $post
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function updatePost(BlogModel $post, Request $request)
    {
        $post->update($request->only(['title', 'author', 'description', 'date']));
    }

    /**
     * Redirect with a success message.
     *
     * @param  string  $route
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithSuccess($route, $message)
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Redirect with an error message.
     *
     * @param  string  $route
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithError($route, $message)
    {
        return redirect()->route($route)->with('error', $message);
    }
}
