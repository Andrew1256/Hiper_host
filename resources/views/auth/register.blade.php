@extends('layouts.app')

@section('title', 'Join HiperBlog Author Community')

@section('content')
<div class="row justify-content-center min-vh-75 align-items-center py-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">Create Author Account</h2>
                    <p class="text-muted">Start sharing your stories with the world.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted">Full Name</label>
                        <input id="name" type="text" class="form-control form-control-lg bg-light border-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label small text-muted">Email Address</label>
                        <input id="email" type="email" class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label small text-muted">Password</label>
                        <input id="password" type="password" class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" name="password" required placeholder="Choose a strong password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label small text-muted">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control form-control-lg bg-light border-0" name="password_confirmation" required placeholder="Repeat your password">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg rounded-3 py-3 fw-semibold">
                            Create Account
                        </button>
                    </div>

                    <div class="text-center mt-4 pt-2">
                        <p class="text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none border-bottom border-2 border-dark pb-1">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
