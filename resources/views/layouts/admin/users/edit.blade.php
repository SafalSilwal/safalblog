@extends('layouts.admin')

@section('title',  'Edit User')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="mb-0">{{  'Edit User' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{  route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name',  $user->name ) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        value="{{ old('email',  $user->email ) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="is_admin" class="form-label">Is Admin?</label>
                    <select class="form-select @error('is_admin') is-invalid @enderror" id="is_admin" name="is_admin" required>
    @if (auth()->user()->isAdmin())
        <option value="1" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>Admin</option>
        <option value="0" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Normal</option>
    @else
        <option value="0" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Normal</option>
        <option value="1" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>Admin</option>
    @endif
</select>

@error('is_admin')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

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
                        {{  'Update User'  }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
