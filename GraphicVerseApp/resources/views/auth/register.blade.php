<link href="{{ asset('css/register.css') }}" rel="stylesheet">
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>

<div class="container">
    <div class="card border-0">
        <a class="navbar-brand" href="/">
            <img src="/svg/AdminGV.png" class="logo" alt="Logo">
        </a>
        <h1 class="text-center">Create Account</h1>
        <p class="sub"></p>
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 h-10">
                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Name') }}">
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email') }}">
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="{{ __('Username') }}">
                            </div>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                            </div>
                        </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="fas fa-user-plus"></i>
                                        <span>{{ __('Register') }}</span>
                                    </button>
                                </div>

                                <div class="text-center mt-3">
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Back to log in') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>