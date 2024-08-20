@extends('layouts.admin')


@section('title',  'Edit Blog Post')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{  'Create Blog Post' }}</h2>

    <form action="{{  route('admin.posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Title Input -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                value="{{ old('title', $post->title) }}" required>
        </div>

     

        <!-- content Input -->
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content', $post->content) }}</textarea>
        </div>

      
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">{{ 'Update Post' }}</button>
    </form>
</div>
@endsection
