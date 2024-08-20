<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Apply middleware to ensure only admins can access these routes.
     */
    public function __construct()
    {
       
        $this->middleware('admin');
        $this->middleware('author');
        
        
    }

    /**
     * Display a list of all blog posts.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        dd(Auth::user()->hasRole('author'));
        $posts = $this->fetchAllPosts();
        return $this->renderView('layouts.admin.posts.index', ['posts' => $posts]);
    }
    public function show(Post $post)
    {
        $posts = $this->fetchAllPosts();
       
        return $this->renderView('layouts.admin.posts.index', ['posts' => $posts]);
    }
    /**
     * Show the form for creating or editing a blog post.
     *
     * @param  \App\Models\Post|null  $post
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        if (Auth::user()->hasRole('author')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }
        return view('layouts.admin.posts.create');
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

        $posts = $this->fetchAllPosts();
       
        return $this->renderView('layouts.admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for editing a blog post.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(  $id)
    {
        $post = $this->findPostOrFail($id);
        if (!Auth::user()->hasRole('author')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }
        return $this->renderView('layouts.admin.posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
      
        $this->validateRequest($request);

        $this->updatePost($post, $request);

        $posts = $this->fetchAllPosts();
       
        return $this->renderView('layouts.admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Remove the specified blog post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('message', 'Blog post deleted successfully.');

    }

    


    /**
     * Fetch all blog posts from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function fetchAllPosts()
    {
        return Post::all();
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
            'content' => 'required|string',
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
        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Find a blog post by ID or fail.
     *
     * @param  int  $id
     * @return \App\Models\Post
     */
    protected function findPostOrFail($id)
    {
        return Post::findOrFail($id);
    }

    /**
     * Update a blog post.
     *
     * @param  \App\Models\Post  $post
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function updatePost(Post $post, Request $request)
    {
        $post->update([
            'title' => $request->title,
          
            'content' => $request->content,
           
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
