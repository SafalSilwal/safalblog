@extends('layouts.app')

@section('title', isset($post) ? 'Edit Blog Post' : 'Create Blog Post')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ isset($post) ? 'Edit Blog Post' : 'Create Blog Post' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($post) ? route('posts.update', $post->id) : route('posts.store') }}" method="POST">
                        @csrf

                        @if (isset($post))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', isset($post) ? $post->title : '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author"
                                value="{{ old('author', isset($post) ? $post->Author : '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                required>{{ old('description', isset($post) ? $post->Description : '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ old('date', isset($post) ? $post->Date : '') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">{{ isset($post) ? 'Update Post' : 'Create Post' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
