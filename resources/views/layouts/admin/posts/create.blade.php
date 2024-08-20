@extends('layouts.admin')


@section('title',  'Create Blog Post')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{  'Create Blog Post' }}</h2>

    <form action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        @method('POST')

        <!-- Title Input -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                value="" required>
        </div>

        <!-- Content -->
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">{{  'Create Post' }}</button>
    </form>
</div>
@endsection
