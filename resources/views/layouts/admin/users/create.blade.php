@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Create User')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="mb-0">{{ isset($user) ? 'Edit User' : 'Create User' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        @if (auth()->user()->isAdmin())
                            <option value="admin" {{ old('role', isset($user) ? $user->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="author" {{ old('role', isset($user) ? $user->role : '') == 'author' ? 'selected' : '' }}>Author</option>
                            <option value="user" {{ old('role', isset($user) ? $user->role : '') == 'user' ? 'selected' : '' }}>User</option>
                        @else
                            <option value="author" {{ old('role', isset($user) ? $user->role : '') == 'author' ? 'selected' : '' }}>Author</option>
                        @endif
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
