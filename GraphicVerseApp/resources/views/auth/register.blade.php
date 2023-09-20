<link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name Input -->
                            <div class="col-md form-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" autocomplete="name" autofocus
                                        placeholder="{{ __('Name') }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div class="col-md form-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" autocomplete="email" placeholder="{{ __('Email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Username Input -->
                            <div class="col-md form-group">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <input id="username" type="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username') }}" autocomplete="username"
                                        placeholder="{{ __('Username') }}">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="col-md form-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password" autocomplete="new-password"
                                        placeholder="{{ __('Password') }}">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="col-md form-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password"
                                        placeholder="{{ __('Confirm Password') }}">
                            </div>

                            <!-- Register Button -->
                            <div class="row mt-4">
                                
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-user-plus"></i> <!-- User Plus Icon -->
                                        <span>{{ __('Register') }}</span>
                                    </button>
                            </div>
                            <div class="login mt-4">
                                <a class="btn2" href="{{ route('login') }}">
                                        {{ __('Back to log in') }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

