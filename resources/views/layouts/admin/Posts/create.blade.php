@extends('layouts.admin')

@section('title', $post->exists ? 'Edit Blog Post' : 'Create Blog Post')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{ $post->exists ? 'Edit Blog Post' : 'Create Blog Post' }}</h2>

    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST">
        @csrf
        @method($post->exists ? 'PUT' : 'POST')

        <!-- Title Input -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                value="{{ old('title', $post->title) }}" required>
        </div>

        <!-- Author Input -->
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" 
                value="{{ old('author', $post->author) }}" required>
        </div>

        <!-- Description Input -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <!-- Date Input -->
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" 
                value="{{ old('date', $post->date) }}" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">{{ $post->exists ? 'Update Post' : 'Create Post' }}</button>
    </form>
</div>
@endsection
