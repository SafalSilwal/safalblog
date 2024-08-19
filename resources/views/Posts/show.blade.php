@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Blog Post Details</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
    <div class="card">
        <div class="card-header">
            Post Details
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>ID:</strong> {{ $post->id }}
                </li>
                <li class="list-group-item">
                    <strong>Title:</strong> {{ $post->title }}
                </li>
                <li class="list-group-item">
                    <strong>Content:</strong>
                    <p>{{ $post->content }}</p>
                </li>
                <li class="list-group-item">
                    <strong>Author:</strong> {{ $post->user->name }} <!-- Assuming the Post model has a relationship with the User model -->
                </li>
            </ul>
            @if(Auth::id() == $post->user_id)
            <div class="mt-3">
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
