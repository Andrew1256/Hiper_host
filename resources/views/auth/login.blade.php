@extends('layouts.app')

@section('title', 'Login — HiperBlog')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold">Welcome Back</h2>
                        <p class="text-muted">Login to manage your articles</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small">Email Address</label>
                            <input id="email" type="email"
                                class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="[EMAIL-ADDRESS]">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold small">Password</label>
                            <input id="password" type="password"
                                class="form-control rounded-3 py-2 @error('password') is-invalid @enderror" name="password"
                                required autocomplete="current-password" placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted small fw-bold">← Back to Homepage</a>
            </div>
        </div>
    </div>
@endsection