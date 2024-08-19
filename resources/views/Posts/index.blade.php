@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Blog Posts</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create Post</a>
    
    @if($posts->isEmpty())
        <p>No posts available.</p>
    @else
        <ul class="list-group">
            @foreach ($posts as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
