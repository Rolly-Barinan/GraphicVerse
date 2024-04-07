
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
<div class="container">
    <div class="card border-0">
    <a class="navbar-brand" href="/">
            <img src="/svg/AdminGV.png" class="logo" alt="Logo">
        </a>
        <h1 class="text-center">Welcome back</h1>
        <p class="sub"></p>
        <div class="text-center">
                            <a class="btn2" href="{{ route('register') }}">
                                {{ __('Don\'t have an account? Sign up') }}
                            </a>
                        </div>
        <form method="POST" action="{{ route('login') }}" class = "form">
            @csrf
                    <div class="form">
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
                    </div>
                        <div class="text-center mt-3">
                            <a class="btn2 btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <div class="text-center">
                            <a class="admin mt-2" href="{{ route('admin.login') }}">
                                {{ __('Admin Login') }}
                            </a>
                        </div>
                    </form>
                </div>
</div>