<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Apply middleware to ensure only admins can access these routes.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a list of all blog posts.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = $this->fetchAllPosts();
        return $this->renderView('layouts.admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating or editing a blog post.
     *
     * @param  \App\Models\BlogModel|null  $post
     * @return \Illuminate\Contracts\View\View
     */
    public function create(BlogModel $post = null)
    {
        return $this->renderView('layouts.admin.posts.create', ['post' => $post]);
    }

    /**
     * Store a newly created blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $this->savePost($request);

        return $this->redirectWithSuccess('admin.posts.index', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing a blog post.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $post = $this->findPostOrFail($id);
        return $this->renderView('layouts.admin.posts.create', ['post' => $post]);
    }

    /**
     * Update the specified blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogModel  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, BlogModel $post)
    {
        $this->validateRequest($request);

        $this->updatePost($post, $request);

        return $this->redirectWithSuccess('admin.posts.index', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post.
     *
     * @param  \App\Models\BlogModel  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BlogModel $post)
    {
        $post->delete();
        return $this->redirectWithSuccess('admin.posts.index', 'Blog post deleted successfully.');
    }

    /**
     * Fetch all blog posts from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function fetchAllPosts()
    {
        return BlogModel::all();
    }

    /**
     * Validate the blog post request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);
    }

    /**
     * Save a new blog post to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function savePost(Request $request)
    {
        BlogModel::create([
            'title' => $request->title,
            'Author' => $request->author,
            'Description' => $request->description,
            'Date' => $request->date,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Find a blog post by ID or fail.
     *
     * @param  int  $id
     * @return \App\Models\BlogModel
     */
    protected function findPostOrFail($id)
    {
        return BlogModel::findOrFail($id);
    }

    /**
     * Update a blog post.
     *
     * @param  \App\Models\BlogModel  $post
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function updatePost(BlogModel $post, Request $request)
    {
        $post->update([
            'title' => $request->title,
            'Author' => $request->author,
            'Description' => $request->description,
            'Date' => $request->date,
        ]);
    }

    /**
     * Render a view with data.
     *
     * @param  string  $view
     * @param  array  $data
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderView($view, array $data)
    {
        return view($view, $data);
    }

    /**
     * Redirect to a route with a success message.
     *
     * @param  string  $route
     * @param  string  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithSuccess($route, $message)
    {
        return redirect()->route($route)->with('success', $message);
    }
}
