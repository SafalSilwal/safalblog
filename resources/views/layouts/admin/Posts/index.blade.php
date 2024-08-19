@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Blog Posts</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Create New Blog Post</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->author }}</td>
                        <td>{{ Str::limit($post->description, 50) }}</td>
                        <td>{{ $post->date->format('Y-m-d') }}</td>
                        <td class="d-flex">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm me-2">Edit</a>

                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No blog posts available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
