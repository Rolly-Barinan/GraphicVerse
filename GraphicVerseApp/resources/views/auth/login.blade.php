<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container-login ">
    <input type="checkbox" id="flip" class="invisible-checkbox d-none">
    <div class="cover">
        <div class="front">
            <img src="/svg/front-img.png" alt="">
            <div class="text">
                <span class="text-1">Access a treasure trove of digital wonders</span>
                <span class="text-2">Welcome back</span>
            </div>
        </div>
        <div class="back">
            <img src="/svg/back-img.png" alt="">
            <div class="text">
                <span class="text-1">Complete miles of journey<br>with one step</span>
                <span class="text-2">Let's get started</span>
            </div>
        </div>
    </div>
    <form action="#">
        <div class="form-content">
        <div class="login-form">
            <div class="title">Login</div>
            <div class="input-boxes">
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="text" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="text" placeholder="Enter your password" required>
                </div>
                <div class="text"><a href="#" class="" id="">Forgot password?</a></div>
                <div class="button input-box">
                    <input type="submit" placeholder="Submit">
                </div>
                <div class="text login-text">Don't have an account? <label for="flip">Signup now</label></div>
            </div>
            
        </div>
        <div class="signup-form">
            <div class="title">Signup</div>
            <div class="input-boxes">
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Enter your name" required>
                </div>
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="text" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="text" placeholder="Enter your password" required>
                </div>
                <div class="text"><a href="#" class="" id="">Forgot password?</a></div>
                <div class="button input-box">
                    <input type="submit" placeholder="Submit">
                </div>
            </div>
            <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
        </div>
    </form>
        </div>
</div>