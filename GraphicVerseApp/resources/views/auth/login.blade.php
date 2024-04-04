<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container-fluid ">
    <div class="row justify-content-center py-4">
        <div class="col-md-8 d-flex justify-content-center">
            <h1 class="text-center mt-3 mb-5">Login</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check text-center">
                    <div class="d-inline-block">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-3">
                        {{ __('Login') }}
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>

                <hr>

                <div class="text-center">
                    <a class="btn2" href="{{ route('register') }}">
                        {{ __('Don\'t have an account? Sign up') }}
                    </a>
                </div>

                <div class="text-center">
                    <a class="btn2 mt-2" href="{{ route('admin.login') }}">
                        {{ __('Admin Login') }}
                    </a>
                </div>
            </form>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <img src="/svg/logo.svg" class="logo" alt="Logo">
        </div>
    </div>
</div>