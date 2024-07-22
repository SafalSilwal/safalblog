<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all(); 

        return view('Posts.index',compact('posts'));
    }
    
    /**
     * Show the form for creating a new resources.
     */
    public function create()
    {
        return view('Posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]); 

        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        return view('Posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
